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

        $galleryModel = new \App\Models\BerandaGalleryModel();
        $galleries = $galleryModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'CMS Manager',
            'groupedContents' => $groupedContents,
            'galleries' => $galleries
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
    public function addGallery()
    {
        $file = $this->request->getFile('image_file');
        $caption = $this->request->getPost('caption');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Ensure directory exists
            if (!is_dir(FCPATH . 'uploads/gallery/')) {
                mkdir(FCPATH . 'uploads/gallery/', 0777, true);
            }
            $file->move(FCPATH . 'uploads/gallery/', $newName);
            
            $model = new \App\Models\BerandaGalleryModel();
            $model->insert([
                'image_url' => '/uploads/gallery/' . $newName,
                'caption'   => $caption
            ]);
            
            ActivityLogger::log('ADMIN_CMS', "Menambahkan gambar baru ke Galeri Beranda", session()->get('user_id'));
            return redirect()->to('/admin/cms')->with('success', 'Gambar berhasil ditambahkan ke Galeri.');
        }

        return redirect()->to('/admin/cms')->with('error', 'Gagal mengunggah gambar.');
    }

    public function deleteGallery($id)
    {
        $model = new \App\Models\BerandaGalleryModel();
        $item = $model->find($id);
        
        if ($item) {
            $filePath = FCPATH . ltrim($item['image_url'], '/');
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $model->delete($id);
            ActivityLogger::log('ADMIN_CMS', "Menghapus gambar dari Galeri Beranda", session()->get('user_id'));
            return redirect()->to('/admin/cms')->with('success', 'Gambar berhasil dihapus dari Galeri.');
        }
        
        return redirect()->to('/admin/cms')->with('error', 'Gambar tidak ditemukan.');
    }
}
