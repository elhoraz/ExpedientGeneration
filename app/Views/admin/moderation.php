<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Moderasi Konten
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .admin-wrapper { padding: clamp(80px, 15vh, 120px) 20px 40px; max-width: 1400px; margin: 0 auto; }
    .admin-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 30px; }
    .admin-title { font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 3vw, 2.5rem); color: #d4af37; }
    .admin-nav { display: flex; gap: 10px; flex-wrap: wrap; }
    .admin-nav a { padding: 8px 16px; border-radius: 8px; border: 1px solid var(--glass-border); color: var(--text-primary); text-decoration: none; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .admin-nav a:hover, .admin-nav a.active { background: rgba(212,175,55,0.1); border-color: #d4af37; color: #d4af37; }

    .mod-section { margin-bottom: 40px; }
    .mod-title { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--text-primary); margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; gap: 10px; }
    .mod-title i { color: #d4af37; }

    .mod-card { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 10px; margin-bottom: 8px; gap: 15px; flex-wrap: wrap; transition: 0.2s; }
    .mod-card:hover { border-color: rgba(212,175,55,0.3); }
    .mod-card.deleted { opacity: 0.4; text-decoration: line-through; }
    .mod-info { flex: 1; min-width: 200px; }
    .mod-author { font-size: 0.75rem; color: #d4af37; font-weight: bold; letter-spacing: 1px; }
    .mod-text { font-size: 0.85rem; color: var(--text-primary); margin-top: 3px; word-break: break-word; }
    .mod-date { font-size: 0.7rem; color: var(--text-secondary); margin-top: 3px; }
    .mod-badge { font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; }
    .mod-badge-open { background: rgba(0,200,100,0.1); color: #00c864; }
    .mod-badge-closed { background: rgba(255,50,50,0.1); color: #ff5555; }

    .btn-del { padding: 5px 12px; border: 1px solid rgba(255,50,50,0.2); background: rgba(255,50,50,0.1); color: #ff5555; border-radius: 5px; cursor: pointer; font-size: 0.7rem; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; flex-shrink: 0; }
    .btn-del:hover { background: rgba(255,50,50,0.3); }

    .empty-state { text-align: center; padding: 30px; color: var(--text-secondary); font-size: 0.85rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-wrapper">
    <div class="admin-header">
        <h1 class="admin-title">Moderasi Konten</h1>
        <nav class="admin-nav">
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/users">Users</a>
            <a href="/admin/moderation" class="active">Moderasi</a>
            <a href="/admin/announcements">Pengumuman</a>
        </nav>
    </div>

    <!-- CHAT MESSAGES -->
    <div class="mod-section">
        <div class="mod-title"><i class="fa-solid fa-comments"></i> Chat Terbaru (20)</div>
        <?php if(!empty($chats)): ?>
            <?php foreach($chats as $c): ?>
                <div class="mod-card <?= ($c['is_deleted'] ?? 0) ? 'deleted' : '' ?>">
                    <div class="mod-info">
                        <div class="mod-author"><?= esc($c['nama_panggilan']) ?></div>
                        <div class="mod-text"><?= esc(mb_substr($c['message'], 0, 200)) ?><?= mb_strlen($c['message']) > 200 ? '...' : '' ?></div>
                        <div class="mod-date"><?= $c['created_at'] ?></div>
                    </div>
                    <?php if(!($c['is_deleted'] ?? 0)): ?>
                    <form action="/admin/delete/chat/<?= $c['id'] ?>" method="POST" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Hapus pesan chat ini?').then(res => { if(res) this.submit(); });">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-del"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                    <?php else: ?>
                        <span style="font-size:0.7rem; color:#ff5555;">DIHAPUS</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Belum ada pesan chat.</div>
        <?php endif; ?>
    </div>

    <!-- MAJLIS TOPICS -->
    <div class="mod-section">
        <div class="mod-title"><i class="fa-solid fa-gavel"></i> Mosi Majlis (20)</div>
        <?php if(!empty($topics)): ?>
            <?php foreach($topics as $t): ?>
                <div class="mod-card">
                    <div class="mod-info">
                        <div class="mod-author"><?= esc($t['nama_panggilan']) ?></div>
                        <div class="mod-text"><?= esc($t['title']) ?></div>
                        <div class="mod-date"><?= $t['created_at'] ?> <span class="mod-badge <?= $t['status'] === 'Open' ? 'mod-badge-open' : 'mod-badge-closed' ?>"><?= esc($t['status']) ?></span></div>
                    </div>
                    <form action="/admin/delete/majlis/<?= $t['id'] ?>" method="POST" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Hapus mosi ini beserta semua suaranya?').then(res => { if(res) this.submit(); });">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-del"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Belum ada mosi.</div>
        <?php endif; ?>
    </div>

    <!-- SYNDICATE -->
    <div class="mod-section">
        <div class="mod-title"><i class="fa-solid fa-briefcase"></i> Bisnis Syndicate (20)</div>
        <?php if(!empty($bisnis)): ?>
            <?php foreach($bisnis as $b): ?>
                <div class="mod-card">
                    <div class="mod-info">
                        <div class="mod-author"><?= esc($b['nama_panggilan']) ?></div>
                        <div class="mod-text"><?= esc($b['nama_bisnis']) ?> — <span style="color:var(--text-secondary);"><?= esc($b['kategori']) ?></span></div>
                        <div class="mod-date"><?= $b['created_at'] ?></div>
                    </div>
                    <form action="/admin/delete/syndicate/<?= $b['id'] ?>" method="POST" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Hapus bisnis ini?').then(res => { if(res) this.submit(); });">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-del"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Belum ada bisnis.</div>
        <?php endif; ?>
    </div>

    <!-- BUKU TAMU -->
    <div class="mod-section">
        <div class="mod-title"><i class="fa-solid fa-book"></i> Buku Tamu (20)</div>
        <?php if(!empty($bukuTamu)): ?>
            <?php foreach($bukuTamu as $bt): ?>
                <div class="mod-card">
                    <div class="mod-info">
                        <div class="mod-author"><?= esc($bt['nama']) ?></div>
                        <div class="mod-text"><?= esc($bt['pesan']) ?></div>
                        <div class="mod-date"><?= $bt['created_at'] ?></div>
                    </div>
                    <form action="/admin/delete/bukutamu/<?= $bt['id'] ?>" method="POST" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Hapus pesan buku tamu ini?').then(res => { if(res) this.submit(); });">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-del"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Belum ada pesan buku tamu.</div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
