<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;

class AnnouncementController extends BaseController
{
    protected AnnouncementModel $model;

    public function __construct()
    {
        $this->model = new AnnouncementModel();
    }

    /**
     * List semua pengumuman (admin).
     */
    public function index()
    {
        $data = [
            'title'         => 'Kelola Pengumuman',
            'announcements' => $this->model->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/announcements/index', $data);
    }

    /**
     * Form buat pengumuman baru.
     */
    public function create()
    {
        return view('admin/announcements/form', [
            'title'        => 'Buat Pengumuman Baru',
            'announcement' => null,
        ]);
    }

    /**
     * Simpan pengumuman baru ke DB.
     */
    public function store()
    {
        $rules = [
            'title'        => 'required|min_length[3]|max_length[255]',
            'content'      => 'required|min_length[10]',
            'category'     => 'required|in_list[berita,pengumuman]',
            'published_at' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Periksa kembali isian Anda.');
        }

        $this->model->insert([
            'title'        => $this->request->getPost('title'),
            'content'      => $this->request->getPost('content'),
            'category'     => $this->request->getPost('category'),
            'is_pinned'    => $this->request->getPost('is_pinned') ? 1 : 0,
            'published_at' => $this->request->getPost('published_at'),
            'created_by'   => session()->get('user_id'),
        ]);

        // Broadcast notification via Pusher
        $pusher = new \App\Services\PusherService();
        $pusher->broadcastNotification(
            'Pengumuman Baru',
            $this->request->getPost('title'),
            '/beranda'
        );

        // Broadcast notification via WhatsApp
        $waService = service('whatsAppService');
        $waService->notifyAnnouncement($this->request->getPost('title'));

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    /**
     * Form edit pengumuman.
     */
    public function edit($id)
    {
        $announcement = $this->model->find($id);
        if (!$announcement) {
            return redirect()->to('/admin/announcements')->with('error', 'Pengumuman tidak ditemukan.');
        }

        return view('admin/announcements/form', [
            'title'        => 'Edit Pengumuman',
            'announcement' => $announcement,
        ]);
    }

    /**
     * Update pengumuman ke DB.
     */
    public function update($id)
    {
        $rules = [
            'title'        => 'required|min_length[3]|max_length[255]',
            'content'      => 'required|min_length[10]',
            'category'     => 'required|in_list[berita,pengumuman]',
            'published_at' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $this->model->update($id, [
            'title'        => $this->request->getPost('title'),
            'content'      => $this->request->getPost('content'),
            'category'     => $this->request->getPost('category'),
            'is_pinned'    => $this->request->getPost('is_pinned') ? 1 : 0,
            'published_at' => $this->request->getPost('published_at'),
        ]);

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman.
     */
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
