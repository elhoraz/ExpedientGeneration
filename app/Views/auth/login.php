<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Gerbang Akses - Expedient Generation</title>
    
    <meta name="description" content="Portal login eksklusif Expedient Generation - Museum Galeri Digital VVIP.">
    <meta name="theme-color" content="#030504">
    <meta property="og:title" content="Gerbang Akses - Expedient Generation">
    <meta property="og:description" content="Akses portal eksklusif peninggalan dan jejak langkah Expedient.">
    <meta property="og:image" content="/images/logo-utuh.png">
    <meta property="og:type" content="website">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/logo-utuh.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ================= 1. ABSOLUTE SCALING & VARIABLES ================= */
        :root {
            /* THE VOID (DARK) */
            --bg-base: #010302;
            --bg-radial: #030d08;
            
            --glow-1: rgba(0, 255, 136, 0.15);
            --glow-2: rgba(0, 162, 255, 0.12);
            --glow-3: rgba(255, 215, 0, 0.08);
            
            --glass-surface: rgba(4, 10, 7, 0.6);
            --glass-border: rgba(0, 255, 136, 0.15);
            --glass-shadow: 0 40px 80px rgba(0,0,0,0.9);
            
            --text-main: #f0f5f2;
            --text-muted: #5e7a6b;
            
            --gold-liquid: linear-gradient(135deg, #d4af37 0%, #fff2cd 40%, #aa771c 60%, #d4af37 100%);
            --emerald-liquid: linear-gradient(135deg, #00ff88, #008844);
            
            --input-bg: rgba(0, 0, 0, 0.5);
            --input-focus: rgba(0, 255, 136, 0.1);
        }

        [data-theme="light"] {
            /* THE PEARL (LIGHT) */
            --bg-base: #f0f5f3;
            --bg-radial: #ffffff;
            
            --glow-1: rgba(0, 255, 136, 0.15);
            --glow-2: rgba(0, 162, 255, 0.1);
            --glow-3: rgba(255, 215, 0, 0.15);
            
            --glass-surface: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 1);
            --glass-shadow: 0 30px 60px rgba(0, 20, 10, 0.08);
            
            --text-main: #041008;
            --text-muted: #6a8275;
            
            --gold-liquid: linear-gradient(135deg, #aa771c 0%, #d4af37 40%, #e6c27a 60%, #aa771c 100%);
            
            --input-bg: rgba(255, 255, 255, 0.8);
            --input-focus: rgba(0, 255, 136, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }

        /* ================= 2. NO-SCROLL IMMERSIVE ENVIRONMENT ================= */
        body {
            background-color: var(--bg-base);
            background-image: radial-gradient(circle at 50% 0%, var(--bg-radial), transparent 80%);
            color: var(--text-main);
            width: 100vw; height: 100vh; /* Absolute Fit */
            display: flex; justify-content: center; align-items: center;
            overflow: hidden; /* Dilarang Scroll */
            perspective: 2000px;
            transition: background 1.2s cubic-bezier(0.25, 1, 0.5, 1);
            touch-action: none; /* Cegah zoom/scroll di mobile */
        }

        /* Ambient Orbs */
        .ambient-field { position: absolute; inset: -20%; z-index: 0; pointer-events: none; filter: blur(50px); transition: transform 0.5s ease-out; will-change: transform; }
        .core-orb { position: absolute; border-radius: 50%; opacity: 0.8; mix-blend-mode: screen; transition: transform 0.6s ease-out; will-change: transform; }
        [data-theme="light"] .core-orb { mix-blend-mode: multiply; opacity: 0.6; }
        
        .orb-1 { width: 50vmax; height: 50vmax; background: var(--glow-1); top: -10%; left: 0; animation: breathe 15s alternate infinite ease-in-out; }
        .orb-2 { width: 60vmax; height: 60vmax; background: var(--glow-2); bottom: -10%; right: -10%; animation: breathe 20s alternate-reverse infinite ease-in-out; }
        .orb-3 { width: 40vmax; height: 40vmax; background: var(--glow-3); top: 30%; left: 30%; animation: breathe 25s alternate infinite ease-in-out; }

        @keyframes breathe { 0% { transform: scale(1); } 100% { transform: scale(1.3) translate(5vw, -5vh); } }

        /* MOBILE PERFORMANCE FIX */
        @media (max-width: 768px) {
            .ambient-field { filter: blur(20px); inset: -10%; }
            .core-orb { animation-duration: 30s !important; }
            .orb-1 { width: 35vmax; height: 35vmax; }
            .orb-2 { width: 40vmax; height: 40vmax; }
            .orb-3 { width: 25vmax; height: 25vmax; }
            .auth-prism { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        }

        #particles-js { position: absolute; inset: 0; z-index: 1; pointer-events: none; }

        /* ================= 3. LIQUID PRISM (NO-SCROLL SMART SCALE) ================= */
        .scene-wrapper {
            position: relative; z-index: 10;
            width: 100%; max-width: clamp(320px, 90vw, 440px); /* Smart Width */
            padding: clamp(10px, 2vh, 20px);
            transform-style: preserve-3d;
            display: flex; justify-content: center; align-items: center;
        }

        .auth-prism {
            width: 100%; position: relative;
            background: var(--glass-surface);
            backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px);
            border-radius: clamp(20px, 4vh, 36px);
            /* Smart Padding berdasarkan tinggi layar */
            padding: clamp(20px, 5vh, 45px) clamp(20px, 6vw, 40px);
            box-shadow: var(--glass-shadow);
            
            border-top: 1px solid var(--glass-border);
            border-left: 1px solid var(--glass-border);
            border-bottom: 1px solid rgba(0,0,0,0.8);
            border-right: 1px solid rgba(0,0,0,0.8);
            
            opacity: 0; transform: translateY(5vh) rotateX(5deg);
            animation: prismEnter 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.1) forwards;
            transition: transform 0.15s ease-out; /* Untuk Tilt & Auto-Lift */
        }

        @keyframes prismEnter { to { opacity: 1; transform: translateY(0) rotateX(0deg); } }

        /* Touch Ripple Effect (Mobile Only) */
        .touch-ripple {
            position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.4);
            transform: scale(0); animation: rippleAnim 0.6s linear; pointer-events: none; z-index: 99;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        /* ================= 4. EXPLODING LOGO & TYPOGRAPHY ================= */
        .prism-header { text-align: center; margin-bottom: clamp(15px, 4vh, 35px); transform: translateZ(40px); }
        
        .logo-container { position: relative; width: clamp(70px, 12vh, 90px); height: clamp(70px, 12vh, 90px); margin: 0 auto clamp(10px, 2vh, 20px); }
        
        /* Gambar Pecahan (Tersembunyi Awal) */
        .logo-part {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;
            opacity: 0; pointer-events: none;
            transition: all 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        /* Gambar Logo Utuh */
        .logo-utuh {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;
            filter: drop-shadow(0 15px 25px rgba(0,0,0,0.6));
            opacity: 0; transform: scale(0.5);
            transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1) 1s; /* Delay muncul setelah pecahan hilang */
        }

        /* State Animasi Pecah (Diterapkan via JS) */
        .logo-container.exploding .logo-part { opacity: 1; }
        /* Arah ledakan acak untuk tiap part */
        .logo-container.exploding .part-1 { transform: translate(-50px, -50px) rotate(-45deg) scale(1.5); }
        .logo-container.exploding .part-2 { transform: translate(50px, -50px) rotate(45deg) scale(1.5); }
        .logo-container.exploding .part-3 { transform: translate(-50px, 50px) rotate(-90deg) scale(1.5); }
        .logo-container.exploding .part-4 { transform: translate(50px, 50px) rotate(90deg) scale(1.5); }
        .logo-container.exploding .part-5 { transform: translateZ(100px) scale(2); }

        /* State Animasi Gabung */
        .logo-container.united .logo-part { opacity: 0; transform: translate(0,0) rotate(0) scale(0.5); }
        .logo-container.united .logo-utuh { opacity: 1; transform: scale(1); animation: floatLogo 5s ease-in-out infinite 2s; }
        
        @keyframes floatLogo { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

        .title-holo {
            font-family: 'Playfair Display', serif; font-size: clamp(1.6rem, 4vh, 2.2rem); font-weight: 700;
            background: var(--gold-liquid); background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: 1px; margin-bottom: 2px;
            animation: shimmerHolo 8s linear infinite;
        }
        @keyframes shimmerHolo { to { background-position: 200% center; } }
        
        .subtitle-spec { font-size: clamp(0.55rem, 1.2vh, 0.7rem); color: var(--text-muted); letter-spacing: 4px; text-transform: uppercase; font-weight: 600; }

        /* ================= 5. KINETIC FLUID INPUTS ================= */
        .input-group { position: relative; margin-bottom: clamp(15px, 3vh, 25px); transform: translateZ(30px); }
        
        .input-control {
            width: 100%; padding: clamp(10px, 2vh, 12px) 0; background: var(--input-bg);
            border: none; border-bottom: 1px solid rgba(255,255,255,0.05);
            color: var(--text-main); font-size: clamp(0.9rem, 2vh, 1.05rem); outline: none; transition: 0.4s;
            border-radius: 6px 6px 0 0; padding-left: 12px;
        }
        .input-control::placeholder { color: transparent; }
        
        .input-neon-line {
            position: absolute; bottom: 0; left: 50%; width: 0; height: 2px;
            background: var(--emerald-liquid); transition: 0.5s cubic-bezier(0.25, 1, 0.5, 1); transform: translateX(-50%);
        }

        .input-label {
            position: absolute; top: clamp(10px, 2vh, 12px); left: 12px; color: var(--text-muted); font-size: clamp(0.85rem, 1.8vh, 0.95rem);
            pointer-events: none; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); letter-spacing: 0.5px;
        }

        .input-control:focus, .input-control:not(:placeholder-shown) { background: var(--input-focus); }
        .input-control:focus ~ .input-label, .input-control:not(:placeholder-shown) ~ .input-label {
            top: -14px; left: 0; font-size: clamp(0.6rem, 1.2vh, 0.7rem); color: var(--text-muted); letter-spacing: 1px; text-transform: uppercase; font-weight: 700;
        }
        .input-control:focus ~ .input-neon-line { width: 100%; box-shadow: 0 -2px 10px rgba(0, 255, 136, 0.4); }
        .input-control:focus ~ .input-label { color: #00ff88; }

        .icon-eye { position: absolute; right: 10px; top: clamp(10px, 2vh, 12px); color: var(--text-muted); cursor: pointer; transition: 0.3s; font-size: 1.1rem; }
        .icon-eye:hover { color: var(--text-main); }

        /* ================= 6. MAGNETIC BUTTONS ================= */
        .btn-rack { display: flex; flex-direction: column; gap: clamp(10px, 2vh, 15px); margin-top: clamp(10px, 2vh, 15px); transform: translateZ(35px); }
        .magnetic-wrap { display: inline-block; width: 100%; position: relative; } 

        .btn-prime {
            position: relative; width: 100%; padding: clamp(12px, 2.5vh, 18px); border-radius: 14px; border: none;
            background: var(--gold-liquid); background-size: 200% auto;
            color: #05090a; font-weight: 800; font-size: clamp(0.85rem, 1.8vh, 0.95rem); text-transform: uppercase; letter-spacing: 2px;
            cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5), inset 0 2px 0 rgba(255,255,255,0.4);
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.27); will-change: transform;
        }
        .btn-prime:hover { animation: shimmerHolo 3s linear infinite; box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }

        .divider { display: flex; align-items: center; color: var(--text-muted); font-size: 0.6rem; letter-spacing: 2px; margin: 5px 0; }
        .divider hr { flex: 1; border: none; border-top: 1px solid rgba(255,255,255,0.05); }
        .divider span { padding: 0 15px; }

        .btn-bio {
            width: 100%; padding: clamp(12px, 2.5vh, 16px); border-radius: 14px;
            background: rgba(0, 255, 136, 0.05); border: 1px solid rgba(0, 255, 136, 0.3);
            color: #00ff88; font-weight: 600; font-size: clamp(0.85rem, 1.8vh, 0.9rem); cursor: pointer;
            transition: all 0.3s; display: flex; justify-content: center; align-items: center; gap: 8px;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .btn-bio:hover { background: rgba(0, 255, 136, 0.15); border-color: #00ff88; transform: translateY(-2px); }

        .register-link { text-align: center; margin-top: clamp(15px, 3vh, 25px); color: var(--text-muted); font-size: clamp(0.75rem, 1.5vh, 0.85rem); transform: translateZ(20px); }
        .register-link a { color: var(--text-main); text-decoration: none; font-weight: 600; border-bottom: 1px solid transparent; transition: 0.3s; }
        .register-link a:hover { border-color: #d4af37; color: #d4af37; }

        /* ================= 7. FUTURISTIC THEME TOGGLE ================= */
        .toggle-widget {
            position: fixed; top: 3vh; right: 4vw; z-index: 100;
            background: var(--glass-surface); backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border); border-radius: 30px;
            padding: 5px 12px 5px 5px; cursor: pointer; display: flex; align-items: center; gap: 8px;
            transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: var(--glass-shadow);
        }
        .toggle-widget:hover { transform: translateY(-2px) scale(1.05); border-color: var(--gold-liquid); }
        
        .icon-orb {
            width: clamp(25px, 4vh, 32px); height: clamp(25px, 4vh, 32px); border-radius: 50%; background: var(--bg-main);
            display: flex; justify-content: center; align-items: center; color: var(--text-main);
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.5); transition: 0.4s; font-size: clamp(0.8rem, 1.5vh, 1rem);
        }
        .widget-text { font-size: clamp(0.6rem, 1.2vh, 0.7rem); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-secondary); }

        /* ================= 8. VOLUMETRIC ALERTS ================= */
        .quantum-toast {
            position: fixed; top: 3vh; right: -500px;
            padding: clamp(15px, 2vh, 20px) clamp(20px, 3vw, 25px); border-radius: 16px;
            background: var(--glass-surface); backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border); color: var(--text-main);
            display: flex; align-items: center; gap: 15px; box-shadow: var(--glass-shadow);
            transition: right 0.8s cubic-bezier(0.34, 1.56, 0.64, 1.2); z-index: 9999; transform-style: preserve-3d;
        }
        .quantum-toast.show { right: 4vw; }
        .toast-icon { font-size: clamp(1.5rem, 3vh, 1.8rem); transform: translateZ(20px); filter: drop-shadow(0 5px 5px rgba(0,0,0,0.5)); }
        
        .toast-success { border-bottom: 3px solid #00ff88; } .toast-success i { color: #00ff88; }
        .toast-error { border-bottom: 3px solid #ff3366; } .toast-error i { color: #ff3366; }

        /* ================= 9. PWA INSTALL MODAL & WIDGET ================= */
        .install-app-btn { right: auto; left: 4vw; border-color: rgba(212,175,55,0.3); }
        .install-app-btn:hover { border-color: #d4af37; }
        
        .install-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); z-index: 99999; display: flex; justify-content: center; align-items: center; opacity: 0; pointer-events: none; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        [data-theme="light"] .install-modal { background: rgba(255,255,255,0.7); }
        .install-modal.active { opacity: 1; pointer-events: auto; }
        .install-content { background: var(--glass-surface); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); padding: 30px; border-radius: 20px; max-width: 400px; width: 90%; transform: translateY(50px); transition: 0.5s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: var(--glass-shadow); }
        .install-modal.active .install-content { transform: translateY(0); }
        .install-title { color: #d4af37; font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 25px; text-transform: uppercase; text-align: center; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 15px; }
        .install-step { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-start; text-align: left; }
        .step-icon { width: 40px; height: 40px; border-radius: 50%; background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.4); display: flex; justify-content: center; align-items: center; color: #d4af37; font-size: 1.2rem; flex-shrink: 0; }
        .step-text h4 { color: var(--text-main); font-size: 0.95rem; margin-bottom: 5px; font-family: 'Playfair Display', serif; }
        .step-text p { color: var(--text-muted); font-size: 0.75rem; line-height: 1.5; }

        @media (max-width: 600px) {
            .quantum-toast.show { right: 20px; left: 20px; }
            .widget-text { display: none; }
        }
    </style>
</head>
<body data-theme="dark">

    <div class="ambient-field" id="ambientField">
        <div class="core-orb orb-1"></div>
        <div class="core-orb orb-2"></div>
        <div class="core-orb orb-3"></div>
    </div>
    
    <canvas id="particles-js"></canvas>

    <button class="toggle-widget" id="btnTheme" title="Ganti Mode">
        <div class="icon-orb"><i class="fa-solid fa-moon" id="toggleIcon"></i></div>
        <span class="widget-text" id="themeText">Malam</span>
    </button>

    <!-- Tombol Install Melayang (Top Left) -->
    <button class="toggle-widget install-app-btn" onclick="openInstallModal()" title="Panduan Install">
        <div class="icon-orb" style="color:#d4af37; background: rgba(212,175,55,0.1);"><i class="fa-solid fa-download"></i></div>
        <span class="widget-text" style="color:#d4af37;">Install App</span>
    </button>

    <?php if(session()->getFlashdata('success')): ?>
        <div id="toastAlert" class="quantum-toast toast-success show">
            <div class="toast-icon"><i class="fa-solid fa-check-double"></i></div>
            <div style="transform: translateZ(10px);">
                <strong style="font-family:'Playfair Display', serif; font-size:1rem;">Akses Diverifikasi</strong><br>
                <span style="font-size:0.8rem; color:var(--text-secondary);"><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div id="toastAlert" class="quantum-toast toast-error show">
            <div class="toast-icon"><i class="fa-solid fa-shield-virus"></i></div>
            <div style="transform: translateZ(10px);">
                <strong style="font-family:'Playfair Display', serif; font-size:1rem;">Akses Ditolak</strong><br>
                <span style="font-size:0.8rem; color:var(--text-secondary);"><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="scene-wrapper" id="sceneWrapper">
        <div class="auth-prism" id="authPrism">
            
            <div class="prism-header">
                <div class="logo-container" id="logoContainer">
                    <img src="/images/kristal-puncak.png" class="logo-part part-1" alt="Part">
                    <img src="/images/tanduk-perak.png" class="logo-part part-2" alt="Part">
                    <img src="/images/zamrud-hijau.png" class="logo-part part-3" alt="Part">
                    <img src="/images/cincin-emas.png" class="logo-part part-4" alt="Part">
                    <img src="/images/mahkota-emas.png" class="logo-part part-5" alt="Part">
                    
                    <img src="/images/logo-utuh.png" class="logo-utuh" alt="Expedient Logo" id="logoUtuh">
                </div>
                <div class="subtitle-spec">Expedient Generation</div>
                <h1 class="title-holo">Portal Utama</h1>
            </div>

            <form action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="input-group">
                    <input type="email" name="email" id="email" class="input-control" required placeholder=" ">
                    <label for="email" class="input-label">Surel Resmi</label>
                    <div class="input-neon-line"></div>
                </div>
                
                <div class="input-group">
                    <input type="password" id="inputPw" name="password" class="input-control" required placeholder=" ">
                    <label for="inputPw" class="input-label">Kata Sandi Akses</label>
                    <div class="input-neon-line"></div>
                    <i class="fa-solid fa-eye icon-eye" id="togglePw"></i>
                </div>
                
                <div style="text-align:right;margin-top:-10px;margin-bottom:5px;transform:translateZ(25px);">
                    <a href="<?= base_url('auth/forgot-password') ?>" style="color:var(--text-muted);font-size:clamp(0.7rem,1.3vh,0.8rem);text-decoration:none;transition:0.3s;border-bottom:1px solid transparent;" onmouseover="this.style.color='#d4af37';this.style.borderColor='#d4af37'" onmouseout="this.style.color='var(--text-muted)';this.style.borderColor='transparent'">
                        <i class="fa-solid fa-key" style="font-size:0.65rem;margin-right:4px;"></i>Lupa Kata Sandi?
                    </a>
                </div>

                <div class="btn-rack">
                    <div class="magnetic-wrap">
                        <button type="submit" class="btn-prime magnetic-btn" id="btnPrime">
                            Inisiasi Masuk <i class="fa-solid fa-arrow-right-long"></i>
                        </button>
                    </div>

                    <div class="divider"><hr><span>ALTERNATIF</span><hr></div>
                    
                    <div class="magnetic-wrap">
                        <button type="button" class="btn-bio magnetic-btn" id="btnBiometricLogin">
                            <i class="fa-solid fa-fingerprint" style="font-size: 1.1rem;"></i> Pemindaian Biometrik
                        </button>
                    </div>
                </div>
            </form>

            <div class="register-link">
                Identitas belum terdaftar? <a href="<?= base_url('auth/register') ?>">Ajukan Registrasi</a>
            </div>

        </div>
    </div>

    <!-- Modal Panduan Instalasi -->
    <div class="install-modal" id="installModal">
        <div class="install-content">
            <h2 class="install-title">Instalasi VVIP App</h2>
            
            <div class="install-step">
                <div class="step-icon"><i class="fa-brands fa-android"></i></div>
                <div class="step-text">
                    <h4>Android (Chrome)</h4>
                    <p>Ketuk ikon <b>Titik Tiga</b> di pojok kanan atas browser, lalu pilih <b>"Tambahkan ke Layar Utama"</b> (Add to Home screen).</p>
                </div>
            </div>

            <div class="install-step">
                <div class="step-icon"><i class="fa-brands fa-apple"></i></div>
                <div class="step-text">
                    <h4>iOS / iPhone (Safari)</h4>
                    <p>Ketuk ikon <b>Bagikan/Share</b> (kotak dengan panah) di bawah layar, geser ke bawah, lalu pilih <b>"Tambah ke Layar Utama"</b>.</p>
                </div>
            </div>

            <div class="install-step">
                <div class="step-icon"><i class="fa-solid fa-desktop"></i></div>
                <div class="step-text">
                    <h4>PC / Laptop</h4>
                    <p>Perhatikan sisi kanan kolom URL/Link di atas, klik ikon <b>Install</b> (layar dengan tanda panah bawah).</p>
                </div>
            </div>

            <button type="button" class="btn-prime magnetic-btn" style="margin-top:20px; width:100%; border-radius:10px; font-size:0.8rem;" onclick="closeInstallModal()">SAYA MENGERTI</button>
        </div>
    </div>

    <script>
        // Modal Install Logic
        function openInstallModal() { document.getElementById('installModal').classList.add('active'); if (navigator.vibrate) navigator.vibrate(20); }
        function closeInstallModal() { document.getElementById('installModal').classList.remove('active'); }

        // 1. EXPLODING LOGO LOGIC
        const logoContainer = document.getElementById('logoContainer');
        
        function triggerLogoExplosion() {
            // State 1: Explode (Pecah)
            logoContainer.classList.remove('united');
            logoContainer.classList.add('exploding');
            
            // State 2: Unite (Gabung kembali setelah 1 detik)
            setTimeout(() => {
                logoContainer.classList.remove('exploding');
                logoContainer.classList.add('united');
            }, 1000);
        }

        // Jalankan saat pertama kali halaman dimuat
        window.addEventListener('load', triggerLogoExplosion);

        // 2. 3D TILT & PARALLAX
        const wrapper = document.getElementById('sceneWrapper');
        const prism = document.getElementById('authPrism');
        
        wrapper.addEventListener('mousemove', (e) => {
            if (window.innerWidth > 768) {
                const rect = wrapper.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                prism.style.setProperty('--mouse-x', `${x}px`);
                prism.style.setProperty('--mouse-y', `${y}px`);

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = ((y - centerY) / centerY) * -4;
                const rotateY = ((x - centerX) / centerX) * 4;

                prism.style.transform = `perspective(2000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            }
        });
        
        wrapper.addEventListener('mouseleave', () => { 
            prism.style.transform = `perspective(2000px) rotateX(0deg) rotateY(0deg)`; 
        });

        // 3. MAGNETIC BUTTONS
        const magneticBtns = document.querySelectorAll('.magnetic-btn');
        magneticBtns.forEach(btn => {
            btn.addEventListener('mousemove', function(e) {
                if (window.innerWidth > 768) {
                    const rect = this.getBoundingClientRect();
                    const h = rect.width / 2;
                    const v = rect.height / 2;
                    const x = e.clientX - rect.left - h;
                    const y = e.clientY - rect.top - v;
                    this.style.transition = 'none';
                    this.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
                }
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.2)';
                this.style.transform = `translate(0px, 0px)`;
            });
        });

        // 4. INTELLIGENT PARTICLES
        const canvas = document.getElementById("particles-js");
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth; canvas.height = window.innerHeight;
        let particlesArray = [];
        let mouse = { x: null, y: null, radius: 100 };

        window.addEventListener('mousemove', function(event){ mouse.x = event.x; mouse.y = event.y; });
        window.addEventListener('mouseleave', function(){ mouse.x = undefined; mouse.y = undefined; });

        class Particle {
            constructor(x, y, size, weight) { this.x = x; this.y = y; this.size = size; this.weight = weight; }
            update() {
                this.y -= this.weight;
                if (this.y < 0 - this.size) { this.y = canvas.height + this.size; this.x = Math.random() * canvas.width; }
                if (mouse.x != null) {
                    let dx = mouse.x - this.x; let dy = mouse.y - this.y; let distance = Math.sqrt(dx*dx + dy*dy);
                    if (distance < mouse.radius) {
                        const forceDirectionX = dx / distance; const forceDirectionY = dy / distance;
                        const maxDistance = mouse.radius; const force = (maxDistance - distance) / maxDistance;
                        this.x -= forceDirectionX * force * 5; this.y -= forceDirectionY * force * 5;
                    }
                }
            }
            draw() {
                const isLight = document.body.getAttribute('data-theme') === 'light';
                ctx.fillStyle = isLight ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.2)';
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill();
            }
        }
        const isMobileDevice = window.innerWidth <= 768;
        const PARTICLE_COUNT = isMobileDevice ? 20 : 70;
        function initParticles() {
            particlesArray = [];
            for (let i = 0; i < PARTICLE_COUNT; i++) {
                particlesArray.push(new Particle(Math.random() * innerWidth, Math.random() * innerHeight, (Math.random() * 2) + 0.5, (Math.random() * 0.5) + 0.2));
            }
        }
        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) { particlesArray[i].update(); particlesArray[i].draw(); }
            requestAnimationFrame(animateParticles);
        }
        initParticles(); animateParticles();
        window.addEventListener('resize', function(){ canvas.width = innerWidth; canvas.height = innerHeight; initParticles(); });

        // 5. THEME TOGGLE + LOGO EXPLOSION HAPTIC
        const btnTheme = document.getElementById('btnTheme');
        const themeText = document.getElementById('themeText');
        const toggleIcon = document.getElementById('toggleIcon');

        btnTheme.addEventListener('click', () => {
            // Haptic Feedback for Mobile (Fitur Mobile Eksklusif 1)
            if (navigator.vibrate) navigator.vibrate(50);
            
            // Ledakkan logo kembali
            triggerLogoExplosion();

            const isDark = document.body.getAttribute('data-theme') === 'dark';
            
            // Particle Burst
            for(let i=0; i<20; i++) { particlesArray.push(new Particle(window.innerWidth - 50, 50, Math.random()*3+1, -(Math.random()*4+2))); }

            document.body.setAttribute('data-theme', isDark ? 'light' : 'dark');
            themeText.innerText = isDark ? 'Siang' : 'Malam';
            toggleIcon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        });

        // 6. EXCLUSIVE MOBILE FEATURES
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            // A. Smooth Gyroscope Parallax 2.0 (Throttled for Performance)
            let currentX = 0, currentY = 0, targetX = 0, targetY = 0;
            let gyroActive = false;
            if (window.DeviceOrientationEvent) {
                window.addEventListener('deviceorientation', (e) => {
                    if (e.gamma === null) return;
                    targetY = Math.max(-3, Math.min(3, e.gamma / 90 * 3));
                    targetX = Math.max(-3, Math.min(3, (e.beta - 45) / 90 * -3));
                    if (!gyroActive) { gyroActive = true; smoothGyro(); }
                });
                function smoothGyro() {
                    currentX += (targetX - currentX) * 0.08;
                    currentY += (targetY - currentY) * 0.08;
                    prism.style.transform = `perspective(2000px) rotateX(${currentX}deg) rotateY(${currentY}deg)`;
                    requestAnimationFrame(smoothGyro);
                }
            }

            // B. Swipe-to-Reveal Ambient (Fitur Mobile Eksklusif 3)
            let touchStartY = 0;
            const ambient = document.getElementById('ambientField');
            document.addEventListener('touchstart', e => touchStartY = e.touches[0].clientY);
            document.addEventListener('touchend', e => {
                const touchEndY = e.changedTouches[0].clientY;
                if (touchStartY - touchEndY > 50) ambient.style.transform = 'scale(1.2)'; // Swipe Up (Lebih terang)
                if (touchEndY - touchStartY > 50) ambient.style.transform = 'scale(0.8)'; // Swipe Down (Meredup)
            });

            // C. Touch Ripple Effect (Fitur Mobile Eksklusif 4)
            prism.addEventListener('touchstart', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.touches[0].clientX - rect.left;
                const y = e.touches[0].clientY - rect.top;
                const ripple = document.createElement('div');
                ripple.classList.add('touch-ripple');
                ripple.style.left = `${x}px`; ripple.style.top = `${y}px`;
                ripple.style.width = ripple.style.height = '50px';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });

            // D. Smart Keyboard Auto-Lift (Fitur Mobile Eksklusif 5)
            const inputs = document.querySelectorAll('.input-control');
            inputs.forEach(input => {
                input.addEventListener('focus', () => { prism.style.transform = `translateY(-15vh)`; });
                input.addEventListener('blur', () => { prism.style.transform = `translateY(0)`; });
            });
        }

        // 7. HIDE PASSWORD & ALERTS
        document.getElementById('togglePw').addEventListener('click', function() {
            const pw = document.getElementById('inputPw');
            pw.type = pw.type === 'password' ? 'text' : 'password';
            this.classList.toggle('fa-eye'); this.classList.toggle('fa-eye-slash');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const toastA = document.getElementById('toastAlert');
            if(toastA) { setTimeout(() => toastA.classList.remove('show'), 6000); }
        });

        // 8. REFACTORED BIOMETRIC LOGIC (BUG FIXED)
        function base64urlToBuffer(base64url) {
            const padding = '='.repeat((4 - base64url.length % 4) % 4);
            const base64 = (base64url + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) { outputArray[i] = rawData.charCodeAt(i); }
            return outputArray.buffer;
        }

        function bufferToBase64url(buffer) {
            const bytes = new Uint8Array(buffer);
            let binary = '';
            for (let i = 0; i < bytes.byteLength; i++) { binary += String.fromCharCode(bytes[i]); }
            return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
        }

        document.getElementById('btnBiometricLogin').addEventListener('click', async () => {
            if (navigator.vibrate) navigator.vibrate(50); // Haptic
            try {
                const btn = document.getElementById('btnBiometricLogin');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Proses...';
                
                const res = await fetch('/api/biometric/login-options');
                const opt = await res.json();
                if (opt.error) { alert(opt.error); btn.innerHTML = originalText; return; }

                opt.challenge = base64urlToBuffer(opt.challenge);
                const assertion = await navigator.credentials.get({ publicKey: opt });
                
                const assertionData = {
                    id: assertion.id, rawId: bufferToBase64url(assertion.rawId), type: assertion.type,
                    response: {
                        authenticatorData: bufferToBase64url(assertion.response.authenticatorData),
                        clientDataJSON: bufferToBase64url(assertion.response.clientDataJSON),
                        signature: bufferToBase64url(assertion.response.signature)
                    }
                };
                
                const verifyRes = await fetch('/api/biometric/login-verify', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(assertionData) });
                const result = await verifyRes.json();
                
                if (result.status === 'success') { window.location.href = result.redirect; } 
                else { alert(result.error); btn.innerHTML = originalText; }
            } catch (err) {
                console.error(err);
                alert('Otentikasi biometrik dibatalkan atau perangkat tidak didukung.');
                document.getElementById('btnBiometricLogin').innerHTML = '<i class="fa-solid fa-fingerprint"></i> Pemindaian Biometrik';
            }
        });
    </script>
</body>
</html>