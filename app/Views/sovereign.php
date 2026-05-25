<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sovereign Vault - <?= esc($user['nama_panggilan']) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* RESET & STANDALONE PAGE SETUP */
        * { box-sizing: border-box; }
        body, html { 
            margin: 0; padding: 0; width: 100vw; height: 100vh; 
            background-color: #020202; overflow: hidden; 
            font-family: 'Inter', sans-serif; user-select: none;
            -webkit-font-smoothing: antialiased;
            transition: background-color 1s ease;
        }

        /* TEMA SIANG (ROYAL PEARL GALLERY) */
        body[data-theme='light'] { background-color: #f8f9fa; }
        body[data-theme='light'] .vault-vignette { background: radial-gradient(circle at center, transparent 10%, rgba(255,255,255,0.6) 100%); }
        body[data-theme='light'] .tactical-hud, body[data-theme='light'] .ux-text { color: #222; text-shadow: 0 0 5px rgba(255,255,255,0.8); }
        body[data-theme='light'] .ux-icon { filter: drop-shadow(0 0 10px rgba(0,0,0,0.2)); color: #b48600; }
        body[data-theme='light'] .btn-vault-back { background: rgba(255,255,255,0.9); color: #333; border-color: #d4af37; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        body[data-theme='light'] .theme-toggle { background: #fff; color: #b48600; border-color: #d4af37; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

        #canvas-container {
            width: 100vw; height: 100vh; display: block;
            position: absolute; top: 0; left: 0; z-index: 10;
            outline: none; pointer-events: auto;
        }

        /* VIGNETTE & LIGHTING FOG */
        .vault-vignette {
            position: absolute; inset: 0; pointer-events: none; z-index: 11;
            background: radial-gradient(circle at center, transparent 20%, rgba(0,0,0,0.9) 100%);
            transition: background 1s ease;
        }

        /* STANDALONE UI COMPONENTS */
        .btn-vault-back {
            position: absolute; top: 30px; left: 30px; z-index: 100;
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; background: rgba(0,0,0,0.6);
            border: 1px solid rgba(212,175,55,0.3); border-radius: 8px;
            color: #d4af37; font-size: 11px; font-weight: 600; 
            letter-spacing: 3px; text-decoration: none; text-transform: uppercase;
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease; cursor: pointer;
        }
        .btn-vault-back:hover {
            transform: translateX(-5px); box-shadow: 0 0 20px rgba(212,175,55,0.5); border-color: #ffd700; color: #fff;
        }

        /* TOMBOL MODE SIANG/MALAM */
        .theme-toggle {
            position: absolute; top: 30px; right: 30px; z-index: 100;
            width: 45px; height: 45px; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            background: rgba(0,0,0,0.6); border: 1px solid rgba(212,175,55,0.3);
            color: #d4af37; font-size: 16px; cursor: pointer;
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle:hover { transform: scale(1.1) rotate(15deg); box-shadow: 0 0 20px rgba(212,175,55,0.5); }

        /* TOMBOL EXPORT (DOWNLOAD) */
        .btn-export {
            position: absolute; top: 90px; right: 30px; z-index: 100;
            width: 45px; height: 45px; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            background: rgba(0,0,0,0.6); border: 1px solid rgba(212,175,55,0.3);
            color: #d4af37; font-size: 16px; cursor: pointer;
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-export:hover { transform: scale(1.1) translateY(5px); box-shadow: 0 0 20px rgba(212,175,55,0.5); }
        body[data-theme='light'] .btn-export { background: #fff; color: #b48600; border-color: #d4af37; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

        /* TACTICAL HUD */
        .tactical-hud {
            position: absolute; bottom: 30px; left: 30px; z-index: 15;
            color: rgba(212,175,55,0.6); font-family: 'Courier New', monospace; 
            font-size: 10px; letter-spacing: 1px; line-height: 1.6; pointer-events: none;
            transition: color 1s ease;
        }

        .ux-overlay {
            position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 12px;
            z-index: 20; pointer-events: none; transition: opacity 0.8s ease;
        }
        .ux-overlay.hidden { opacity: 0; }
        .ux-icon { color: #d4af37; font-size: 24px; animation: dragSim 2.5s infinite; filter: drop-shadow(0 0 10px rgba(212,175,55,0.6)); }
        .ux-text { color: #d4af37; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 4px; text-shadow: 0 0 10px #000; text-align: center; }

        @keyframes dragSim {
            0% { transform: translateY(-10px) scale(0.9); opacity: 0; }
            20% { transform: translateY(0px) scale(1); opacity: 1; }
            70% { transform: translateY(30px) scale(1); opacity: 1; }
            100% { transform: translateY(40px) scale(0.9); opacity: 0; }
        }

        /* PRELOADER STANDALONE */
        #preloader { 
            position: fixed; inset: 0; z-index: 99999; background: #020202; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; 
            transition: opacity 0.8s ease; 
        }
        .loader-ring { 
            width: 50px; height: 50px; border: 2px solid rgba(212,175,55,0.1); 
            border-top-color: #ffd700; border-radius: 50%; animation: spinLoader 1s linear infinite; margin-bottom: 20px; 
            box-shadow: 0 0 20px rgba(212,175,55,0.2);
        }
        #loadingText { color: #ffd700; font-family: 'Courier New', monospace; letter-spacing: 4px; font-size: 11px; text-shadow: 0 0 10px rgba(212,175,55,0.5); }
        @keyframes spinLoader { 100% { transform: rotate(360deg); } }

        body.is-grabbing { cursor: grabbing !important; }
        body.is-grabbing #canvas-container { cursor: grabbing !important; }

        @media (max-width: 768px) {
            .btn-vault-back { top: 20px; left: 20px; padding: 8px 15px; font-size: 10px; }
            .theme-toggle { top: 20px; right: 20px; width: 35px; height: 35px; font-size: 14px; }
            .btn-export { top: 65px; right: 20px; width: 35px; height: 35px; font-size: 14px; }
            .tactical-hud { display: none; }
            .ux-overlay { bottom: 20px; }
        }
    </style>
</head>
<body>

    <div id="preloader">
        <div class="loader-ring"></div>
        <span id="loadingText"><?= cms_raw('sov_loading', 'DECRYPTING OMNIPRESENCE...') ?></span>
    </div>

    <div class="vault-vignette"></div>

    <a href="<?= base_url('fitur') ?>" class="btn-vault-back" id="btnBackToFitur">
        <i class="fa-solid fa-chevron-left"></i> <?= cms_text('sov_btn_exit', 'Exit Vault') ?>
    </a>

    <button class="theme-toggle" id="btnThemeToggle" title="Toggle Day/Night Mode">
        <i class="fa-solid fa-sun"></i>
    </button>

    <button class="btn-export" id="btnExportId" title="Simpan Kartu ID (PNG)">
        <i class="fa-solid fa-download"></i>
    </button>

    <div class="tactical-hud">
        SYS_VER: 4.0.4_SOVEREIGN<br>
        ENVIRONMENT: <span id="envStatus">NOIR_VAULT_ACTIVE</span><br>
        ACCESS: GRANTED [<?= strtoupper(esc($user['nama_panggilan'])) ?>]
    </div>

    <div class="ux-overlay" id="uxOverlay">
        <i class="fa-solid fa-hand-pointer ux-icon"></i>
        <span class="ux-text"><?= cms_html('sov_tutor_drag', 'Berinteraksi <br><span style="font-size:9px; opacity:0.7">Tarik Kartu & Usap Layar</span>') ?></span>
    </div>

    <div id="canvas-container"></div>

    <script type="importmap">
        {
            "imports": {
                "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
                "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
            }
        }
    </script>

    <script>
        window.ExpedientData = {
            nama: "<?= esc($user['nama_lengkap']) ?>",
            jabatan: "<?= cms_raw('sov_jabatan', 'SOVEREIGN ENTITY') ?>",
            nomor_id: "EXP-<?= sprintf('%03d', $user['id']) ?>",
            exp: "<?= cms_raw('sov_exp', 'VALID THRU FOREVER') ?>",
            foto_url: "<?= $foto_profil ?>",
            qr_url: "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode(base_url('scan/') . esc($user['public_token'])) ?>"
        };
    </script>
    <script type="module" src="/assets/js/sovereign.js"></script>
</body>
</html>