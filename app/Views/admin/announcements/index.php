<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .admin-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .admin-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3vw, 2.5rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
    }
    .btn-create {
        background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(212,175,55,0.05));
        border: 1px solid rgba(212,175,55,0.5);
        color: #d4af37;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.8rem;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-create:hover {
        background: #d4af37;
        color: #030504;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(212,175,55,0.3);
    }

    .announcement-table {
        width: 100%;
        border-collapse: collapse;
    }
    .announcement-table th {
        text-align: left;
        padding: 15px 20px;
        color: #d4af37;
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(212,175,55,0.3);
    }
    .announcement-table td {
        padding: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        vertical-align: middle;
    }
    .announcement-table tr {
        transition: 0.3s;
    }
    .announcement-table tr:hover {
        background: rgba(212,175,55,0.05);
    }

    .ann-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        color: var(--text-primary);
        margin-bottom: 5px;
    }
    .ann-category {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .cat-berita { background: rgba(0,200,150,0.15); color: #00c896; border: 1px solid rgba(0,200,150,0.3); }
    .cat-pengumuman { background: rgba(212,175,55,0.15); color: #d4af37; border: 1px solid rgba(212,175,55,0.3); }

    .ann-date {
        color: var(--text-secondary);
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
    }
    .ann-pinned {
        color: #d4af37;
        font-size: 1rem;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        letter-spacing: 1px;
        transition: 0.3s;
        display: inline-block;
        border: none;
        cursor: pointer;
    }
    .btn-edit {
        background: rgba(0,150,255,0.1);
        color: #0096ff;
        border: 1px solid rgba(0,150,255,0.3);
    }
    .btn-edit:hover { background: rgba(0,150,255,0.25); }
    .btn-delete {
        background: rgba(255,80,80,0.1);
        color: #ff5050;
        border: 1px solid rgba(255,80,80,0.3);
    }
    .btn-delete:hover { background: rgba(255,80,80,0.25); }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }
    .empty-state i {
        font-size: 3rem;
        color: rgba(212,175,55,0.3);
        margin-bottom: 20px;
    }

    .table-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .announcement-table th:nth-child(3),
        .announcement-table td:nth-child(3),
        .announcement-table th:nth-child(4),
        .announcement-table td:nth-child(4) { display: none; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-wrapper">
    <div class="admin-header">
        <h1 class="admin-title">Kelola Pengumuman</h1>
        <a href="/admin/announcements/create" class="btn-create hover-trigger">
            <i class="fa-solid fa-plus"></i> Buat Baru
        </a>
    </div>

    <div class="table-panel">
        <?php if (empty($announcements)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-bullhorn"></i>
                <p>Belum ada pengumuman. Buat pengumuman pertama!</p>
            </div>
        <?php else: ?>
            <table class="announcement-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Pin</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($announcements as $a): ?>
                    <tr>
                        <td><div class="ann-title"><?= esc($a['title']) ?></div></td>
                        <td>
                            <span class="ann-category cat-<?= esc($a['category']) ?>">
                                <?= esc($a['category']) ?>
                            </span>
                        </td>
                        <td class="ann-date"><?= date('d M Y', strtotime($a['published_at'])) ?></td>
                        <td>
                            <?php if ($a['is_pinned']): ?>
                                <i class="fa-solid fa-thumbtack ann-pinned"></i>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:right; display:flex; gap:8px; justify-content:flex-end;">
                            <a href="/admin/announcements/edit/<?= $a['id'] ?>" class="btn-action btn-edit hover-trigger">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form action="/admin/announcements/delete/<?= $a['id'] ?>" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-action btn-delete hover-trigger">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
