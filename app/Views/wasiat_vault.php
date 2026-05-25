<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Amanah & Wasiat - The Legacy Vault
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #f9f5e8;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --bg-dark: #050505;
        --card-bg: rgba(20, 20, 22, 0.85);
        --text-muted: #8b9ba8;
        --wax-red: #9e1b1b;
    }

    body {
        background-color: var(--bg-dark);
        font-family: 'Inter', sans-serif;
        color: #fff;
        min-height: 100vh;
        background-image: url('https://images.unsplash.com/photo-1614064641913-a520f596a247?q=80&w=2574&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .vault-wrapper {
        position: relative;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 20px;
        z-index: 10;
        min-height: 100vh;
        backdrop-filter: blur(15px);
        background: rgba(5, 5, 5, 0.7);
        box-shadow: 0 0 50px rgba(0,0,0,0.8);
    }

    /* HEADER */
    .vault-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
        border-bottom: 1px solid rgba(212,175,55,0.3);
        padding-bottom: 20px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 500;
        transition: all 0.4s;
    }
    .btn-back:hover {
        color: #fff;
        transform: translateX(-5px);
    }

    .header-titles {
        text-align: right;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--gold-main);
        margin: 0;
        letter-spacing: 3px;
        text-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }
    .status-badge {
        font-size: 0.8rem;
        letter-spacing: 2px;
        color: var(--text-muted);
        margin-top: 5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* CONTENT GRID */
    .wasiat-list {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    /* LEGACY CARD (Replaces Lockbox) */
    .legacy-card {
        background: var(--card-bg);
        border: 1px solid rgba(212,175,55,0.2);
        border-radius: 8px;
        padding: 40px;
        position: relative;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .legacy-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.7);
        border-color: rgba(212,175,55,0.4);
    }

    .legacy-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 5px;
        background: linear-gradient(90deg, transparent, var(--gold-main), transparent);
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        opacity: 0.5;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .author-avatar {
        width: 50px; height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gold-dark);
        box-shadow: 0 0 15px rgba(212,175,55,0.2);
    }
    .author-meta .box-id {
        color: var(--gold-dark);
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .author-meta .box-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--gold-light);
        margin: 0;
    }

    .document-meta {
        text-align: right;
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* SECRET CONTENT */
    .secret-content {
        background: rgba(0,0,0,0.3);
        padding: 30px;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 6px;
        font-size: 1rem;
        line-height: 1.8;
        color: var(--text-muted);
        position: relative;
        min-height: 120px;
        margin-bottom: 25px;
        font-family: 'Playfair Display', serif;
        font-style: italic;
    }

    .scramble-text {
        filter: blur(4px);
        opacity: 0.6;
        user-select: none;
        transition: all 0.5s;
    }

    /* SEAL / BUTTONS */
    .seal-btn {
        background: transparent;
        color: var(--gold-main);
        border: 1px solid var(--gold-main);
        padding: 12px 30px;
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border-radius: 30px;
        font-family: 'Inter', sans-serif;
    }
    .seal-btn:hover {
        background: rgba(212,175,55,0.1);
        box-shadow: 0 0 20px rgba(212,175,55,0.2);
    }

    .form-input {
        background: rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        padding: 15px;
        font-family: 'Inter', sans-serif;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.3s;
    }
    .form-input:focus {
        border-color: var(--gold-main);
    }
    
    .wax-seal-icon {
        color: var(--wax-red);
        font-size: 1.2rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
    }

    /* OVERLAY & MODAL */
    .elegant-modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 20px;
        backdrop-filter: blur(8px);
    }
    
    .modal-paper {
        background: #fdfbf7;
        color: #1a1a1a;
        max-width: 650px;
        width: 100%;
        padding: 50px;
        border-radius: 4px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        position: relative;
    }
    .modal-paper::before {
        content: ''; position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px;
        border: 1px solid rgba(212,175,55,0.3); pointer-events: none;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .vault-header {
            flex-direction: column-reverse;
            text-align: center;
            gap: 15px;
        }
        .header-titles { text-align: center; }
        .page-title { font-size: 1.8rem; }
        .legacy-card { padding: 25px; }
        .card-header {
            flex-direction: column;
            gap: 15px;
        }
        .document-meta { text-align: left; }
        .form-row {
            flex-direction: column !important;
        }
        .form-row .form-input {
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="vault-wrapper">
    <header class="vault-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-arrow-left-long"></i> <?= cms_text('wasiat_btn_back', 'Kembali ke Vault') ?>
        </a>
        <div class="header-titles">
            <h1 class="page-title"><?= cms_text('wasiat_title', 'Amanah & Wasiat') ?></h1>
            <div class="status-badge"><i class="fa-solid fa-feather-pointed"></i> <?= cms_text('wasiat_badge', 'Arsip Personal Tertutup') ?></div>
        </div>
    </header>

    <div class="wasiat-list">
        
        <!-- Form Tambah Amanah Baru -->
        <div class="legacy-card" style="border-color: rgba(212,175,55,0.5);">
            <div class="card-header" style="border-bottom:none; margin-bottom:0; padding-bottom:0;">
                <div class="author-meta">
                    <h2 class="box-title" style="color:var(--gold-main); font-size:1.4rem;"><?= cms_text('wasiat_box_new_title', 'Buat Dokumen Segel Baru') ?></h2>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-top:10px;"><?= cms_text('wasiat_box_new_desc', 'Tuliskan amanah yang hanya dapat dibaca oleh mereka yang memegang kunci otorisasinya.') ?></p>
                </div>
            </div>
            <form action="/wasiat/store" method="POST" style="margin-top: 25px;">
                <?= csrf_field() ?>
                <textarea name="message" rows="4" class="form-input" required placeholder="<?= cms_raw('wasiat_ph_message', 'Tulis pesan rahasia yang akan disegel...') ?>" style="width:100%; margin-bottom:20px;"></textarea>
                <div class="form-row" style="display:flex; gap:15px; align-items:center;">
                    <input type="password" name="passphrase" class="form-input" required placeholder="<?= cms_raw('wasiat_ph_pass', 'Kunci Akses (Passphrase)') ?>" style="flex:1;">
                    <button type="submit" class="seal-btn"><i class="fa-solid fa-stamp wax-seal-icon"></i> <?= cms_text('wasiat_btn_seal', 'SEGEL DOKUMEN') ?></button>
                </div>
            </form>
        </div>

        <?php if(!empty($wasiats)): ?>
            <?php foreach($wasiats as $w): ?>
                <div class="legacy-card" id="doc-<?= $w['id'] ?>">
                    <div class="card-header">
                        <div class="author-info">
                            <img src="<?= base_url('uploads/profiles/' . ($w['foto_profil'] ?: 'default.webp')) ?>" alt="Foto" class="author-avatar">
                            <div class="author-meta">
                                <div class="box-id">Diarsipkan pada: <?= date('d M Y', strtotime($w['created_at'])) ?></div>
                                <h2 class="box-title"><?= cms_text('wasiat_box_title_prefix', 'Amanah dari') ?> <?= esc($w['nama_panggilan']) ?></h2>
                            </div>
                        </div>
                        <div class="document-meta">
                            <div><i class="fa-solid fa-lock" style="color:var(--gold-main); font-size:0.8rem;"></i> Terenkripsi Penuh</div>
                            <div style="margin-top:5px;">Akses Tertutup</div>
                        </div>
                    </div>
                    <div class="secret-content">
                        <div class="scramble-text">
                            Amanah ini dalam keadaan tertutup rapat. Hanya otoritas atau pewaris yang memiliki kunci persetujuan yang dapat membaca isi pesan yang terkandung di dalamnya. Menjaga kerahasiaan...
                        </div>
                    </div>
                    
                    <form action="/wasiat/unlock/<?= $w['id'] ?>" method="POST" class="form-row" style="display:flex; gap:15px; align-items:center;">
                        <?= csrf_field() ?>
                        <input type="password" name="passphrase" class="form-input" required placeholder="<?= cms_raw('wasiat_ph_unlock', 'Masukkan Kunci Akses...') ?>" style="width:250px;">
                        <button type="submit" class="seal-btn" style="border-color: rgba(255,255,255,0.2); color: #fff;">
                            <i class="fa-solid fa-key" style="color:var(--gold-main);"></i> <?= cms_text('wasiat_btn_open', 'Buka Dokumen') ?>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; padding:60px; color:var(--text-muted); border:1px dashed rgba(255,255,255,0.1); border-radius:8px;">
                <i class="fa-solid fa-box-archive" style="font-size:3rem; margin-bottom:20px; opacity:0.5;"></i><br>
                <?= cms_text('wasiat_empty', 'Belum ada amanah yang diarsipkan di ruang ini.') ?>
            </div>
        <?php endif; ?>

        <?php if(isset($pager)): ?>
        <div style="display:flex; justify-content:center; gap:10px; margin-top:40px; padding-bottom:40px;">
            <?= $pager->links('default', 'default_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Wasiat Terbuka -->
<?php if(session()->getFlashdata('unlocked_wasiat')): ?>
<div class="elegant-modal" id="unsealModal">
    <div class="modal-paper" id="unsealPaper">
        <div style="text-align:center; margin-bottom:30px;">
            <i class="fa-solid fa-stamp" style="color:var(--wax-red); font-size:3rem; margin-bottom:15px;"></i>
            <h2 style="font-family:'Playfair Display', serif; font-size:2rem; margin:0;"><?= cms_text('wasiat_modal_title', 'Amanah Terbuka') ?></h2>
            <div style="width:50px; height:2px; background:var(--gold-main); margin:15px auto;"></div>
        </div>
        
        <div style="font-family:'Playfair Display', serif; font-size:1.1rem; line-height:1.8; word-break:break-word;">
            <?= nl2br(esc(session()->getFlashdata('unlocked_wasiat'))) ?>
        </div>
        
        <div style="margin-top:50px; text-align:center; border-top:1px solid rgba(0,0,0,0.1); padding-top:20px;">
            <button onclick="closeUnsealModal()" style="background:transparent; color:#1a1a1a; border:1px solid #1a1a1a; padding:10px 30px; font-weight:bold; cursor:pointer; font-family:'Inter', sans-serif; letter-spacing:1px; transition:0.3s; border-radius:30px;" onmouseover="this.style.background='#1a1a1a'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#1a1a1a';"><?= cms_text('wasiat_btn_close', 'Tutup Kembali') ?></button>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js" defer></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Elegant Intro Animation
    gsap.from(".legacy-card", { y: 40, opacity: 0, duration: 1, stagger: 0.15, ease: "power3.out" });

    // Modal Handling
    <?php if(session()->getFlashdata('unlocked_wasiat')): ?>
        setTimeout(() => {
            const modal = document.getElementById('unsealModal');
            modal.style.display = 'flex';
            gsap.from("#unsealPaper", { y: 50, rotationX: -10, opacity: 0, duration: 0.8, ease: "power3.out" });
        }, 300);

        window.closeUnsealModal = function() {
            gsap.to("#unsealPaper", { y: -30, opacity: 0, duration: 0.5, ease: "power2.in", onComplete:() => {
                document.getElementById('unsealModal').style.display = 'none';
            }});
        };
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
