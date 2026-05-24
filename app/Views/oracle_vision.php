<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>The Oracle's Vision - Firasat Analytics</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/oracle_vision.css">
</head>
<body>

    <a href="/fitur" class="btn-back-vault">
        <i class="fa-solid fa-chevron-left"></i> <?= cms_text('oracle_btn_exit', 'Exit Vision') ?>
    </a>

    <button class="btn-time-capsule" id="btnToggleCapsule">
        <i class="fa-solid fa-hourglass-half"></i> <?= cms_text('oracle_btn_capsule', 'Pesan Masa Depan') ?>
    </button>

    <div class="time-capsule-panel" id="capsulePanel">
        <div class="capsule-title"><?= cms_text('oracle_capsule_title_write', 'Tulis Pesan Masa Depan') ?></div>
        <form action="/oracle/store" method="POST" class="capsule-form">
            <?= csrf_field() ?>
            <textarea name="vision_text" rows="4" placeholder="<?= cms_raw('oracle_capsule_placeholder', 'Tuliskan visi atau pesan rahasia untuk diri Anda di masa depan...') ?>" required></textarea>
            <label style="font-size:0.8rem; color:#888; margin-bottom:5px; display:block;"><?= cms_text('oracle_capsule_label_date', 'Tanggal Dibuka:') ?></label>
            <input type="date" name="unlock_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            <button type="submit" class="capsule-btn"><?= cms_text('oracle_capsule_btn_seal', 'SEGEL PESAN') ?></button>
        </form>

        <div class="capsule-title" style="margin-top: 20px;"><?= cms_text('oracle_capsule_title_archive', 'Arsip Pesan Anda') ?></div>
        <?php if(!empty($visions)): ?>
            <?php foreach($visions as $v): ?>
                <div class="capsule-card <?= $v['is_unlocked'] ? 'unlocked' : '' ?>">
                    <div style="font-size:0.8rem; color:var(--oracle-gold); margin-bottom:10px;">
                        <i class="fa-solid fa-lock<?= $v['is_unlocked'] ? '-open' : '' ?>"></i> 
                        <?= cms_text('oracle_capsule_scheduled', 'Terjadwal:') ?> <?= date('d M Y', strtotime($v['unlock_date'])) ?>
                    </div>
                    
                    <?php if($v['is_unlocked']): ?>
                        <div style="font-family:'Courier New', monospace; font-size:0.9rem; color:#fff; white-space:pre-wrap;"><?= esc($v['vision_text']) ?></div>
                    <?php else: ?>
                        <?php if(strtotime($v['unlock_date']) <= strtotime(date('Y-m-d'))): ?>
                            <form action="/oracle/unlock/<?= $v['id'] ?>" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" style="background:transparent; border:1px solid #d4af37; color:#d4af37; padding:5px 10px; cursor:pointer; font-size:0.8rem; border-radius:3px;"><?= cms_text('oracle_capsule_btn_open', 'BUKA SEGEL') ?></button>
                            </form>
                        <?php else: ?>
                            <div style="font-size:0.8rem; color:#888;"><?= cms_text('oracle_capsule_wait', 'Segel Waktu Aktif. Menunggu takdir.') ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="font-size:0.8rem; color:#777; font-style:italic;"><?= cms_text('oracle_capsule_empty', 'Belum ada pesan yang tersegel.') ?></div>
        <?php endif; ?>
    </div>

    <!-- Modal Vision Terbuka -->
    <?php if(session()->getFlashdata('unlocked_vision')): ?>
    <div id="unsealModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; justify-content:center; align-items:center; flex-direction:column; padding:20px; backdrop-filter:blur(5px);">
        <div class="capsule-card" style="border-color:var(--oracle-gold); max-width:600px; width:100%; background:var(--oracle-dark);" id="unsealBox">
            <h2 style="color:var(--oracle-gold); font-family:'Playfair Display'; margin-bottom:20px; text-shadow:0 0 15px rgba(212,175,55,0.5); text-transform:uppercase;"><i class="fa-solid fa-envelope-open-text"></i> <?= cms_text('oracle_modal_title', 'PESAN MASA DEPAN TERBUKA') ?></h2>
            <div style="font-family:'Courier New', monospace; font-size:1rem; line-height:1.6; color:#fff; word-break:break-word; background:rgba(0,0,0,0.5); padding:20px; border-radius:5px; border:1px dashed rgba(212,175,55,0.3);">
                <?= nl2br(esc(session()->getFlashdata('unlocked_vision'))) ?>
            </div>
            <div style="margin-top:30px; text-align:right;">
                <button onclick="closeUnsealModal()" style="background:transparent; color:var(--oracle-gold); border:1px solid var(--oracle-gold); padding:10px 25px; font-weight:bold; cursor:pointer; font-family:'Courier New'; letter-spacing:2px; transition:0.3s;" onmouseover="this.style.background='rgba(212,175,55,0.1)'" onmouseout="this.style.background='transparent'"><?= cms_text('oracle_modal_close', 'TUTUP DOKUMEN') ?></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="oracle-wrapper">
        <div class="vision-container" id="visionContainer">
            <div class="permission-notice" id="permNotice"><?= cms_text('oracle_notice_camera', 'Menunggu Izin Kamera...') ?></div>
            <video id="videoFeed" autoplay playsinline></video>
            <canvas id="captureCanvas"></canvas>
            
            <img src="/images/logo-utuh.png" class="logo-reticle" id="logoReticle" alt="Scanner Reticle">
            <div class="scanner-laser" id="scannerLaser"></div>
            
            <!-- Islamic HUD Element -->
            <div style="position:absolute; top:20px; left:20px; color:rgba(212,175,55,0.7); font-family:serif; font-size:1.2rem; text-shadow: 0 0 10px rgba(212,175,55,0.5); z-index:3; direction:rtl; display:none;" id="arabicHud">
                وُجُوهٌ يَوْمَئِذٍ مُّسْفِرَةٌ
            </div>
            
            <div class="hud-text" id="hudText"><?= cms_text('oracle_hud_standby', 'SYSTEM STANDBY') ?></div>
            
            <!-- The Result Overlay -->
            <div class="result-overlay" id="resultOverlay">
                <h2 class="aura-title" id="auraTitle">The Tycoon</h2>
                <div class="aura-desc" id="auraDesc">Resonansi Dominan. Kepemimpinan Absolut.</div>
                <img src="/images/logo-utuh.png" class="watermark-logo">
            </div>
        </div>

        <div class="controls-panel">
            <button class="btn-initiate hover-trigger" id="btnScan" disabled>
                <i class="fa-solid fa-eye"></i> <?= cms_text('oracle_btn_scan', 'Mulai Pemindaian') ?>
            </button>
            <button class="btn-reset hover-trigger" id="btnReset"><?= cms_text('oracle_btn_reset', 'Pindai Ulang') ?></button>
        </div>
    </div>

    <script src="/vendor/gsap/gsap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    <script>
        window.userEntityId = <?= esc($user_id) ?>;
    </script>
    <script src="/assets/js/oracle_vision.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Flashdata handling
        <?php if(session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
            document.getElementById('capsulePanel').classList.add('open');
            <?php if(session()->getFlashdata('unlocked_vision')): ?>
                // Tampilkan custom modal dengan GSAP animation
                setTimeout(() => {
                    const modal = document.getElementById('unsealModal');
                    if (modal) {
                        modal.style.display = 'flex';
                        gsap.from("#unsealBox", { scale:0.8, opacity:0, duration:0.5, ease:"back.out(1.7)" });
                    }
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
</body>
</html>
