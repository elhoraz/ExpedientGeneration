<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Kotak Masuk
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .inbox-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .inbox-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .inbox-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 10px;
    }

    .chat-item {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
        transition: 0.3s;
        text-decoration: none;
        color: var(--text-primary);
    }
    .chat-item:hover {
        transform: translateY(-5px);
        border-color: #d4af37;
        box-shadow: 0 10px 30px rgba(212,175,55,0.1);
    }

    .chat-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #d4af37;
    }

    .chat-info {
        flex: 1;
    }
    .chat-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        color: #d4af37;
        margin-bottom: 5px;
    }
    .chat-preview {
        font-size: 0.9rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 400px;
    }
    .chat-time {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: monospace;
    }

    .lounge-card {
        background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(255,255,255,0.02) 100%);
        border: 1px solid rgba(212,175,55,0.5);
    }
    .lounge-card .chat-name {
        color: #fff;
        text-shadow: 0 0 10px rgba(212,175,55,0.5);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="inbox-wrapper">
    <div class="inbox-header reveal-up">
        <h1 class="inbox-title">Kotak Masuk</h1>
        <p style="color: var(--text-secondary); letter-spacing: 2px;">SALURAN KOMUNIKASI EKSKLUSIF</p>
    </div>

    <!-- Akses ke The Lounge -->
    <a href="/chat/lounge" class="chat-item lounge-card reveal-up">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #d4af37; color: #000; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-martini-glass"></i>
        </div>
        <div class="chat-info">
            <div class="chat-name">The Lounge</div>
            <div class="chat-preview">Ruang Diskusi Eksekutif Angkatan</div>
        </div>
        <div class="chat-time"><i class="fa-solid fa-chevron-right"></i></div>
    </a>

    <!-- Daftar Personal Chat -->
    <?php if(!empty($inbox)): ?>
        <?php foreach($inbox as $msg): ?>
            <a href="/chat/personal/<?= $msg['partner_id'] ?>" class="chat-item reveal-up">
                <?php $foto = $msg['foto_profil'] ?? 'default.webp'; ?>
                <img src="<?= $foto !== 'default.webp' ? '/uploads/profiles/'.$foto : 'https://ui-avatars.com/api/?name='.urlencode($msg['nama_panggilan'] ?: $msg['nama_lengkap']).'&background=d4af37&color=000' ?>" class="chat-avatar" alt="Avatar">
                
                <div class="chat-info">
                    <div class="chat-name"><?= esc($msg['nama_panggilan'] ?: $msg['nama_lengkap']) ?></div>
                    <div class="chat-preview"><?= esc($msg['last_message']) ?></div>
                </div>
                
                <div class="chat-time">
                    <?= date('d M H:i', strtotime($msg['last_message_time'])) ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; color: var(--text-secondary); border: 1px dashed var(--glass-border); border-radius: 16px; margin-top: 20px;">
            Belum ada riwayat pesan personal. Kunjungi Direktori atau The Nexus untuk mulai terhubung.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
