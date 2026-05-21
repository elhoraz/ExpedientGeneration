<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Arsip Kehadiran - 42nd Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .archive-container {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 800px;
        margin: 0 auto;
        min-height: 100vh;
    }
    .archive-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .archive-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 10px;
    }
    .archive-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .message-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        border-left: 4px solid #d4af37;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 20px;
        transition: transform 0.3s, background 0.3s;
    }
    .message-card:hover {
        transform: translateX(10px);
        background: rgba(255,255,255,0.05);
    }
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border-bottom: 1px dashed rgba(212,175,55,0.3);
        padding-bottom: 10px;
    }
    .message-author {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        color: #fff;
        font-weight: bold;
    }
    .message-date {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: 'Courier New', monospace;
    }
    .message-body {
        color: var(--text-primary);
        font-size: 1rem;
        line-height: 1.6;
        font-style: italic;
    }
    .message-body::before { content: '"'; color: #d4af37; font-size: 1.2em; font-family: serif; }
    .message-body::after { content: '"'; color: #d4af37; font-size: 1.2em; font-family: serif; }

    /* Pagination Styling */
    .pagination { display: flex; list-style: none; padding: 0; justify-content: center; gap: 5px; margin-top: 40px; }
    .pagination li a, .pagination li span {
        display: inline-block; padding: 8px 12px; background: rgba(212,175,55,0.1); color: #d4af37;
        text-decoration: none; border-radius: 5px; border: 1px solid rgba(212,175,55,0.2); transition: 0.3s; font-size: 0.9rem;
    }
    .pagination li.active a, .pagination li.active span { background: #d4af37; color: #000; border-color: #d4af37; }
    .pagination li a:hover { background: rgba(212,175,55,0.2); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="archive-container">
    <div class="archive-header">
        <h1 class="archive-title">Arsip Kehadiran</h1>
        <p class="archive-subtitle">Jejak sejarah yang ditinggalkan oleh entitas Expedient</p>
    </div>

    <div style="margin-bottom: 30px;">
        <a href="/#loader" style="color:var(--text-secondary); text-decoration:none; font-size:0.9rem; transition:0.3s;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
    </div>

    <?php if(empty($buku_tamu)): ?>
        <div style="text-align:center; padding:50px; color:var(--text-secondary); font-style:italic;">Belum ada catatan kehadiran yang direkam.</div>
    <?php else: ?>
        <div class="messages-list">
            <?php foreach($buku_tamu as $msg): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div class="message-author"><i class="fa-solid fa-feather-pointed" style="color:#d4af37; margin-right:8px;"></i> <?= esc($msg['nama']) ?></div>
                        <div class="message-date"><?= date('d F Y - H:i', strtotime($msg['created_at'])) ?></div>
                    </div>
                    <div class="message-body">
                        <?= esc($msg['pesan']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pager): ?>
            <div style="display:flex; justify-content:center;">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
