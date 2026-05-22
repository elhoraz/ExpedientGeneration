<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Amanah & Wasiat - The Legacy Vault
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --vault-steel: #0a0b0a;
        --bg-dark: #010201;
        --danger-red: #8b0000;
        --neon-red: #ff3333;
        --neon-green: #00ff88;
    }

        background-color: var(--bg-dark);
        font-family: 'Courier New', monospace;
        color: #fff;
        min-height: 100vh;
    }

    .vault-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
        z-index: 10;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* HEADER */
    .vault-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
        border-bottom: 1px solid rgba(212,175,55,0.2);
        padding-bottom: 20px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
        transition: all 0.4s;
        font-family: 'Inter', sans-serif;
    }
    .btn-back:hover {
        color: #fff;
        text-shadow: 0 0 10px var(--gold-main);
        transform: translateX(-5px);
    }

    .header-titles {
        text-align: right;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--danger-red);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 5px;
        text-shadow: 0 0 20px rgba(139,0,0,0.8);
    }
    .status-badge {
        font-size: 0.7rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--neon-red);
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .blink-dot {
        width: 8px; height: 8px; background: var(--neon-red); border-radius: 50%;
        animation: blink 1.5s infinite;
    }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.2; } }

    /* CONTENT GRID */
    .wasiat-list {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    /* LOCKBOX CARD */
    .lockbox {
        background: var(--vault-steel);
        border: 2px solid #1a1a1a;
        border-left: 4px solid var(--danger-red);
        padding: 30px;
        position: relative;
        box-shadow: inset 0 0 50px rgba(0,0,0,0.8), 0 10px 30px rgba(0,0,0,0.9);
        overflow: hidden;
    }
    .lockbox::before {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.02) 45%, transparent 50%);
        pointer-events: none;
    }

    .lockbox-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
        padding-bottom: 15px;
    }

    .box-id {
        color: var(--gold-dark);
        font-size: 0.8rem;
        letter-spacing: 3px;
        margin-bottom: 5px;
    }
    .box-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #fff;
        margin: 0;
    }
    .box-meta {
        text-align: right;
        font-size: 0.7rem;
        color: var(--text-muted);
        letter-spacing: 1px;
    }

    /* SECRET TEXT AREA */
    .secret-content {
        background: #000;
        padding: 20px;
        border: 1px solid #222;
        border-radius: 4px;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #555; /* Dim color for encrypted state */
        position: relative;
        min-height: 100px;
        margin-bottom: 20px;
    }

    .scramble-text {
        word-break: break-all;
        filter: blur(1px);
        transition: all 0.5s;
    }

    /* UNLOCK BUTTON */
    .btn-unlock {
        background: transparent;
        color: var(--danger-red);
        border: 1px solid var(--danger-red);
        padding: 10px 25px;
        font-size: 0.8rem;
        font-family: 'Courier New', monospace;
        letter-spacing: 3px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-unlock:hover {
        background: rgba(139,0,0,0.1);
        box-shadow: 0 0 15px rgba(139,0,0,0.3);
    }

    /* SCAN OVERLAY (Full Screen) */
    .scan-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95);
        z-index: 100;
        display: none; /* hidden by default */
        flex-direction: column;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(10px);
    }

    .fingerprint-scanner {
        width: 150px; height: 150px;
        border: 2px solid var(--danger-red);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        display: flex; justify-content: center; align-items: center;
        box-shadow: 0 0 50px rgba(139,0,0,0.2);
    }
    .fingerprint-icon {
        font-size: 5rem;
        color: rgba(139,0,0,0.3);
        transition: color 0.5s;
    }
    .scan-line {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 5px;
        background: var(--danger-red);
        box-shadow: 0 0 20px var(--danger-red);
        opacity: 0;
    }

    .scan-text {
        margin-top: 30px;
        color: var(--danger-red);
        font-size: 1rem;
        letter-spacing: 5px;
        text-transform: uppercase;
    }

    /* UNLOCKED STATE */
    .lockbox.unlocked {
        border-color: var(--neon-green);
        border-left-color: var(--neon-green);
    }
    .lockbox.unlocked .scramble-text {
        filter: blur(0);
        color: #fff;
        font-family: 'Inter', sans-serif;
    }
    .lockbox.unlocked .btn-unlock {
        display: none;
    }
    .lockbox.unlocked .box-id {
        color: var(--neon-green);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .vault-header {
            flex-direction: column-reverse;
            text-align: center;
            gap: 15px;
        }
        .header-titles { text-align: center; }
        .page-title { font-size: 1.5rem; letter-spacing: 3px; }
        .lockbox { padding: 20px; }
        .lockbox-header {
            flex-direction: column;
            gap: 10px;
        }
        .box-meta { text-align: left; }
        .secret-content { word-break: break-word; }
        form[style*="display:flex"] {
            flex-direction: column !important;
        }
        form[style*="display:flex"] input {
            width: 100% !important;
        }
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="vault-wrapper">
    <header class="vault-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> Kembali
        </a>
        <div class="header-titles">
            <h1 class="page-title">Amanah & Wasiat</h1>
            <div class="status-badge"><div class="blink-dot"></div> Ruang Rahasia</div>
        </div>
    </header>

    <div class="wasiat-list">
        
        <!-- Form Tambah Amanah Baru -->
        <div class="lockbox" style="border-left-color: var(--gold-main); border-color: rgba(212,175,55,0.3);">
            <div class="lockbox-header" style="border-bottom:none; margin-bottom:0; padding-bottom:0;">
                <div>
                    <h2 class="box-title" style="color:var(--gold-main); font-size:1.2rem;">+ Segel Amanah Baru</h2>
                </div>
            </div>
            <form action="/wasiat/store" method="POST" style="margin-top: 15px;">
                <?= csrf_field() ?>
                <textarea name="message" rows="3" required placeholder="Tulis pesan rahasia yang akan dienkripsi..." style="width:100%; background:rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1); color:#fff; padding:10px; font-family:'Courier New'; margin-bottom:10px; border-radius:4px;"></textarea>
                <div style="display:flex; gap:10px;">
                    <input type="password" name="passphrase" required placeholder="Kunci Akses (Passphrase)" style="flex:1; background:rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1); color:#fff; padding:10px; font-family:'Courier New'; border-radius:4px;">
                    <button type="submit" class="btn-unlock" style="border-color:var(--gold-main); color:var(--gold-main);"><i class="fa-solid fa-lock"></i> SEGEL</button>
                </div>
            </form>
        </div>

        <?php if(!empty($wasiats)): ?>
            <?php foreach($wasiats as $w): ?>
                <div class="lockbox" id="box-<?= $w['id'] ?>">
                    <div class="lockbox-header">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="<?= base_url('uploads/profiles/' . ($w['foto_profil'] ?: 'default.webp')) ?>" alt="Foto" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid var(--gold-dark);">
                            <div>
                                <div class="box-id">FILE.ID: AMN-<?= str_pad($w['id'], 4, '0', STR_PAD_LEFT) ?></div>
                                <h2 class="box-title">Amanah dari <?= esc($w['nama_panggilan']) ?></h2>
                            </div>
                        </div>
                        <div class="box-meta">
                            <div>ENCRYPTED: AES-256-CBC</div>
                            <div>DATE: <?= date('d M Y', strtotime($w['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="secret-content">
                        <div class="scramble-text" style="word-break: break-all; opacity: 0.5;">
                            <?= esc(substr($w['encrypted_message'], 0, 150)) ?>...
                        </div>
                    </div>
                    
                    <form action="/wasiat/unlock/<?= $w['id'] ?>" method="POST" style="display:flex; gap:10px; align-items:center;">
                        <?= csrf_field() ?>
                        <input type="password" name="passphrase" required placeholder="Masukkan Kunci Akses..." style="background:transparent; border:none; border-bottom:1px dashed var(--danger-red); color:var(--danger-red); padding:5px; font-family:'Courier New'; outline:none; width:200px;">
                        <button type="submit" class="btn-unlock">
                            <i class="fa-solid fa-key"></i> Buka Segel
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; padding:50px; color:#555; border:1px dashed #333;">Belum ada amanah yang tersegel di ruang ini.</div>
        <?php endif; ?>

        <?php if(isset($pager)): ?>
        <div style="display:flex; justify-content:center; gap:10px; margin-top:40px; padding-bottom:40px;">
            <?= $pager->links('default', 'default_full') ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Scan Overlay -->
<div class="scan-overlay" id="scanOverlay">
    <div class="fingerprint-scanner">
        <i class="fa-solid fa-fingerprint fingerprint-icon" id="fpIcon"></i>
        <div class="scan-line" id="scanLine"></div>
    </div>
    <div class="scan-text" id="scanText">Meminta Akses Biometrik...</div>
</div>

<!-- Modal Wasiat Terbuka -->
<?php if(session()->getFlashdata('unlocked_wasiat')): ?>
<div id="unsealModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; justify-content:center; align-items:center; flex-direction:column; padding:20px; backdrop-filter:blur(5px);">
    <div class="lockbox" style="border-color:var(--neon-green); border-left-color:var(--neon-green); max-width:600px; width:100%;" id="unsealBox">
        <h2 style="color:var(--neon-green); font-family:'Playfair Display'; margin-bottom:20px; text-shadow:0 0 15px rgba(0,255,136,0.5);"><i class="fa-solid fa-envelope-open-text"></i> AMANAH TERBUKA</h2>
        <div style="font-family:'Inter', sans-serif; font-size:1rem; line-height:1.6; color:#fff; word-break:break-word; background:rgba(0,0,0,0.5); padding:20px; border-radius:5px; border:1px dashed rgba(0,255,136,0.3);">
            <?= nl2br(esc(session()->getFlashdata('unlocked_wasiat'))) ?>
        </div>
        <div style="margin-top:30px; text-align:right;">
            <button onclick="closeUnsealModal()" style="background:transparent; color:var(--neon-green); border:1px solid var(--neon-green); padding:10px 25px; font-weight:bold; cursor:pointer; font-family:'Courier New'; letter-spacing:2px; transition:0.3s;" onmouseover="this.style.background='rgba(0,255,136,0.1)'" onmouseout="this.style.background='transparent'">TUTUP DOKUMEN</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js" defer></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro
    gsap.from(".lockbox", { y: 50, opacity: 0, duration: 0.8, stagger: 0.1, ease: "power2.out" });

    // Flashdata Success/Error Handling
    <?php if(session()->getFlashdata('success')): ?>
        // The template's toast will show the success message automatically.
        <?php if(session()->getFlashdata('unlocked_wasiat')): ?>
            // Tampilkan custom modal dengan GSAP animation
            setTimeout(() => {
                const modal = document.getElementById('unsealModal');
                modal.style.display = 'flex';
                gsap.from("#unsealBox", { scale:0.8, opacity:0, duration:0.5, ease:"back.out(1.7)" });
            }, 500);

            window.closeUnsealModal = function() {
                gsap.to("#unsealBox", { scale:0.8, opacity:0, duration:0.3, onComplete:() => {
                    document.getElementById('unsealModal').style.display = 'none';
                }});
            };
        <?php endif; ?>
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
