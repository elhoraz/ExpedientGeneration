<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Ulang Tahun Hari Ini
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .bday-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 900px;
        margin: 0 auto;
    }

    .bday-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .bday-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3rem);
        color: #d4af37;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .bday-header p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        letter-spacing: 2px;
    }
    .bday-date-badge {
        display: inline-block;
        background: rgba(212,175,55,0.1);
        border: 1px solid rgba(212,175,55,0.3);
        padding: 8px 24px;
        border-radius: 50px;
        color: #d4af37;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        margin-top: 15px;
        letter-spacing: 2px;
    }

    .bday-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .bday-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
        position: relative;
        overflow: hidden;
    }
    .bday-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d4af37, #ffd700, #d4af37);
    }
    .bday-card:hover {
        transform: translateY(-8px);
        border-color: rgba(212,175,55,0.5);
        box-shadow: 0 20px 50px rgba(212,175,55,0.1);
    }

    .bday-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #d4af37;
        margin-bottom: 15px;
        box-shadow: 0 0 30px rgba(212,175,55,0.2);
    }

    .bday-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: #d4af37;
        margin-bottom: 5px;
    }
    .bday-fullname {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 15px;
    }

    .bday-confetti {
        font-size: 2rem;
        margin-bottom: 10px;
        animation: confettiBounce 1.5s ease infinite;
    }
    @keyframes confettiBounce {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(10deg); }
    }

    .bday-btn {
        display: inline-block;
        padding: 10px 25px;
        background: transparent;
        border: 1px solid #d4af37;
        color: #d4af37;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: 0.3s;
        letter-spacing: 1px;
    }
    .bday-btn:hover {
        background: #d4af37;
        color: #000;
        transform: scale(1.05);
    }

    .bday-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }
    .bday-empty i {
        font-size: 4rem;
        color: rgba(212,175,55,0.2);
        margin-bottom: 20px;
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bday-wrapper">
    <div class="bday-header reveal-up">
        <h1>🎂 <?= cms_text('bday_list_title', 'Ulang Tahun Hari Ini') ?></h1>
        <p><?= cms_text('bday_list_subtitle', 'Kirim ucapan terbaik untuk kolega Anda') ?></p>
        <div class="bday-date-badge">
            <i class="fa-regular fa-calendar"></i> <?= date('d F Y') ?>
        </div>
    </div>

    <?php if(!empty($birthday_users)): ?>
        <div class="bday-grid">
            <?php foreach($birthday_users as $u): ?>
                <div class="bday-card reveal-up">
                    <div class="bday-confetti">🎉</div>
                    <?php
                        $foto = $u['foto_profil'] ?? '';
                        $avatarUrl = !empty($foto) 
                            ? '/uploads/profiles/' . $foto 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($u['nama_panggilan'] ?: $u['nama_lengkap']) . '&background=d4af37&color=000&size=200';
                    ?>
                    <img src="<?= $avatarUrl ?>" class="bday-avatar" alt="<?= esc($u['nama_panggilan']) ?>">
                    <div class="bday-name"><?= esc($u['nama_panggilan'] ?: $u['nama_lengkap']) ?></div>
                    <div class="bday-fullname"><?= esc($u['nama_lengkap']) ?></div>
                    
                    <a href="/birthday/<?= $u['id'] ?>" class="bday-btn">
                        <i class="fa-solid fa-gift" style="margin-right: 8px;"></i> <?= cms_text('bday_list_btn', 'Kirim Ucapan') ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bday-empty reveal-up">
            <i class="fa-regular fa-face-smile"></i>
            <h3 style="color: var(--text-primary); margin-bottom: 10px; font-family: 'Playfair Display', serif;"><?= cms_text('bday_list_empty_title', 'Tidak Ada Ulang Tahun Hari Ini') ?></h3>
            <p><?= cms_text('bday_list_empty_desc', 'Belum ada kolega yang berulang tahun hari ini. Kembali lagi besok!') ?></p>
            <a href="/beranda" class="bday-btn" style="margin-top: 20px;">
                <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> <?= cms_text('bday_list_btn_back', 'Kembali') ?>
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
