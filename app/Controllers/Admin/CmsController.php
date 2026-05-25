<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteContentModel;
use App\Libraries\ActivityLogger;

class CmsController extends BaseController
{
    public function index()
    {
        $model = new SiteContentModel();
        $contents = $model->orderBy('content_key', 'ASC')->findAll();

        $groupedContents = [];
        foreach ($contents as $c) {
            $parts = explode('_', $c['content_key']);
            $prefix = ucfirst($parts[0]);
            if (!isset($groupedContents[$prefix])) {
                $groupedContents[$prefix] = [];
            }
            $groupedContents[$prefix][] = $c;
        }

        $data = [
            'title' => 'CMS Manager',
            'groupedContents' => $groupedContents
        ];

        return view('admin/cms_manager', $data);
    }

    public function update()
    {
        $model = new SiteContentModel();
        $id = $this->request->getPost('id');
        $value = $this->request->getPost('content_value');

        $file = $this->request->getFile('image_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/cms/', $newName);
            $value = '/uploads/cms/' . $newName;
        }

        if ($id) {
            $model->update($id, ['content_value' => $value]);
            
            // Clear cache
            $content = $model->find($id);
            if ($content) {
                \Config\Services::cache()->delete('cms_content_' . $content['content_key']);
            }

            ActivityLogger::log('ADMIN_CMS', "Mengubah konten CMS ID #{$id}", session()->get('user_id'));
            return redirect()->to('/admin/cms')->with('success', 'Konten berhasil diperbarui.');
        }

        return redirect()->to('/admin/cms')->with('error', 'ID konten tidak valid.');
    }

    public function save_content()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Hanya AJAX']);
        }

        $key = $this->request->getPost('key');
        $value = $this->request->getPost('value');
        $type = $this->request->getPost('type') ?? 'text'; // Can be 'text', 'html', or 'image_upload'

        $model = new SiteContentModel();
        $row = $model->where('content_key', $key)->first();

        // Handle Image Uploads
        if ($type === 'image_upload') {
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/cms/', $newName);
                $value = '/uploads/cms/' . $newName;
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Upload gambar gagal']);
            }
        }

        if ($row) {
            $model->update($row['id'], ['content_value' => $value]);
        } else {
            $model->insert([
                'content_key' => $key,
                'content_value' => $value,
                'content_type' => ($type === 'image_upload' ? 'image' : $type)
            ]);
        }

        // Clear cache
        \Config\Services::cache()->delete('cms_content_' . $key);
        ActivityLogger::log('ADMIN_CMS', "Inline edit konten {$key}", session()->get('user_id'));

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Konten disimpan',
            'new_value' => $value
        ]);
    }
    public function batchUpdate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Hanya AJAX']);
        }

        $model = new SiteContentModel();
        
        $updatesStr = $this->request->getPost('updates');
        $deletionsStr = $this->request->getPost('deletions');
        $newKeysStr = $this->request->getPost('new_keys');

        $updates = json_decode($updatesStr, true) ?? [];
        $deletions = json_decode($deletionsStr, true) ?? [];
        $newKeys = json_decode($newKeysStr, true) ?? [];

        // 1. Proses Updates
        foreach ($updates as $id => $val) {
            $row = $model->find($id);
            if (!$row) continue;
            
            if ($val === '[NEW_FILE]') {
                $file = $this->request->getFile('file_' . $id);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/cms/', $newName);
                    $val = '/uploads/cms/' . $newName;
                } else {
                    continue; // Skip jika upload gagal
                }
            }
            
            $model->update($id, ['content_value' => $val]);
            \Config\Services::cache()->delete('cms_content_' . $row['content_key']);
        }

        // 2. Proses Deletions
        foreach ($deletions as $id) {
            $row = $model->find($id);
            if ($row) {
                // If it's an image, delete the physical file
                if ($row['content_type'] == 'image') {
                    $filePath = FCPATH . ltrim($row['content_value'], '/');
                    if (file_exists($filePath)) @unlink($filePath);
                }
                $model->delete($id);
                \Config\Services::cache()->delete('cms_content_' . $row['content_key']);
            }
        }

        // 3. Proses New Keys
        foreach ($newKeys as $idx => $nk) {
            $val = $nk['value'];
            if ($val === '[NEW_FILE]') {
                $file = $this->request->getFile('new_file_' . $idx);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/cms/', $newName);
                    $val = '/uploads/cms/' . $newName;
                } else {
                    $val = '';
                }
            }

            // Check if key already exists
            $existing = $model->where('content_key', $nk['key'])->first();
            if ($existing) {
                $model->update($existing['id'], ['content_value' => $val, 'content_type' => $nk['type']]);
            } else {
                $model->insert([
                    'content_key' => $nk['key'],
                    'content_value' => $val,
                    'content_type' => $nk['type']
                ]);
            }
            \Config\Services::cache()->delete('cms_content_' . $nk['key']);
        }

        ActivityLogger::log('ADMIN_CMS', "Melakukan Bulk Update CMS", session()->get('user_id'));

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Perubahan CMS berhasil disimpan.'
        ]);
    }
}
