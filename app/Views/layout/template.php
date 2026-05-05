<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $this->renderSection('title') ?> - Expedient Generation</title>
    
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="theme-color" content="#030504">
    <meta name="description" content="Museum Galeri Digital VVIP & Arsip Direktori Expedient Generation.">
    <meta property="og:title" content="<?= $this->renderSection('title') ?> - Expedient Generation">
    <meta property="og:description" content="Akses portal eksklusif peninggalan dan jejak langkah Expedient.">
    <meta property="og:image" content="<?= base_url('images/logo-utuh.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        const savedTheme = localStorage.getItem('expedient_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <style>
        /* ================= 1. DUAL THEME VARIABLES ================= */
        :root {
            --bg-main: #030504; --bg-radial: #0a110e;
            --aurora-1: rgba(0, 255, 136, 0.08); --aurora-2: rgba(0, 162, 255, 0.06); --aurora-3: rgba(212, 175, 55, 0.05);
            --glass-bg: rgba(255, 255, 255, 0.02); --glass-blur: blur(40px) saturate(150%);
            --glass-border: rgba(255, 255, 255, 0.08); --glass-shadow: 0 30px 60px rgba(0,0,0,0.4);
            --text-primary: #ffffff; --text-secondary: #8b9ba8;
            --awwwards-ease: cubic-bezier(0.83, 0, 0.17, 1);
        }
        :root[data-theme="light"] {
            --bg-main: #f8faf9; --bg-radial: #ffffff;
            --aurora-1: rgba(0, 150, 255, 0.06); --aurora-2: rgba(255, 150, 180, 0.06); --aurora-3: rgba(212, 175, 55, 0.1);
            --glass-bg: rgba(255, 255, 255, 0.6); --glass-border: rgba(212, 175, 55, 0.3); --glass-shadow: 0 30px 60px rgba(0,20,40,0.08);
            --text-primary: #0f1714; --text-secondary: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: transparent; } ::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.3); border-radius: 10px; }
        
        body { background-color: var(--bg-main); background-image: radial-gradient(circle at 50% 50%, var(--bg-radial), transparent 80%); color: var(--text-primary); height: 100vh; overflow: hidden; transition: background 1s ease, color 0.5s ease; cursor: none; }

        /* ================= 2. KEMEWAHAN: FILM GRAIN & MAGNETIC CURSOR ================= */
        .film-grain { position: fixed; inset: 0; pointer-events: none; z-index: 99999; opacity: 0.04; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E"); }
        [data-theme="light"] .film-grain { opacity: 0.05; filter: invert(1); }

        @media (pointer: fine) {
            .cursor-dot { position: fixed; top: 0; left: 0; width: 6px; height: 6px; background: #d4af37; border-radius: 50%; pointer-events: none; z-index: 999999; transform: translate(-50%, -50%); transition: width 0.2s, height 0.2s; }
            .cursor-ring { position: fixed; top: 0; left: 0; width: 36px; height: 36px; border: 1px solid rgba(212,175,55,0.6); border-radius: 50%; pointer-events: none; z-index: 999998; transform: translate(-50%, -50%); transition: width 0.3s, height 0.3s, background 0.3s, border-color 0.3s; transition-timing-function: ease-out; }
            body.cursor-hovering .cursor-dot { width: 0; height: 0; opacity: 0; }
            body.cursor-hovering .cursor-ring { width: 60px; height: 60px; background: rgba(212,175,55,0.15); border-color: rgba(212,175,55,0.8); backdrop-filter: blur(4px); }
        }
        @media (pointer: coarse) { body { cursor: auto; } .cursor-dot, .cursor-ring { display: none; } }

        /* ================= 3. BACKGROUND ENGINE ================= */
        .aurora-container { position: fixed; inset: -30%; z-index: 0; pointer-events: none; filter: blur(30px); transition: opacity 1s; }
        .aurora-blob { position: absolute; border-radius: 50%; opacity: 0.9; mix-blend-mode: screen; animation: morphBlob 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); }
        :root[data-theme="light"] .aurora-blob { mix-blend-mode: multiply; opacity: 0.7; }
        .blob-1 { width: 55vw; height: 55vw; background: var(--aurora-1); top: -10%; left: 0; transform-origin: center right; }
        .blob-2 { width: 65vw; height: 65vw; background: var(--aurora-2); bottom: -10%; right: -10%; transform-origin: center left; animation-delay: -7s; }
        .blob-3 { width: 45vw; height: 45vw; background: var(--aurora-3); top: 40%; left: 30%; transform-origin: bottom center; animation-delay: -14s; }
        @keyframes morphBlob { 0% { transform: scale(1) translate(0, 0) rotate(0deg); } 100% { transform: scale(1.4) translate(120px, -80px) rotate(90deg); } }
        #particles-js { position: fixed; inset: 0; z-index: 1; pointer-events: none; }

        /* ================= 4. THE 10 CELESTIAL LOADERS ================= */
        #loadingScreen { position: fixed; inset: 0; background: var(--bg-main); z-index: 999990; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.8s; }
        .loader-logo-wrap { position: relative; width: 220px; height: 220px; display: flex; justify-content: center; align-items: center; margin-bottom: 40px; }
        .loader-logo { width: 130px; filter: drop-shadow(0 0 25px rgba(212,175,55,0.8)); position: relative; z-index: 10; mix-blend-mode: screen; animation: fadeInScale 1s ease-out forwards; }
        :root[data-theme="light"] .loader-logo { mix-blend-mode: normal; filter: drop-shadow(0 0 25px rgba(212,175,55,0.4)); }
        
        @keyframes fadeInScale { 0% { opacity: 0; transform: scale(0.8); } 100% { opacity: 1; transform: scale(1); } }
        @keyframes spinFull { 100% { transform: rotate(360deg); } }

        .load-v1 .pure-ripple { position: absolute; width: 150px; height: 150px; border: 2px solid #d4af37; border-radius: 50%; opacity: 0; animation: pureRipple 2.5s infinite ease-out; } .load-v1 .pure-ripple:nth-child(2) { animation-delay: 1.25s; } @keyframes pureRipple { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(2.2); opacity: 0; } }
        .load-v2 { perspective: 1200px; } .load-v2 .cosmos-orbit { position: absolute; width: 200px; height: 200px; border: 1px solid rgba(212,175,55,0.3); border-radius: 50%; animation: cosmosSpin 4s infinite linear; } .load-v2 .orbit-1 { animation-duration: 3s; } .load-v2 .orbit-2 { animation-duration: 5s; animation-direction: reverse; } @keyframes cosmosSpin { 0% { transform: rotateX(70deg) rotateY(20deg) rotateZ(0deg); } 100% { transform: rotateX(70deg) rotateY(20deg) rotateZ(360deg); } }
        .load-v3 .loader-logo { filter: grayscale(1); opacity: 0.1; } .load-v3 .gilded-reveal { font-family: 'Playfair Display', serif; color: #d4af37; font-size: 1.2rem; letter-spacing: 5px; text-transform: uppercase; animation: shimmer 2s infinite linear; } @keyframes shimmer { 0% { opacity: 0.3; } 50% { opacity: 1; text-shadow: 0 0 15px #d4af37; } 100% { opacity: 0.3; } }
        .load-v4 .loader-logo { animation: fadeInScale 1s ease-out forwards, bloom 2s ease-in-out 1s infinite alternate; } @keyframes bloom { 0% { filter: drop-shadow(0 0 10px rgba(212,175,55,0.4)); } 100% { filter: drop-shadow(0 0 40px rgba(212,175,55,1)); } }
        .load-v5 .loader-logo { z-index: 1; } .load-v5 .lunar-eclipse { position: absolute; width: 140px; height: 140px; background: var(--bg-main); border-radius: 50%; z-index: 5; box-shadow: -20px 0 30px #d4af37; animation: lunarEclipse 4s infinite ease-in-out; } :root[data-theme="light"] .load-v5 .lunar-eclipse { box-shadow: -20px 0 30px #aa771c; } @keyframes lunarEclipse { 0% { left: -120%; box-shadow: 20px 0 30px #d4af37; } 50% { left: 20px; box-shadow: 0 0 40px #d4af37; } 100% { left: 120%; box-shadow: -20px 0 30px #d4af37; } }
        .load-v6 .solar-crown { position: absolute; inset: -25px; border-radius: 50%; background: conic-gradient(from 0deg, transparent 70%, rgba(212,175,55,0.6) 100%); animation: spinFull 2s linear infinite; border: 2px solid rgba(212,175,55,0.1); }
        .load-v7 .sacred-lattice { position: absolute; width: 160px; height: 160px; border: 1px solid rgba(212,175,55,0.2); animation: latticePulse 3s infinite alternate; opacity: 0; } @keyframes latticePulse { 0% { opacity: 0.1; } 100% { opacity: 0.7; transform: scale(1.1); } }
        .load-v8 .prismatic-pulse { position: absolute; width: 180px; height: 180px; border: 2px solid #00ff88; animation: prismRipple 3s infinite ease-out; opacity: 0; } :root[data-theme="light"] .load-v8 .prismatic-pulse { border-color: #00a2ff; } @keyframes prismRipple { 0% { transform: scale(0.8); opacity: 1; filter: hue-rotate(0deg); } 100% { transform: scale(2.5); opacity: 0; filter: hue-rotate(360deg); } }
        .load-v9 .flowing-veils { position: absolute; bottom: -60px; display: flex; gap: 10px; height: 35px; align-items: center; justify-content: center; } .load-v9 .veil { width: 7px; background: #d4af37; border-radius: 4px; animation: veilFlow 1.2s infinite alternate ease-in-out; box-shadow: 0 0 15px #d4af37; } @keyframes veilFlow { 0% { transform: scaleY(0.5); opacity: 0.4; } 100% { transform: scaleY(1.3); opacity: 1; } }
        .load-v10 { perspective: 1500px; } .load-v10 .stellar-orbit { position: absolute; width: 250px; height: 90px; border: 1px solid rgba(212,175,55,0.3); border-radius: 50%; animation: spinX 4s infinite linear; } .load-v10 .stellar-orbit:nth-child(2) { animation: spinY 4s infinite linear; } .load-v10 .stellar-dot { position: absolute; width: 12px; height: 12px; background: #fff; border-radius: 50%; box-shadow: 0 0 20px #fff; top: -6px; left: 50%; transform: translateX(-50%); } :root[data-theme="light"] .load-v10 .stellar-dot { background: #d4af37; box-shadow: 0 0 20px #d4af37; }

        /* ================= 5. AWWWARDS LEVEL FLOATING SIDEBAR (DI-UPGRADE) ================= */
        .sidebar { 
            position: fixed; top: 20px; left: 20px; bottom: 20px; width: 75px; /* Dipersempit agar elegan */
            background: linear-gradient(135deg, var(--glass-bg) 0%, rgba(255,255,255,0.01) 100%);
            backdrop-filter: var(--glass-blur); -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border); border-radius: 20px;
            display: flex; flex-direction: column; align-items: center; padding: 25px 0; gap: 20px; 
            z-index: 100; box-shadow: var(--glass-shadow); 
            transition: transform 0.8s var(--awwwards-ease), opacity 0.6s var(--awwwards-ease), width 0.8s var(--awwwards-ease);
        }
        
        .nav-item { 
            width: 45px; height: 45px; border-radius: 14px; display: flex; justify-content: center; align-items: center; 
            color: var(--text-secondary); font-size: 1.15rem; cursor: pointer; position: relative; text-decoration: none; 
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 1; transform: translateY(0);
        }
        .nav-item::after { 
            content: attr(data-tooltip); position: absolute; left: 60px; background: var(--glass-bg); 
            backdrop-filter: var(--glass-blur); padding: 8px 16px; border-radius: 10px; 
            border: 1px solid var(--glass-border); font-size: 0.8rem; font-weight: 600; color: var(--text-primary); 
            opacity: 0; pointer-events: none; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); white-space: nowrap; 
            transform: translateX(-15px) scale(0.95); box-shadow: var(--glass-shadow); font-family: 'Inter', sans-serif; letter-spacing: 1px; 
        }
        .nav-item:hover, .nav-item.active { 
            background: rgba(212,175,55,0.15); color: #d4af37; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.15), inset 0 0 0 1px rgba(212,175,55,0.5); 
            transform: translateY(-4px); 
        }
        .nav-item:hover::after { opacity: 1; transform: translateX(0) scale(1); }

        /* Staggered Animation for Nav Items */
        .sidebar .nav-item:nth-child(1) { transition-delay: 0.05s; }
        .sidebar .nav-item:nth-child(2) { transition-delay: 0.10s; }
        .sidebar .nav-item:nth-child(3) { transition-delay: 0.15s; }
        .sidebar .nav-item:nth-child(4) { transition-delay: 0.20s; }
        .sidebar .nav-item:nth-child(5) { transition-delay: 0.25s; }
        .sidebar .nav-item:nth-child(6) { transition-delay: 0.30s; }
        .sidebar .nav-item:nth-child(8) { transition-delay: 0.35s; }

        /* Sidebar Closed State (Desktop) */
        body.sidebar-closed .sidebar { transform: translateX(-150px); opacity: 0; pointer-events: none; }
        body.sidebar-closed .sidebar .nav-item { opacity: 0; transform: translateY(20px); transition-delay: 0s !important; }

        /* ================= KEMEWAHAN BARU: EDGE-SNAP TOGGLE (2 GARIS) ================= */
        .menu-toggle {
            position: fixed; top: 50%; left: 0; z-index: 110;
            transform: translateY(-50%);
            background: linear-gradient(135deg, var(--glass-bg) 0%, rgba(212,175,55,0.05) 100%);
            backdrop-filter: var(--glass-blur); -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border); border-left: none; /* Hilangkan border kiri agar menempel */
            border-radius: 0 16px 16px 0; /* Melengkung di kanan saja */
            width: 40px; height: 75px; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 6px;
            cursor: pointer;
            transition: all 0.6s var(--awwwards-ease); 
            box-shadow: 5px 0 20px rgba(0,0,0,0.15);
        }
        /* Garis Minimalis (Asimetris) */
        .menu-toggle .line {
            width: 18px; height: 2px; background: var(--text-primary); border-radius: 2px; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .menu-toggle .line.short { width: 10px; align-self: flex-start; margin-left: 11px; } /* Garis bawah lebih pendek */
        
        /* Interaksi Hover Mewah */
        .menu-toggle:hover { 
            width: 48px; border-color: rgba(212,175,55,0.5); background: rgba(212,175,55,0.15); 
            box-shadow: 10px 0 25px rgba(212, 175, 55, 0.2); 
        }
        .menu-toggle:hover .line { background: #d4af37; }
        .menu-toggle:hover .line.short { width: 18px; margin-left: 0; } /* Garis memanjang saat dihover */

        /* Sembunyikan toggle dengan mulus saat sidebar terbuka */
        body:not(.sidebar-closed) .menu-toggle { opacity: 0; transform: translateY(-50%) translateX(-100%); pointer-events: none; }

        /* Main Wrapper Dynamic Adjustments */
        .main-wrapper { 
            position: absolute; top: 0; left: 115px; /* Disesuaikan dengan lebar sidebar baru */
            right: 0; bottom: 0; z-index: 10; overflow-y: auto; overflow-x: hidden; scroll-behavior: smooth;
            transition: left 0.8s var(--awwwards-ease);
        }
        body.sidebar-closed .main-wrapper { left: 0; }

        /* ================= 6. THE AEGIS NOTIFICATION ================= */
        .aegis-toast { position: fixed; bottom: 40px; right: -450px; width: 380px; background: var(--glass-bg); backdrop-filter: blur(25px); border: 1px solid var(--glass-border); border-left: 4px solid #d4af37; padding: 25px; display: flex; align-items: center; gap: 20px; box-shadow: 0 30px 60px rgba(0,0,0,0.5); z-index: 99999; transition: transform 0.8s var(--awwwards-ease), opacity 0.8s; opacity: 0; pointer-events: none; overflow: hidden; border-radius: 12px; }
        .aegis-toast.show { transform: translateX(-490px); opacity: 1; pointer-events: auto; }
        .aegis-toast::after { content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%; background: linear-gradient(to right, transparent, rgba(212,175,55,0.15), transparent); transform: skewX(-20deg); }
        .aegis-toast.show::after { animation: shimmerToast 2.5s ease-out forwards 0.5s; }
        @keyframes shimmerToast { 100% { left: 200%; } }
        .aegis-icon { color: #d4af37; font-size: 2rem; filter: drop-shadow(0 0 10px rgba(212,175,55,0.4)); animation: pulseAegis 3s infinite alternate; }
        @keyframes pulseAegis { 0% { transform: scale(0.9); opacity: 0.8; } 100% { transform: scale(1.1); opacity: 1; } }
        .aegis-content { display: flex; flex-direction: column; }
        .aegis-title { font-family: 'Courier New', monospace; font-size: 0.7rem; color: var(--text-secondary); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 5px; }
        .aegis-name { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--text-primary); font-weight: 700; line-height: 1.1; }

        /* ================= 7. THEME WIDGET ================= */
        .theme-widget { position: fixed; top: 30px; right: 40px; z-index: 100; background: linear-gradient(135deg, var(--glass-bg) 0%, rgba(255,255,255,0.01) 100%); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-border); border-radius: 40px; padding: 6px 16px 6px 6px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.4s; box-shadow: var(--glass-shadow); color: var(--text-primary); }
        .theme-widget:hover { transform: translateY(-3px) scale(1.05); border-color: #d4af37; }
        .icon-orb { width: 35px; height: 35px; border-radius: 50%; background: var(--bg-main); display: flex; justify-content: center; align-items: center; color: var(--text-primary); box-shadow: inset 0 2px 5px rgba(0,0,0,0.5); transition: 0.4s; }
        :root[data-theme="light"] .icon-orb { box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); }
        .theme-widget:hover .icon-orb { background: var(--text-primary); color: var(--bg-main); }
        .widget-text { font-size: 0.75rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--text-secondary); transition: 0.4s; }

        /* ================= 8. MOBILE RESPONSIVENESS ================= */
        @media (max-width: 768px) {
            .sidebar { 
                top: auto; bottom: 20px; left: 20px; right: 20px; width: calc(100vw - 40px); height: 70px; 
                flex-direction: row; padding: 0 15px; justify-content: space-around;
            }
            .nav-item { width: 40px; height: 40px; font-size: 1.1rem; margin: 0; }
            .nav-item::after { display: none; }
            .nav-spacer { display: none; }
            
            .main-wrapper { left: 0; bottom: 100px; transition: bottom 0.8s var(--awwwards-ease); }
            
            /* Sidebar Closed State (Mobile) */
            body.sidebar-closed .sidebar { transform: translateY(150px); opacity: 0; }
            body.sidebar-closed .main-wrapper { bottom: 0; height: 100vh; }
            
            /* Floating Toggle Button (Mobile - Nempel di bawah) */
            .menu-toggle { 
                top: auto; bottom: 0; left: 50%; transform: translateX(-50%); 
                width: 75px; height: 35px; 
                border-radius: 16px 16px 0 0; /* Melengkung di atas saja */
                border: 1px solid var(--glass-border); border-bottom: none; 
                flex-direction: column; gap: 5px; 
            }
            .menu-toggle .line.short { align-self: center; margin-left: 0; width: 10px; } /* Di tengah untuk mobile */
            .menu-toggle:hover { width: 85px; height: 40px; }
            .menu-toggle:hover .line.short { width: 18px; }

            body:not(.sidebar-closed) .menu-toggle { transform: translateX(-50%) translateY(100%); opacity: 0; }
            body.sidebar-closed .menu-toggle { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }

            .theme-widget { top: 20px; right: 20px; padding: 6px; } .widget-text { display: none; }
            .aegis-toast { width: 90%; right: auto; left: 5%; bottom: 110px; border-radius: 12px; border-left: 1px solid var(--glass-border); border-bottom: 4px solid #d4af37; }
            .aegis-toast.show { transform: translateX(0); }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <div class="film-grain"></div>
    <div class="cursor-dot" id="cursorDot"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <?php 
        $loaders = ['v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7', 'v8', 'v9', 'v10'];
        $selectedLoader = $loaders[array_rand($loaders)];
    ?>

    <div id="loadingScreen" class="load-<?= $selectedLoader ?>">
        <div class="loader-logo-wrap">
            <?php if($selectedLoader == 'v1'): ?><div class="pure-ripple"></div><div class="pure-ripple"></div>
            <?php elseif($selectedLoader == 'v2'): ?><div class="cosmos-orbit orbit-1"></div><div class="cosmos-orbit orbit-2"></div>
            <?php elseif($selectedLoader == 'v5'): ?><div class="lunar-eclipse"></div>
            <?php elseif($selectedLoader == 'v6'): ?><div class="solar-crown"></div>
            <?php elseif($selectedLoader == 'v7'): ?><div class="sacred-lattice"></div>
            <?php elseif($selectedLoader == 'v8'): ?><div class="prismatic-pulse"></div>
            <?php elseif($selectedLoader == 'v9'): ?><div class="flowing-veils"><div class="veil"></div><div class="veil"></div><div class="veil"></div><div class="veil"></div><div class="veil"></div></div>
            <?php elseif($selectedLoader == 'v10'): ?><div class="stellar-orbit"><div class="stellar-dot"></div></div><div class="stellar-orbit reverse"><div class="stellar-dot"></div></div>
            <?php endif; ?>
            <img src="/images/logo-utuh.png" class="loader-logo" alt="Loading Expedient">
        </div>
        <?php if($selectedLoader == 'v3'): ?><div class="gilded-reveal">EXPEDIENT GENERATION</div><?php endif; ?>
    </div>

    <div class="aurora-container">
        <div class="aurora-blob blob-1"></div><div class="aurora-blob blob-2"></div><div class="aurora-blob blob-3"></div>
    </div>
    <canvas id="particles-js"></canvas>

    <div id="aegisToast" class="aegis-toast">
        <i class="fa-solid fa-compass-drafting aegis-icon"></i>
        <div class="aegis-content">
            <span class="aegis-title">Entitas Terdeteksi Masuk</span>
            <strong id="radarName" class="aegis-name">Seseorang</strong>
        </div>
    </div>

    <button class="menu-toggle hover-trigger" id="btnMenuOpen" title="Panggil Panel">
        <span class="line"></span>
        <span class="line short"></span>
    </button>

    <nav class="sidebar" id="sidebarNav">
        <a href="javascript:void(0)" class="nav-item hover-trigger" id="btnMenuClose" data-tooltip="Sembunyikan Panel" onclick="hapticNav()">
            <i class="fa-solid fa-compress"></i>
        </a>
        <a href="/beranda" class="nav-item hover-trigger <?= (uri_string() == 'beranda') ? 'active' : '' ?>" data-tooltip="Grand Exhibition" onclick="hapticNav()"><i class="fa-solid fa-landmark"></i></a>
        <a href="/direktori" class="nav-item hover-trigger <?= (uri_string() == 'direktori') ? 'active' : '' ?>" data-tooltip="The Registry" onclick="hapticNav()"><i class="fa-solid fa-address-book"></i></a>
        <a href="/galeri" class="nav-item hover-trigger <?= (uri_string() == 'galeri') ? 'active' : '' ?>" data-tooltip="The Vault" onclick="hapticNav()"><i class="fa-solid fa-film"></i></a>
        <a href="/radar" class="nav-item hover-trigger <?= (uri_string() == 'radar') ? 'active' : '' ?>" data-tooltip="Omnipresence" onclick="hapticNav()"><i class="fa-solid fa-earth-americas"></i></a>
        <a href="/syndicate" class="nav-item hover-trigger <?= (uri_string() == 'syndicate') ? 'active' : '' ?>" data-tooltip="The Council" onclick="hapticNav()"><i class="fa-solid fa-chess-knight"></i></a>
        <a href="/fitur" class="nav-item hover-trigger <?= (uri_string() == 'fitur') ? 'active' : '' ?>" data-tooltip="Fitur Eksekutif" onclick="hapticNav()"><i class="fa-solid fa-gem"></i></a>
        <div style="flex-grow: 1;" class="nav-spacer"></div>
        <a href="/profil" class="nav-item hover-trigger <?= (uri_string() == 'profil') ? 'active' : '' ?>" data-tooltip="Ruang Kendali" onclick="hapticNav()"><i class="fa-solid fa-user-astronaut"></i></a>
    </nav>

    <button class="theme-widget hover-trigger" id="btnTheme" title="Ganti Mode">
        <div class="icon-orb"><i class="fa-solid fa-moon" id="toggleIcon"></i></div>
        <span class="widget-text" id="themeText">Malam</span>
    </button>

    <main class="main-wrapper">
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        function hapticNav() { if (navigator.vibrate) navigator.vibrate(20); }

        // SIDEBAR TOGGLE LOGIC
        const btnMenuOpen = document.getElementById('btnMenuOpen');
        const btnMenuClose = document.getElementById('btnMenuClose');
        
        btnMenuOpen.addEventListener('click', () => {
            document.body.classList.remove('sidebar-closed');
            hapticNav();
        });
        
        btnMenuClose.addEventListener('click', () => {
            document.body.classList.add('sidebar-closed');
            hapticNav();
        });

        // Auto-close sidebar on mobile after clicking a link
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar .nav-item[href^="/"]').forEach(link => {
                link.addEventListener('click', () => {
                    document.body.classList.add('sidebar-closed');
                });
            });
        }

        // CURSOR LOGIC
        const cursorDot = document.getElementById('cursorDot');
        const cursorRing = document.getElementById('cursorRing');
        if (window.matchMedia("(pointer: fine)").matches) {
            let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
            window.addEventListener('mousemove', (e) => {
                mouseX = e.clientX; mouseY = e.clientY;
                cursorDot.style.left = `${mouseX}px`; cursorDot.style.top = `${mouseY}px`;
            });
            function animateCursor() {
                ringX += (mouseX - ringX) * 0.15; ringY += (mouseY - ringY) * 0.15;
                cursorRing.style.left = `${ringX}px`; cursorRing.style.top = `${ringY}px`;
                requestAnimationFrame(animateCursor);
            }
            animateCursor();
            const addHover = () => document.body.classList.add('cursor-hovering');
            const removeHover = () => document.body.classList.remove('cursor-hovering');
            setInterval(() => {
                document.querySelectorAll('a:not(.cursor-bind), button:not(.cursor-bind), .hover-trigger:not(.cursor-bind), .shard-wrapper:not(.cursor-bind)').forEach(el => {
                    el.classList.add('cursor-bind');
                    el.addEventListener('mouseenter', addHover);
                    el.addEventListener('mouseleave', removeHover);
                });
            }, 1000);
        }

        // THEME & LOADER
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'light') {
            document.getElementById('themeText').innerText = 'Siang';
            document.getElementById('toggleIcon').className = 'fa-solid fa-sun';
        }

        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loadingScreen');
                loader.style.opacity = '0';
                setTimeout(() => loader.style.visibility = 'hidden', 800);
            }, 1500); 
        });

        const btnTheme = document.getElementById('btnTheme');
        btnTheme.addEventListener('click', () => {
            hapticNav();
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('expedient_theme', newTheme);
            for(let i=0; i<20; i++) particlesArray.push(new Particle(window.innerWidth - 50, 50, Math.random()*3+1, -(Math.random()*4+2)));
            if (newTheme === 'light') { document.getElementById('themeText').innerText = 'Siang'; document.getElementById('toggleIcon').className = 'fa-solid fa-sun'; } 
            else { document.getElementById('themeText').innerText = 'Malam'; document.getElementById('toggleIcon').className = 'fa-solid fa-moon'; }
        });

        // PARTICLES ENGINE
        const canvas = document.getElementById("particles-js");
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth; canvas.height = window.innerHeight;
        let particlesArray = []; let mouseArea = { x: null, y: null, radius: 100 };
        window.addEventListener('mousemove', e => { mouseArea.x = e.x; mouseArea.y = e.y; });
        window.addEventListener('mouseleave', () => { mouseArea.x = undefined; mouseArea.y = undefined; });
        
        class Particle {
            constructor(x, y, size, weight) { this.x = x; this.y = y; this.size = size; this.weight = weight; }
            update() {
                this.y -= this.weight;
                if (this.y < 0 - this.size) { this.y = canvas.height + this.size; this.x = Math.random() * canvas.width; }
                if (mouseArea.x != null) {
                    let dx = mouseArea.x - this.x; let dy = mouseArea.y - this.y; let distance = Math.sqrt(dx*dx + dy*dy);
                    if (distance < mouseArea.radius) {
                        const forceDirectionX = dx / distance; const forceDirectionY = dy / distance;
                        const force = (mouseArea.radius - distance) / mouseArea.radius;
                        this.x -= forceDirectionX * force * 5; this.y -= forceDirectionY * force * 5;
                    }
                }
            }
            draw() {
                const isLight = document.documentElement.getAttribute('data-theme') === 'light';
                ctx.fillStyle = isLight ? 'rgba(0, 0, 0, 0.15)' : 'rgba(255, 255, 255, 0.15)';
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill();
            }
        }
        function initParticles() { particlesArray = []; for (let i = 0; i < 60; i++) particlesArray.push(new Particle(Math.random() * innerWidth, Math.random() * innerHeight, (Math.random() * 2) + 0.5, (Math.random() * 0.5) + 0.2)); }
        function animateParticles() { ctx.clearRect(0, 0, canvas.width, canvas.height); for (let i = 0; i < particlesArray.length; i++) { particlesArray[i].update(); particlesArray[i].draw(); } requestAnimationFrame(animateParticles); }
        initParticles(); animateParticles();
        window.addEventListener('resize', () => { canvas.width = innerWidth; canvas.height = innerHeight; initParticles(); });

        // GYROSCOPE PARALLAX (HP)
        if (window.DeviceOrientationEvent && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            const auroras = document.querySelectorAll('.aurora-blob');
            window.addEventListener('deviceorientation', (e) => {
                const tiltX = Math.min(Math.max(e.gamma, -45), 45); 
                const tiltY = Math.min(Math.max(e.beta - 45, -45), 45); 
                requestAnimationFrame(() => {
                    auroras.forEach((blob, index) => {
                        const depthSpeed = (index + 1) * 0.8; 
                        blob.style.transform = `translate(${tiltX * depthSpeed}px, ${tiltY * depthSpeed}px)`;
                    });
                    if (particlesArray.length > 0) particlesArray.forEach(p => { p.x += tiltX * 0.05; });
                });
            });
        }

        // ================= PUSHER & CI4 FLASHDATA =================
        const pusher = new Pusher('<?= getenv('PUSHER_APP_KEY') ?: 'app-key' ?>', { 
            cluster: '<?= getenv('PUSHER_APP_CLUSTER') ?: 'mt1' ?>', 
            wsHost: '127.0.0.1', 
            wsPort: 6001, 
            forceTLS: false, 
            disableStats: true 
        });
        const channel = pusher.subscribe('expedient-channel');
        
        channel.bind('alumni-baru', function(data) {
            const aegisToast = document.getElementById('aegisToast');
            const radarName = document.getElementById('radarName');
            document.querySelector('.aegis-title').innerText = "Entitas Terdeteksi Masuk";
            radarName.innerText = data.nama; 
            aegisToast.classList.add('show');
            if (navigator.vibrate) navigator.vibrate([100, 100, 100]);
            setTimeout(() => aegisToast.classList.remove('show'), 6000);
        });

        // CI4 FLASHDATA CATCHER
        <?php if (session()->getFlashdata('pesan')) : ?>
            document.addEventListener("DOMContentLoaded", () => {
                const aegisToastSys = document.getElementById('aegisToast');
                const radarNameSys = document.getElementById('radarName');
                document.querySelector('.aegis-title').innerText = "Informasi Sistem";
                radarNameSys.innerText = "<?= session()->getFlashdata('pesan') ?>";
                aegisToastSys.classList.add('show');
                if (navigator.vibrate) navigator.vibrate([50, 50, 50]);
                setTimeout(() => aegisToastSys.classList.remove('show'), 6000);
            });
        <?php endif; ?>
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>