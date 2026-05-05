<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Expedient Vault - Protokol Keamanan Tingkat Tinggi
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --glass-bg: rgba(5, 10, 8, 0.4);
        --neon-green: #00ff88;
        --neon-blue: #00e5ff;
        --hud-color: var(--gold-main);
    }

    body {
        background-color: #010201;
        background-image: 
            radial-gradient(circle at 50% -20%, rgba(212,175,55,0.15) 0%, transparent 50%),
            linear-gradient(180deg, #050a08 0%, #010201 100%);
        overflow-x: hidden;
    }

    .vault-wrapper {
        position: relative; width: 100%; min-height: 100vh;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 20px; z-index: 10;
    }

    /* ================= FASE 1: GOD-TIER HUD SCANNER ================= */
    .auth-vault {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        width: 100%; max-width: 500px; transition: all 1s cubic-bezier(0.25, 1, 0.5, 1); z-index: 50;
    }

    .hud-container {
        position: relative; width: clamp(260px, 70vw, 340px); aspect-ratio: 3/4;
        display: flex; justify-content: center; align-items: center;
    }

    /* Sudut-sudut HUD (Bracket) */
    .hud-bracket {
        position: absolute; width: 40px; height: 40px; border: 3px solid var(--hud-color); z-index: 10;
        transition: all 0.3s; box-shadow: 0 0 15px var(--hud-color); opacity: 0.8;
    }
    .hud-tl { top: -10px; left: -10px; border-right: none; border-bottom: none; }
    .hud-tr { top: -10px; right: -10px; border-left: none; border-bottom: none; }
    .hud-bl { bottom: -10px; left: -10px; border-right: none; border-top: none; }
    .hud-br { bottom: -10px; right: -10px; border-left: none; border-top: none; }

    .camera-ring {
        position: relative; width: 100%; height: 100%; border-radius: 20px;
        background: rgba(0,0,0,0.8); box-shadow: 0 20px 50px rgba(0,0,0,0.8); overflow: hidden;
        border: 1px solid rgba(212,175,55,0.2);
    }

    .camera-feed { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); filter: contrast(1.1) saturate(1.2); }
    
    .scanner-sweep {
        position: absolute; top: 0; left: 0; width: 100%; height: 20%;
        background: linear-gradient(to bottom, transparent, rgba(212,175,55,0.4), rgba(212,175,55,0.8));
        border-bottom: 2px solid var(--hud-color); box-shadow: 0 5px 20px var(--hud-color);
        animation: elegantSweep 2.5s ease-in-out infinite alternate; pointer-events: none; display: none;
    }
    @keyframes elegantSweep { 0% { transform: translateY(-100%); } 100% { transform: translateY(500%); } }

    /* Indikator Liveness (3 Tahap) */
    .liveness-steps {
        display: flex; gap: 10px; margin-top: 25px;
    }
    .step-dot {
        width: 40px; height: 6px; border-radius: 3px; background: rgba(255,255,255,0.1);
        transition: all 0.5s; position: relative; overflow: hidden;
    }
    .step-dot.active { background: var(--hud-color); box-shadow: 0 0 10px var(--hud-color); }
    .step-dot.done { background: var(--neon-green); box-shadow: 0 0 10px var(--neon-green); }

    .status-badge {
        margin-top: 20px; padding: 15px 35px; background: rgba(0,0,0,0.6); backdrop-filter: blur(20px);
        border: 1px solid rgba(212,175,55,0.3); border-radius: 8px; color: #fff; font-family: 'Courier New', monospace;
        font-size: 0.9rem; letter-spacing: 2px; font-weight: bold; text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-left: 4px solid var(--hud-color); text-transform: uppercase;
    }


    /* ================= FASE 2: MAGNETIC 3D GRID (AWWWARDS LEVEL) ================= */
    .features-dashboard {
        display: none; width: 100%; max-width: 1200px;
        flex-direction: column; align-items: center; opacity: 0; z-index: 20;
    }

    .dashboard-header { text-align: center; margin-bottom: 70px; }
    .dashboard-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: clamp(2.5rem, 6vw, 4.5rem); margin: 0; letter-spacing: 5px; text-transform: uppercase; text-shadow: 0 15px 30px rgba(0,0,0,0.9); }
    .dashboard-subtitle { color: #7b8e9b; font-size: 1rem; letter-spacing: 6px; text-transform: uppercase; margin-top: 15px; }

    .cinematic-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; width: 100%; padding: 0 20px; perspective: 1500px; }

    /* Kartu Magnetic 3D */
    .premium-card {
        position: relative; height: 420px; border-radius: 20px; text-decoration: none; transform-style: preserve-3d;
        box-shadow: 0 25px 50px rgba(0,0,0,0.8); border: 1px solid rgba(255,255,255,0.05); 
        /* Transisi diatur oleh JS saat hover agar mulus */
        background-color: #050a08;
    }

    .premium-card .card-bg {
        position: absolute; inset: -20px; background-size: cover; background-position: center; 
        transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1), filter 0.8s; z-index: 1; filter: grayscale(100%) brightness(0.4);
    }
    .premium-card .card-bg::after {
        content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, transparent 80%);
    }

    .premium-card::before {
        content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 50% 100%, rgba(212,175,55,0.2) 0%, transparent 60%);
        z-index: 2; opacity: 0; transition: opacity 0.5s; pointer-events: none;
    }

    .card-content {
        position: absolute; inset: 0; z-index: 10; padding: 40px 30px; display: flex; flex-direction: column; justify-content: flex-end;
        transform: translateZ(40px); /* Efek Teks Melayang (Parallax) */ pointer-events: none;
    }

    .card-icon { position: absolute; top: 30px; right: 30px; font-size: 2.2rem; color: rgba(255,255,255,0.2); transition: color 0.5s, transform 0.5s; transform: translateZ(30px); }
    .card-title { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.8rem; margin: 0 0 10px 0; transform: translateY(20px); transition: 0.5s; }
    .card-desc { color: #7b8e9b; font-size: 0.85rem; line-height: 1.6; margin: 0; opacity: 0; transform: translateY(20px); transition: 0.5s; }
    .launch-btn { margin-top: 20px; font-size: 0.75rem; letter-spacing: 3px; color: var(--gold-main); text-transform: uppercase; opacity: 0; transform: translateY(20px); transition: 0.5s; display: flex; align-items: center; gap: 10px; font-weight: bold; }

    /* HOVER STATE (JS takes over tilt, CSS handles color/opacity) */
    .premium-card:hover .card-bg { filter: grayscale(0%) brightness(0.7); transform: scale(1.05); }
    .premium-card:hover::before { opacity: 1; }
    .premium-card:hover .card-icon { color: var(--gold-main); transform: translateZ(50px) scale(1.1); text-shadow: 0 0 20px rgba(212,175,55,0.5); }
    .premium-card:hover .card-title, .premium-card:hover .card-desc, .premium-card:hover .launch-btn { transform: translateY(0); opacity: 1; }

    .locked-card { filter: grayscale(100%); opacity: 0.5; pointer-events: none; }
    .locked-card .card-icon { color: #ff3366 !important; }

    /* ================= MODE SIANG (LIGHT THEME) ================= */
    :root[data-theme="light"] body {
        background-color: #f8faf9;
        background-image: 
            radial-gradient(circle at 50% -20%, rgba(212,175,55,0.1) 0%, transparent 50%),
            linear-gradient(180deg, #f8faf9 0%, #eef1ef 100%);
    }

    :root[data-theme="light"] .dashboard-title {
        color: #aa771c;
        text-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    :root[data-theme="light"] .dashboard-subtitle {
        color: #64748b;
    }

    :root[data-theme="light"] .premium-card {
        background-color: #ffffff;
        box-shadow: 0 15px 40px rgba(0,20,40,0.1);
        border: 1px solid rgba(212,175,55,0.15);
    }

    :root[data-theme="light"] .premium-card .card-bg {
        filter: grayscale(50%) brightness(0.6);
    }

    :root[data-theme="light"] .premium-card:hover .card-bg {
        filter: grayscale(0%) brightness(0.8);
    }

    :root[data-theme="light"] .card-title {
        color: #0f1714;
    }

    :root[data-theme="light"] .card-desc {
        color: #64748b;
    }

    :root[data-theme="light"] .launch-btn {
        color: #aa771c;
    }

    :root[data-theme="light"] .card-icon {
        color: rgba(0,0,0,0.15);
    }

    :root[data-theme="light"] .premium-card:hover .card-icon {
        color: #aa771c;
    }

    :root[data-theme="light"] .premium-card::before {
        background: radial-gradient(circle at 50% 100%, rgba(212,175,55,0.1) 0%, transparent 60%);
    }

    :root[data-theme="light"] .locked-card {
        filter: grayscale(100%); opacity: 0.35;
    }

    :root[data-theme="light"] .status-badge {
        background: rgba(255,255,255,0.8);
        border: 1px solid rgba(212,175,55,0.3);
        color: #0f1714;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="vault-wrapper">



    <div class="features-dashboard" id="featuresDashboard" style="display:flex; opacity:1;">
        <div class="dashboard-header">
            <h1 class="dashboard-title">The Sovereign Vault</h1>
            <p class="dashboard-subtitle">Akses Eksklusif Entitas Expedient Terverifikasi</p>
        </div>

        <div class="cinematic-grid">
            <a href="/sovereign" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-gem card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Sovereign ID</h3>
                    <p class="card-desc">Modul identitas 5D interaktif. Merender ulang data biometrik dan arsip Anda dalam bentuk holografik.</p>
                    <div class="launch-btn">Inisiasi Protokol <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/profil" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2670&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-id-badge card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Profil Entitas</h3>
                    <p class="card-desc">Ruang kendali utama. Manajemen data pribadi dan pengaturan kunci keamanan fisik (Passkey).</p>
                    <div class="launch-btn">Akses Ruang Kendali <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/wasiat" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1614064641913-a520f596a247?q=80&w=2574&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.3);"></div>
                <i class="fa-solid fa-vault card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Amanah & Wasiat</h3>
                    <p class="card-desc">Brankas pesan terenkripsi tingkat tinggi. Titipkan pesan rahasia, wasiat, atau data vital yang hanya terbuka dengan pemicu otentikasi spesifik.</p>
                    <div class="launch-btn">Akses Keamanan Maksimal <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/baitul-maal" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1579621970795-87facc2f976d?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-coins card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Baitul Maal</h3>
                    <p class="card-desc">Pusat kontribusi dan wakaf elit. Visualisasi rekam jejak sedekah jariyah angkatan dalam bentuk tabungan cahaya keabadian.</p>
                    <div class="launch-btn">Buka Khasanah <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/majlis" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1594954002661-8f55fc15d7de?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-microphone-lines card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Majlis Syura</h3>
                    <p class="card-desc">Bilik suara VVIP eksklusif. Dengarkan kajian, bertukar pikiran, dan jalin ukhuwah dalam keheningan yang elegan.</p>
                    <div class="launch-btn">Masuk Ruang Majlis <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/tarbiyah" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32b7?q=80&w=2532&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-handshake-angle card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Tarbiyah Nexus</h3>
                    <p class="card-desc">Jaringan mentorship elit & ekosistem B2B Halal. Ruang kolaborasi profesional antar entitas untuk memperkuat muamalah dan karir.</p>
                    <div class="launch-btn">Buka Jaringan <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/oracle" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1620712943543-bcc4688e7485?q=80&w=2565&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-eye card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">The Oracle's Vision</h3>
                    <p class="card-desc">Pemindai kamera interaktif untuk mengekstraksi dan membaca Aura Eksekutif Anda secara langsung.</p>
                    <div class="launch-btn">Inisiasi Visi <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/multazam" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1542642510-48227b613eec?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-ticket card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Protokol Multazam</h3>
                    <p class="card-desc">Sistem RSVP & Tiket Cerdas untuk acara VVIP. Hadiri kajian akbar dan gala diner angkatan dengan otorisasi pass digital eksklusif.</p>
                    <div class="launch-btn">Akses Protokol Acara <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/kontemplasi" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1518241353330-0f7941c2d9b5?q=80&w=2574&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.3);"></div>
                <i class="fa-solid fa-peace card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Ruang Kontemplasi</h3>
                    <p class="card-desc">Mode sanctuary layar penuh. Temukan kedamaian dari bisingnya dunia dengan keheningan, tata napas, dan audio ambience Islami.</p>
                    <div class="launch-btn">Masuki Keheningan <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/celestial" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?q=80&w=2670&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-star card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">The Celestial Codex</h3>
                    <p class="card-desc">Tarik tiga kartu takdir dari dek misterius. Ungkap ramalan dan kebijaksanaan hari ini.</p>
                    <div class="launch-btn">Buka Codex <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>
        </div>
    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // === MAGNETIC 3D TILT EFFECT (AWWWARDS JS) ===
    const cards = document.querySelectorAll('.js-tilt-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = ((y - centerY) / centerY) * -15;
            const rotateY = ((x - centerX) / centerX) * 15;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            card.style.transition = "none";
        });

        card.addEventListener('mouseleave', () => {
            card.style.transition = "transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)";
            card.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
        });
    });

    // Intro Animation
    gsap.from(".dashboard-header", { opacity: 0, y: -50, duration: 1.5, ease: "expo.out" });
    gsap.from(".premium-card", { 
        opacity: 0, y: 100, rotationX: -20, duration: 1.2, 
        stagger: 0.15, ease: "back.out(1.5)", delay: 0.2 
    });
});
</script>
<?= $this->endSection() ?>