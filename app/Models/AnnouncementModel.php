<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table         = 'announcements';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'title', 'content', 'category', 'is_pinned',
        'published_at', 'created_by'
    ];

    /**
     * Ambil pengumuman terbaru yang sudah dipublikasikan.
     */
    public function getPublished(int $limit = 10): array
    {
        return $this->where('published_at <=', date('Y-m-d'))
                    ->orderBy('is_pinned', 'DESC')
                    ->orderBy('published_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Ambil berita untuk beranda (hanya kategori berita, max 5).
     */
    public function getBeritaBeranda(int $limit = 5): array
    {
        return $this->where('published_at <=', date('Y-m-d'))
                    ->orderBy('is_pinned', 'DESC')
                    ->orderBy('published_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
