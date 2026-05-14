<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Selamat Ulang Tahun, <?= esc($user['nama_panggilan']) ?>!<?= $this->endSection() ?>

<?php
// ========================================================================
// ALGORITMA 124 DESAIN UNIK — Seed berdasarkan User ID
// ========================================================================
$seed = $design_id;

// 25 Palet Warna [bg_gradient_1, bg_gradient_2, accent, text_main, text_sub]
$palettes = [
    ['#ff6b6b','#feca57','#ff9ff3','#ffffff','#ffffffcc'],
    ['#0abde3','#10ac84','#48dbfb','#ffffff','#ffffffcc'],
    ['#5f27cd','#c44569','#e056a0','#ffffff','#ffffffdd'],
    ['#ff9a76','#ffeaa7','#fdcb6e','#2d3436','#636e72'],
    ['#6c5ce7','#a29bfe','#dfe6e9','#ffffff','#ffffffcc'],
    ['#00b894','#00cec9','#55efc4','#ffffff','#ffffffcc'],
    ['#e17055','#fab1a0','#ffeaa7','#2d3436','#636e72'],
    ['#fd79a8','#e84393','#fdcb6e','#ffffff','#ffffffcc'],
    ['#636e72','#2d3436','#d4af37','#ffffff','#ffffffcc'],
    ['#0984e3','#74b9ff','#dfe6e9','#ffffff','#ffffffcc'],
    ['#d63031','#ff7675','#ffeaa7','#ffffff','#ffffffcc'],
    ['#e84393','#fd79a8','#fab1a0','#ffffff','#ffffffcc'],
    ['#00b894','#55efc4','#81ecec','#2d3436','#636e72'],
    ['#6c5ce7','#fd79a8','#ffeaa7','#ffffff','#ffffffcc'],
    ['#fdcb6e','#f39c12','#e74c3c','#2d3436','#636e72'],
    ['#1abc9c','#16a085','#2ecc71','#ffffff','#ffffffcc'],
    ['#2c3e50','#3498db','#e74c3c','#ffffff','#ffffffcc'],
    ['#8e44ad','#9b59b6','#f1c40f','#ffffff','#ffffffcc'],
    ['#e74c3c','#c0392b','#f39c12','#ffffff','#ffffffcc'],
    ['#1e3799','#0c2461','#f6b93b','#ffffff','#ffffffcc'],
    ['#b8e994','#78e08f','#38ada9','#2d3436','#636e72'],
    ['#f8c291','#e55039','#eb2f06','#ffffff','#ffffffcc'],
    ['#4a69bd','#6a89cc','#f8c291','#ffffff','#ffffffcc'],
    ['#e58e26','#fa983a','#f6b93b','#2d3436','#636e72'],
    ['#d4af37','#f5d76e','#2c3e50','#2c3e50','#34495e'],
];

// 12 Font Pairs [heading_font, body_font, heading_weight]
$fonts = [
    ['Playfair Display','Inter','900'],
    ['Poppins','Lato','800'],
    ['Montserrat','Open Sans','900'],
    ['Pacifico','Nunito','400'],
    ['Bebas Neue','Roboto','400'],
    ['Abril Fatface','Source Sans 3','400'],
    ['DM Serif Display','DM Sans','400'],
    ['Josefin Sans','Work Sans','700'],
    ['Righteous','Quicksand','400'],
    ['Lobster','Mulish','400'],
    ['Raleway','Karla','900'],
    ['Oswald','Merriweather','700'],
];

// 8 Layout classes
$layouts = ['center-stack','split-left','split-right','diagonal','frame-overlay','circle-focus','fullbleed','card-float'];

// 6 Dekorasi
$decos = ['confetti','stars','balloons','sparkles','ribbons','floral'];

// 4 Animasi
$anims = ['cascade','bounce','bloom','burst'];

$p = $palettes[$seed % 25];
$f = $fonts[$seed % 12];
$layout = $layouts[$seed % 8];
$deco = $decos[$seed % 6];
$anim = $anims[$seed % 4];

// Font import URL
$fontUrl = 'https://fonts.googleapis.com/css2?family=' . urlencode($f[0]) . ':wght@' . $f[2] . '&family=' . urlencode($f[1]) . ':wght@400;600&display=swap';
?>

<?= $this->section('styles') ?>
<link href="<?= $fontUrl ?>" rel="stylesheet">
<style>
/* ================= BIRTHDAY CARD ENGINE ================= */
.bday-universe {
    position: relative; width: 100%; min-height: 100vh;
    background: linear-gradient(135deg, <?= $p[0] ?> 0%, <?= $p[1] ?> 100%);
    display: flex; justify-content: center; align-items: center;
    overflow: hidden; padding: 40px 20px;
}

.bday-card {
    position: relative; z-index: 10; width: 100%; max-width: 580px;
    text-align: center; padding: 60px 40px;
}

/* Font Assignment */
.bday-heading { font-family: '<?= $f[0] ?>', serif; font-weight: <?= $f[2] ?>; }
.bday-body { font-family: '<?= $f[1] ?>', sans-serif; }

/* Accent Color */
.bday-accent { color: <?= $p[2] ?>; }
.bday-text { color: <?= $p[3] ?>; }
.bday-sub { color: <?= $p[4] ?>; }

/* ================= PHOTO ================= */
.bday-photo-wrap {
    width: 160px; height: 160px; border-radius: 50%; margin: 0 auto 30px;
    border: 4px solid <?= $p[2] ?>; overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.bday-photo-wrap img { width: 100%; height: 100%; object-fit: cover; }
.bday-photo-placeholder {
    width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;
    background: rgba(255,255,255,0.15); font-size: 4rem; color: <?= $p[2] ?>;
}

/* ================= TEXT ELEMENTS ================= */
.bday-pretitle {
    font-size: clamp(0.7rem, 2vw, 0.9rem); letter-spacing: 6px; text-transform: uppercase;
    margin-bottom: 15px; opacity: 0.8;
}
.bday-name {
    font-size: clamp(2.5rem, 8vw, 5rem); line-height: 1.1; margin-bottom: 10px;
    text-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.bday-age {
    font-size: clamp(1.2rem, 3vw, 1.8rem); margin-bottom: 30px; opacity: 0.9;
}
.bday-zodiak {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 20px; border-radius: 30px; font-size: 0.85rem;
    background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2); margin-bottom: 30px;
}
.bday-wishes {
    font-size: clamp(1rem, 2.5vw, 1.3rem); line-height: 1.8;
    max-width: 450px; margin: 0 auto 40px; opacity: 0.9;
}
.bday-date-badge {
    display: inline-block; padding: 12px 30px; border-radius: 50px;
    background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3); font-size: 0.85rem;
    letter-spacing: 3px; text-transform: uppercase; margin-bottom: 30px;
}
.bday-share-btn {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 35px; border-radius: 50px; border: 2px solid <?= $p[2] ?>;
    background: transparent; color: <?= $p[3] ?>; font-size: 0.85rem;
    letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
    transition: 0.4s; text-decoration: none; font-weight: 600;
}
.bday-share-btn:hover { background: <?= $p[2] ?>; color: #111; transform: translateY(-3px); }

/* ================= 8 LAYOUT VARIANTS ================= */
.layout-split-left .bday-card, .layout-split-right .bday-card { text-align: left; max-width: 700px; }
.layout-split-right .bday-card { text-align: right; }
.layout-diagonal .bday-card { transform: rotate(-2deg); }
.layout-diagonal .bday-card:hover { transform: rotate(0deg); transition: 0.6s; }
.layout-frame-overlay .bday-card {
    border: 3px solid <?= $p[2] ?>; border-radius: 30px; padding: 50px 40px;
    background: rgba(0,0,0,0.15); backdrop-filter: blur(20px);
}
.layout-circle-focus .bday-photo-wrap { width: 200px; height: 200px; margin-bottom: 40px; }
.layout-fullbleed .bday-name { font-size: clamp(3rem, 12vw, 7rem); letter-spacing: -2px; }
.layout-card-float .bday-card {
    background: rgba(255,255,255,0.1); backdrop-filter: blur(30px);
    border: 1px solid rgba(255,255,255,0.2); border-radius: 30px;
    box-shadow: 0 40px 80px rgba(0,0,0,0.3); padding: 60px 50px;
}

/* ================= 6 DECORATIONS (CSS Only) ================= */
.deco-particle { position: absolute; pointer-events: none; z-index: 5; }

/* Confetti */
.deco-confetti .deco-particle {
    width: 10px; height: 10px; opacity: 0.7;
    animation: confettiFall linear infinite;
}
@keyframes confettiFall {
    0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
    100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
}

/* Stars */
.deco-stars .deco-particle {
    width: 4px; height: 4px; background: #fff; border-radius: 50%;
    animation: twinkle 2s ease-in-out infinite alternate;
}
@keyframes twinkle { 0% { opacity: 0.2; transform: scale(0.5); } 100% { opacity: 1; transform: scale(1.5); } }

/* Balloons */
.deco-balloons .deco-particle {
    width: 30px; height: 38px; border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
    opacity: 0.6; animation: floatUp 8s ease-in-out infinite;
}
@keyframes floatUp { 0%,100% { transform: translateY(0) rotate(-5deg); } 50% { transform: translateY(-30px) rotate(5deg); } }

/* Sparkles */
.deco-sparkles .deco-particle {
    width: 6px; height: 6px; background: <?= $p[2] ?>; border-radius: 50%;
    box-shadow: 0 0 10px <?= $p[2] ?>, 0 0 20px <?= $p[2] ?>;
    animation: sparkPulse 1.5s ease-in-out infinite alternate;
}
@keyframes sparkPulse { 0% { opacity: 0.3; transform: scale(0.5); } 100% { opacity: 1; transform: scale(1.2); } }

/* Ribbons */
.deco-ribbons .deco-particle {
    width: 3px; height: 40px; opacity: 0.4; border-radius: 2px;
    animation: ribbonDrift 6s ease-in-out infinite;
}
@keyframes ribbonDrift { 0%,100% { transform: translateX(0) rotate(0deg); } 50% { transform: translateX(20px) rotate(15deg); } }

/* Floral */
.deco-floral .deco-particle {
    width: 20px; height: 20px; border-radius: 50%; opacity: 0.3;
    border: 2px solid <?= $p[2] ?>;
    animation: floralSpin 10s linear infinite;
}
@keyframes floralSpin { 100% { transform: rotate(360deg); } }

/* ================= 4 ANIMATIONS ================= */
.anim-cascade .bday-anim-el { opacity: 0; transform: translateY(40px); animation: cascadeIn 0.8s ease forwards; }
.anim-bounce .bday-anim-el { opacity: 0; transform: scale(0.5); animation: bounceIn 0.7s cubic-bezier(0.68,-0.55,0.27,1.55) forwards; }
.anim-bloom .bday-anim-el { opacity: 0; transform: scale(0) rotate(-10deg); animation: bloomIn 0.9s ease forwards; }
.anim-burst .bday-anim-el { opacity: 0; transform: translateY(60px) scale(0.8); animation: burstIn 0.6s ease forwards; }

@keyframes cascadeIn { to { opacity: 1; transform: translateY(0); } }
@keyframes bounceIn { to { opacity: 1; transform: scale(1); } }
@keyframes bloomIn { to { opacity: 1; transform: scale(1) rotate(0deg); } }
@keyframes burstIn { to { opacity: 1; transform: translateY(0) scale(1); } }

.bday-anim-el:nth-child(1) { animation-delay: 0.1s; }
.bday-anim-el:nth-child(2) { animation-delay: 0.25s; }
.bday-anim-el:nth-child(3) { animation-delay: 0.4s; }
.bday-anim-el:nth-child(4) { animation-delay: 0.55s; }
.bday-anim-el:nth-child(5) { animation-delay: 0.7s; }
.bday-anim-el:nth-child(6) { animation-delay: 0.85s; }
.bday-anim-el:nth-child(7) { animation-delay: 1.0s; }
.bday-anim-el:nth-child(8) { animation-delay: 1.15s; }

/* ================= BACK BUTTON ================= */
.bday-back {
    position: fixed; top: 20px; left: 20px; z-index: 100;
    width: 45px; height: 45px; border-radius: 50%;
    background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2); color: #fff;
    display: flex; justify-content: center; align-items: center;
    text-decoration: none; font-size: 1.1rem; transition: 0.3s;
}
.bday-back:hover { background: rgba(0,0,0,0.6); transform: scale(1.1); }

/* ================= MOBILE ================= */
@media (max-width: 768px) {
    .bday-card { padding: 40px 20px; }
    .bday-photo-wrap { width: 130px; height: 130px; }
    .layout-card-float .bday-card { padding: 40px 25px; border-radius: 20px; }
    .layout-frame-overlay .bday-card { padding: 35px 25px; }
}

/* Expedient Watermark */
.bday-watermark {
    position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
    font-size: 0.65rem; letter-spacing: 4px; text-transform: uppercase;
    opacity: 0.3; z-index: 5; white-space: nowrap;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="/beranda" class="bday-back"><i class="fa-solid fa-arrow-left"></i></a>

<div class="bday-universe layout-<?= $layout ?> deco-<?= $deco ?> anim-<?= $anim ?>">

    <!-- Decoration Particles (generated by JS) -->
    <div id="decoContainer"></div>

    <div class="bday-card">
        <!-- Photo -->
        <div class="bday-anim-el">
            <div class="bday-photo-wrap">
                <?php if (!empty($foto_url)): ?>
                    <img src="<?= esc($foto_url) ?>" alt="<?= esc($user['nama_panggilan']) ?>">
                <?php else: ?>
                    <div class="bday-photo-placeholder bday-heading">🎂</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pre-title -->
        <div class="bday-pretitle bday-body bday-sub bday-anim-el">Selamat Ulang Tahun</div>

        <!-- Name -->
        <h1 class="bday-name bday-heading bday-text bday-anim-el"><?= esc($user['nama_panggilan']) ?></h1>

        <!-- Age -->
        <div class="bday-age bday-body bday-text bday-anim-el">
            Ke-<strong style="font-size: 1.4em;"><?= $usia ?></strong> Tahun
        </div>

        <!-- Zodiak -->
        <div class="bday-zodiak bday-body bday-text bday-anim-el">
            <span style="font-size: 1.3em;"><?= $zodiak['icon'] ?></span>
            <?= $zodiak['nama'] ?>
        </div>

        <!-- Date Badge -->
        <div class="bday-anim-el">
            <div class="bday-date-badge bday-body bday-text">
                <?= date('d F Y', strtotime($user['tanggal_lahir'])) ?>
            </div>
        </div>

        <!-- Wishes -->
        <p class="bday-wishes bday-body bday-sub bday-anim-el">
            Semoga Allah SWT senantiasa melimpahkan keberkahan, kesehatan, dan kebahagiaan di setiap langkahmu. Barakallahu fiik! 🤲
        </p>

        <!-- Share Button -->
        <div class="bday-anim-el">
            <a href="https://wa.me/?text=🎂 Selamat Ulang Tahun <?= urlencode($user['nama_panggilan']) ?>! Lihat ucapannya di: <?= urlencode(base_url('birthday/' . $user['id'])) ?>" target="_blank" class="bday-share-btn bday-body">
                <i class="fa-brands fa-whatsapp"></i> Kirim Ucapan
            </a>
        </div>
    </div>

    <div class="bday-watermark bday-body bday-text">Expedient Generation — 42nd Arrisalah</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('decoContainer');
    const deco = '<?= $deco ?>';
    const colors = ['<?= $p[0] ?>','<?= $p[1] ?>','<?= $p[2] ?>','#fff','#ffd700','#ff6b6b','#48dbfb','#55efc4'];
    const count = deco === 'stars' ? 50 : deco === 'sparkles' ? 35 : 25;

    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.classList.add('deco-particle');
        el.style.left = Math.random() * 100 + '%';
        el.style.top = Math.random() * 100 + '%';
        el.style.animationDuration = (Math.random() * 5 + 3) + 's';
        el.style.animationDelay = (Math.random() * 3) + 's';

        if (deco === 'confetti') {
            el.style.background = colors[Math.floor(Math.random() * colors.length)];
            el.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
            el.style.width = (Math.random() * 8 + 6) + 'px';
            el.style.height = (Math.random() * 8 + 6) + 'px';
        } else if (deco === 'balloons') {
            el.style.background = colors[Math.floor(Math.random() * colors.length)];
        } else if (deco === 'ribbons') {
            el.style.background = colors[Math.floor(Math.random() * colors.length)];
            el.style.height = (Math.random() * 30 + 20) + 'px';
        }

        container.appendChild(el);
    }
});
</script>
<?= $this->endSection() ?>
