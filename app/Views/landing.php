<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient Generation — 42nd Arrisalah</title>

    <meta name="description" content="Museum Digital VVIP & Platform Komunitas Alumni Eksklusif Pondok Modern Arrisalah Angkatan ke-42.">
    <meta property="og:title" content="Expedient Generation — 42nd Arrisalah">
    <meta property="og:description" content="Akses portal eksklusif peninggalan dan jejak langkah Expedient.">
    <meta property="og:image" content="<?= base_url('images/logo-utuh.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        const savedTheme = localStorage.getItem('expedient_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <style>
        :root {
            --bg-main: #030504; --bg-radial: #0a110e;
            --glass-bg: rgba(255, 255, 255, 0.02); --glass-blur: blur(40px) saturate(150%);
            --glass-border: rgba(255, 255, 255, 0.08); --glass-shadow: 0 30px 60px rgba(0,0,0,0.4);
            --text-primary: #ffffff; --text-secondary: #8b9ba8;
            --gold: #d4af37; --gold-glow: rgba(212, 175, 55, 0.4);
        }
        :root[data-theme="light"] {
            --bg-main: #f8faf9; --bg-radial: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.6); --glass-border: rgba(212, 175, 55, 0.3);
            --glass-shadow: 0 30px 60px rgba(0,20,40,0.08);
            --text-primary: #0f1714; --text-secondary: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body {
            background: var(--bg-main);
            background-image: radial-gradient(circle at 50% 30%, var(--bg-radial), transparent 80%);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ====== AURORA BACKGROUND ====== */
        .aurora-container { position: fixed; inset: -30%; z-index: 0; pointer-events: none; filter: blur(30px); }
        .aurora-blob { position: absolute; border-radius: 50%; opacity: 0.9; mix-blend-mode: screen; animation: morphBlob 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); }
        :root[data-theme="light"] .aurora-blob { mix-blend-mode: multiply; opacity: 0.7; }
        .blob-1 { width: 55vw; height: 55vw; background: rgba(0, 255, 136, 0.08); top: -10%; left: 0; }
        .blob-2 { width: 65vw; height: 65vw; background: rgba(0, 162, 255, 0.06); bottom: -10%; right: -10%; animation-delay: -7s; }
        .blob-3 { width: 45vw; height: 45vw; background: rgba(212, 175, 55, 0.05); top: 40%; left: 30%; animation-delay: -14s; }
        @keyframes morphBlob { 0% { transform: scale(1) translate(0, 0) rotate(0deg); } 100% { transform: scale(1.4) translate(120px, -80px) rotate(90deg); } }

        /* ====== FILM GRAIN ====== */
        .film-grain { position: fixed; inset: 0; pointer-events: none; z-index: 1; opacity: 0.04; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E"); }

        /* ====== PARTICLE CANVAS ====== */
        #particleCanvas { position: fixed; inset: 0; z-index: 1; pointer-events: none; }

        /* ====== MAIN CONTENT ====== */
        .landing-content {
            position: relative; z-index: 10;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            min-height: 100vh; padding: 40px 20px; text-align: center;
        }

        /* ====== LOGO ====== */
        .logo-container { margin-bottom: 40px; position: relative; }
        .logo-img {
            width: clamp(100px, 20vw, 180px); height: auto;
            filter: drop-shadow(0 0 30px var(--gold-glow));
            animation: floatLogo 6s ease-in-out infinite;
        }
        .logo-ring {
            position: absolute; inset: -20px; border: 1px solid rgba(212,175,55,0.2);
            border-radius: 50%; animation: spinSlow 20s linear infinite;
        }
        .logo-ring::before {
            content: ''; position: absolute; top: -3px; left: 50%; width: 6px; height: 6px;
            background: var(--gold); border-radius: 50%; box-shadow: 0 0 15px var(--gold);
        }
        @keyframes floatLogo { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        @keyframes spinSlow { 100% { transform: rotate(360deg); } }

        /* ====== TYPOGRAPHY ====== */
        .landing-eyebrow {
            font-family: 'Courier New', monospace; font-size: 0.7rem;
            letter-spacing: 8px; text-transform: uppercase;
            color: var(--gold); margin-bottom: 15px; opacity: 0;
            animation: fadeUp 1s ease-out 0.3s forwards;
        }
        .landing-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 6vw, 4.5rem); font-weight: 900;
            line-height: 1.1; margin-bottom: 20px;
            background: linear-gradient(135deg, var(--text-primary) 30%, var(--gold) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; opacity: 0;
            animation: fadeUp 1s ease-out 0.5s forwards;
        }
        .landing-subtitle {
            font-size: clamp(0.9rem, 2vw, 1.15rem); color: var(--text-secondary);
            max-width: 600px; line-height: 1.7; margin-bottom: 50px; opacity: 0;
            animation: fadeUp 1s ease-out 0.7s forwards;
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* ====== CTA BUTTONS ====== */
        .cta-group { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; opacity: 0; animation: fadeUp 1s ease-out 0.9s forwards; }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold), #c9a029);
            color: #030504; padding: 16px 40px;
            border-radius: 12px; text-decoration: none;
            font-weight: 700; font-size: 0.9rem;
            letter-spacing: 3px; text-transform: uppercase;
            transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
            display: inline-flex; align-items: center; gap: 10px;
        }
        .btn-primary:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.5);
        }

        .btn-secondary {
            background: var(--glass-bg); backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary); padding: 16px 40px;
            border-radius: 12px; text-decoration: none;
            font-weight: 600; font-size: 0.9rem;
            letter-spacing: 3px; text-transform: uppercase;
            transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex; align-items: center; gap: 10px;
        }
        .btn-secondary:hover {
            transform: translateY(-4px);
            border-color: rgba(212,175,55,0.5);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        /* ====== STATS ROW ====== */
        .stats-row {
            display: flex; gap: 60px; margin-top: 70px; opacity: 0;
            animation: fadeUp 1s ease-out 1.1s forwards;
        }
        .stat-item { text-align: center; }
        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; color: var(--gold);
        }
        .stat-label {
            font-size: 0.7rem; letter-spacing: 3px; text-transform: uppercase;
            color: var(--text-secondary); margin-top: 5px;
        }

        /* ====== SCROLL HINT ====== */
        .scroll-hint {
            position: absolute; bottom: 30px; left: 0; width: 100%;
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: var(--text-secondary); font-size: 0.7rem; letter-spacing: 4px; text-transform: uppercase;
            opacity: 0; animation: fadeUp 1s ease-out 1.5s forwards; pointer-events: none;
        }
        .scroll-line {
            width: 1px; height: 40px; background: linear-gradient(to bottom, var(--gold), transparent);
            animation: scrollPulse 2s ease-in-out infinite;
        }
        @keyframes scrollPulse { 0%, 100% { opacity: 0.3; transform: scaleY(0.6); } 50% { opacity: 1; transform: scaleY(1); } }

        /* ====== SECTION 2: TENTANG ====== */
        .about-section {
            position: relative; z-index: 10;
            padding: 100px 20px; max-width: 900px; margin: 0 auto;
            text-align: center;
        }
        .about-eyebrow {
            font-family: 'Courier New', monospace; font-size: 0.7rem;
            letter-spacing: 8px; text-transform: uppercase;
            color: var(--gold); margin-bottom: 15px;
        }
        .about-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 3.5vw, 2.5rem); font-weight: 700;
            color: var(--text-primary); margin-bottom: 30px;
        }
        .about-text {
            color: var(--text-secondary); line-height: 2; font-size: 1rem;
            max-width: 700px; margin: 0 auto 40px;
        }
        .about-features {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-top: 50px;
        }
        .feature-card {
            background: var(--glass-bg); backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 20px;
            padding: 40px 30px; text-align: left; transition: 0.5s;
            position: relative; overflow: hidden; transform-style: preserve-3d;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .feature-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(212,175,55,0.1), transparent 50%);
            pointer-events: none; opacity: 0; transition: 0.5s;
        }
        .feature-card:hover { border-color: rgba(212,175,55,0.4); box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 40px rgba(212,175,55,0.1); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon { font-size: 2rem; color: var(--gold); margin-bottom: 20px; transform: translateZ(30px); filter: drop-shadow(0 5px 15px rgba(212,175,55,0.4)); }
        .feature-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; margin-bottom: 12px; transform: translateZ(20px); }
        .feature-desc { font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6; transform: translateZ(10px); }

        /* ====== FOOTER ====== */
        .landing-footer {
            position: relative; z-index: 10; text-align: center;
            padding: 40px 20px; border-top: 1px solid var(--glass-border);
            color: var(--text-secondary); font-size: 0.75rem; letter-spacing: 2px;
        }

        /* ====== THEME TOGGLE ====== */
        .theme-toggle {
            position: fixed; top: 30px; right: 30px; z-index: 100;
            background: var(--glass-bg); backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 40px;
            padding: 10px 14px; cursor: pointer; color: var(--text-primary);
            font-size: 1.1rem; transition: 0.4s; box-shadow: var(--glass-shadow);
        }
        .theme-toggle:hover { transform: scale(1.1); border-color: var(--gold); }

        @media (max-width: 600px) {
            .stats-row { gap: 30px; }
            .cta-group { flex-direction: column; align-items: center; }
            .btn-primary, .btn-secondary { width: 100%; max-width: 300px; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="film-grain"></div>
    <div class="aurora-container">
        <div class="aurora-blob blob-1"></div>
        <div class="aurora-blob blob-2"></div>
        <div class="aurora-blob blob-3"></div>
    </div>
    <canvas id="particleCanvas"></canvas>

    <button class="theme-toggle" id="themeToggle" title="Ganti Mode">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
    </button>

    <!-- ====== HERO SECTION ====== -->
    <section class="landing-content">
        <div class="logo-container">
            <div class="logo-ring"></div>
            <?= cms_image('landing_hero_logo', '/images/logo-utuh.png', 'logo-img', 'alt="Expedient Generation"') ?>
        </div>

        <p class="landing-eyebrow"><?= cms_text('landing_eyebrow', '42nd Pondok Modern Arrisalah') ?></p>
        <h1 class="landing-title"><?= cms_html('landing_title', 'Expedient<br>Generation') ?></h1>
        <p class="landing-subtitle">
            <?= cms_html('landing_subtitle', 'Museum digital eksklusif dan platform komunitas alumni angkatan ke-42.<br>Menjaga warisan, membangun masa depan, mempersatukan barisan.') ?>
        </p>

        <div class="cta-group">
            <a href="/login" class="btn-primary" id="ctaLogin">
                <i class="fa-solid fa-right-to-bracket"></i> <?= cms_text('landing_btn_login', 'Masuk ke Portal') ?>
            </a>
            <a href="/beranda" class="btn-secondary" id="ctaExplore">
                <i class="fa-solid fa-compass"></i> <?= cms_text('landing_btn_explore', 'Jelajahi Museum') ?>
            </a>
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-number" id="counterAlumni"><?= $total_alumni ?></div>
                <div class="stat-label">Entitas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">2025</div>
                <div class="stat-label"><?= cms_text('landing_stat_2_label', 'Tahun Kebangkitan') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number">12</div>
                <div class="stat-label"><?= cms_text('landing_stat_3_label', 'Modul VVIP') ?></div>
            </div>
        </div>

        <div class="scroll-hint">
            <span>Gulir</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <!-- ====== ABOUT SECTION ====== -->
    <section class="about-section">
        <p class="about-eyebrow"><?= cms_text('landing_about_eyebrow', 'Tentang Kami') ?></p>
        <h2 class="about-title"><?= cms_text('landing_about_title', 'Kami Bukan Sekadar Angkatan') ?></h2>
        <div class="about-text">
            <?= cms_html('landing_about_desc', 'Kami adalah barisan pelopor yang lahir dari rahim Arrisalah, dibentuk oleh waktu, dipersatukan oleh takdir. Platform ini adalah monumen digital untuk menjaga silaturahmi, mendokumentasikan jejak langkah, dan membangun masa depan bersama.') ?>
        </div>

        <div class="about-features">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-landmark"></i></div>
                <div class="feature-title"><?= cms_text('landing_feat1_title', 'Museum Interaktif') ?></div>
                <div class="feature-desc"><?= cms_text('landing_feat1_desc', 'Beranda dengan galeri kenangan, timeline sejarah, dan arsip angkatan.') ?></div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-earth-americas"></i></div>
                <div class="feature-title"><?= cms_text('landing_feat2_title', 'Global Radar') ?></div>
                <div class="feature-desc"><?= cms_text('landing_feat2_desc', 'Peta 3D persebaran alumni di seluruh Indonesia dan dunia.') ?></div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-id-card"></i></div>
                <div class="feature-title"><?= cms_text('landing_feat3_title', 'Sovereign ID') ?></div>
                <div class="feature-desc"><?= cms_text('landing_feat3_desc', 'Kartu identitas VVIP 3D dengan teknologi Three.js dan WebAuthn.') ?></div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-gem"></i></div>
                <div class="feature-title"><?= cms_text('landing_feat4_title', '12 Modul VVIP') ?></div>
                <div class="feature-desc"><?= cms_text('landing_feat4_desc', 'Oracle Vision, Enigma Vault, Celestial Codex, dan banyak lagi.') ?></div>
            </div>
        </div>
    </section>

    <!-- ====== FOOTER ====== -->
    <footer class="landing-footer">
        &copy; <?= date('Y') ?> <?= cms_text('landing_footer_text', 'Expedient Generation — 42nd Pondok Modern Arrisalah') ?>
    </footer>

    <script>
        // ====== GOLD PARTICLE SYSTEM ======
        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        class Particle {
            constructor() { this.reset(); }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedY = -(Math.random() * 0.3 + 0.1);
                this.speedX = (Math.random() - 0.5) * 0.2;
                this.opacity = Math.random() * 0.5 + 0.1;
                this.life = Math.random() * 200 + 100;
            }
            update() {
                this.y += this.speedY;
                this.x += this.speedX;
                this.life--;
                if (this.life <= 0 || this.y < -10) this.reset();
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(212, 175, 55, ${this.opacity})`;
                ctx.fill();
            }
        }

        for (let i = 0; i < 60; i++) particles.push(new Particle());

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animateParticles);
        }
        animateParticles();

        // ====== THEME TOGGLE ======
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        function updateThemeIcon() {
            const t = document.documentElement.getAttribute('data-theme');
            themeIcon.className = t === 'dark' ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
        }
        updateThemeIcon();

        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('expedient_theme', next);
            updateThemeIcon();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>
        VanillaTilt.init(document.querySelectorAll(".feature-card"), {
            max: 15, speed: 400, glare: true, "max-glare": 0.2, perspective: 1000
        });
    </script>
</body>
</html>
