<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Inisiasi Angkatan - Expedient Generation</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ================= 1. GOD-TIER VARIABLES ================= */
        :root {
            --bg-main: #020406; --bg-radial: #081014;
            --aurora-1: rgba(0, 255, 136, 0.12); --aurora-2: rgba(0, 162, 255, 0.1); --aurora-3: rgba(212, 175, 55, 0.08);
            
            --glass-bg: rgba(10, 15, 20, 0.45); --glass-blur: blur(40px);
            --glass-shadow: 0 50px 100px rgba(0, 0, 0, 0.95); --glass-edge: rgba(255, 255, 255, 0.06);
            
            --text-primary: #ffffff; --text-secondary: #7b8e9b;
            --gold-liquid: linear-gradient(110deg, #d4af37 0%, #fff2cd 30%, #aa771c 70%, #d4af37 100%);
            --border-glow: rgba(0, 255, 136, 0.6);
            
            --input-bg: rgba(0, 0, 0, 0.4); --input-line: rgba(255, 255, 255, 0.1); --input-focus: rgba(0, 255, 136, 0.1);
        }

        [data-theme="light"] {
            --bg-main: #eef2f5; --bg-radial: #ffffff;
            --aurora-1: rgba(0, 150, 255, 0.1); --aurora-2: rgba(255, 150, 180, 0.1); --aurora-3: rgba(212, 175, 55, 0.15);
            
            --glass-bg: rgba(255, 255, 255, 0.65); --glass-blur: blur(40px);
            --glass-shadow: 0 40px 80px rgba(0, 20, 40, 0.08); --glass-edge: rgba(255, 255, 255, 0.8);
            
            --text-primary: #0a1118; --text-secondary: #5e6c77;
            --gold-liquid: linear-gradient(110deg, #b38728 0%, #d4af37 30%, #ffd700 70%, #b38728 100%);
            --border-glow: rgba(0, 120, 255, 0.3);
            
            --input-bg: rgba(255, 255, 255, 0.6); --input-line: rgba(0, 0, 0, 0.1); --input-focus: rgba(0, 120, 255, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(123, 142, 155, 0.3); border-radius: 10px; }

        /* ================= 2. FIXED ENVIRONMENT ================= */
        body { background-color: var(--bg-main); background-image: radial-gradient(circle at 50% 0%, var(--bg-radial), transparent 70%); color: var(--text-primary); min-height: 100vh; overflow-x: hidden; transition: background 1.2s cubic-bezier(0.25, 1, 0.5, 1); }
        .main-container { position: relative; width: 100%; min-height: 100vh; padding: 120px 20px 80px 20px; display: flex; justify-content: center; align-items: flex-start; perspective: 1500px; }
        .aurora-container { position: fixed; inset: -30%; z-index: 0; pointer-events: none; filter: var(--glass-blur); }
        .aurora-blob { position: absolute; border-radius: 50%; opacity: 0.9; mix-blend-mode: screen; animation: morphBlob 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); }
        [data-theme="light"] .aurora-blob { mix-blend-mode: multiply; opacity: 0.7; }
        .blob-1 { width: 55vw; height: 55vw; background: var(--aurora-1); top: -10%; left: 0; transform-origin: center right; }
        .blob-2 { width: 65vw; height: 65vw; background: var(--aurora-2); bottom: -10%; right: -10%; transform-origin: center left; animation-delay: -7s; }
        .blob-3 { width: 45vw; height: 45vw; background: var(--aurora-3); top: 40%; left: 30%; transform-origin: bottom center; animation-delay: -14s; }
        @keyframes morphBlob { 0% { transform: scale(1) translate(0, 0) rotate(0deg); } 100% { transform: scale(1.4) translate(120px, -80px) rotate(90deg); } }
        #particles-js { position: fixed; inset: 0; z-index: 1; pointer-events: none; }

        /* ================= 3. NAVIGATION & TOGGLE ================= */
        .floating-nav { position: fixed; top: 40px; left: 40px; z-index: 100; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-edge); border-radius: 30px; padding: 10px 22px; cursor: pointer; display: flex; align-items: center; gap: 10px; color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 0.85rem; box-shadow: var(--glass-shadow); transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); animation: fadeIn 1s forwards 0.5s; opacity: 0; }
        .floating-nav:hover { transform: translateX(-5px); border-color: #d4af37; color: #d4af37; }
        .theme-widget { position: fixed; top: 40px; right: 40px; z-index: 100; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-edge); border-radius: 40px; padding: 6px 16px 6px 6px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: var(--glass-shadow); }
        .theme-widget:hover { transform: translateY(-3px) scale(1.05); border-color: var(--text-primary); }
        .icon-orb { width: 35px; height: 35px; border-radius: 50%; background: var(--bg-main); display: flex; justify-content: center; align-items: center; color: var(--text-primary); box-shadow: inset 0 2px 5px rgba(0,0,0,0.5); transition: 0.4s; }
        [data-theme="light"] .icon-orb { box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); }
        .theme-widget:hover .icon-orb { background: var(--text-primary); color: var(--bg-main); }
        .widget-text { font-size: 0.75rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--text-secondary); transition: 0.4s; }

        /* ================= 4. THE PRISM VAULT ================= */
        .register-vault { position: relative; z-index: 10; width: 100%; max-width: 900px; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-radius: 36px; padding: 55px; box-shadow: var(--glass-shadow), inset 0 0 0 1px var(--glass-edge); opacity: 0; transform: translateY(60px) scale(0.95); animation: vaultEnter 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.1) forwards; }
        @keyframes vaultEnter { to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes fadeIn { to { opacity: 1; } }

        .vault-header { text-align: center; margin-bottom: 40px; transform: translateZ(30px); }
        .logo-container { position: relative; width: 90px; height: 90px; margin: 0 auto 20px; }
        .logo-part { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; opacity: 0; pointer-events: none; transition: all 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.1); }
        .logo-utuh { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.6)); opacity: 0; transform: scale(0.5); transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1) 1s; }
        .logo-container.exploding .logo-part { opacity: 1; }
        .logo-container.exploding .part-1 { transform: translate(-50px, -50px) rotate(-45deg) scale(1.5); }
        .logo-container.exploding .part-2 { transform: translate(50px, -50px) rotate(45deg) scale(1.5); }
        .logo-container.exploding .part-3 { transform: translate(-50px, 50px) rotate(-90deg) scale(1.5); }
        .logo-container.exploding .part-4 { transform: translate(50px, 50px) rotate(90deg) scale(1.5); }
        .logo-container.exploding .part-5 { transform: translateZ(100px) scale(2); }
        .logo-container.united .logo-part { opacity: 0; transform: translate(0,0) rotate(0) scale(0.5); }
        .logo-container.united .logo-utuh { opacity: 1; transform: scale(1); animation: floatLogo 5s ease-in-out infinite 2s; }
        @keyframes floatLogo { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

        .title-holographic { font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 700; background: var(--gold-liquid); background-size: 200% auto; -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: 1px; animation: shimmerLiquid 8s linear infinite; margin-bottom: 8px; }
        @keyframes shimmerLiquid { to { background-position: 200% center; } }
        .subtitle-spec { font-size: 0.8rem; color: var(--text-secondary); letter-spacing: 4px; text-transform: uppercase; font-weight: 600; }

        .php-error-box { background: rgba(255, 51, 102, 0.08); border: 1px solid rgba(255, 51, 102, 0.5); padding: 20px 25px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(255, 51, 102, 0.15), inset 0 0 20px rgba(255, 51, 102, 0.05); animation: shakeError 0.5s cubic-bezier(.36,.07,.19,.97) both; border-left: 5px solid #ff3366; }
        .php-error-box h4 { color: #ff3366; margin-bottom: 10px; font-weight: 700; font-size: 1.1rem; }
        .php-error-box ul { margin-left: 20px; color: #ff99aa; font-size: 0.9rem; line-height: 1.6; }

        /* ================= 5. SMART GRID, FLUID INPUTS & ERROR HINTS ================= */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px 35px; } /* Increased gap to prevent error overlaps */
        .span-full { grid-column: 1 / -1; }

        .input-group { position: relative; opacity: 0; transform: translateY(20px); animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; margin-bottom: 5px; }
        .input-group:nth-child(1){animation-delay:0.1s;} .input-group:nth-child(2){animation-delay:0.15s;}
        .input-group:nth-child(3){animation-delay:0.2s;} .input-group:nth-child(4){animation-delay:0.25s;}
        .input-group:nth-child(5){animation-delay:0.3s;} .input-group:nth-child(6){animation-delay:0.35s;}
        .input-group:nth-child(7){animation-delay:0.4s;} .input-group:nth-child(8){animation-delay:0.45s;}
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

        .input-control { width: 100%; padding: 14px 15px; background: var(--input-bg); border: none; border-bottom: 1px solid var(--input-line); border-radius: 8px 8px 0 0; color: var(--text-primary); font-size: 1rem; outline: none; transition: 0.4s; }
        .input-control::placeholder { color: transparent; }
        select.input-control { appearance: none; cursor: pointer; }
        select.input-control option { background: var(--bg-main); color: var(--text-primary); }

        .input-neon-line { position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: var(--border-glow); transition: 0.5s cubic-bezier(0.25, 1, 0.5, 1); transform: translateX(-50%); }
        .input-label { position: absolute; top: 14px; left: 15px; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); letter-spacing: 0.5px; }

        /* KETERANGAN ERROR KHUSUS DI BAWAH INPUT */
        .error-hint {
            position: absolute; bottom: -20px; left: 5px; font-size: 0.7rem; font-weight: 600;
            color: #ff3366; opacity: 0; transform: translateY(-5px); transition: all 0.3s ease; pointer-events: none;
        }

        /* EFEK INVALID PADA INPUT */
        .input-control.is-invalid { border-bottom: 1px solid #ff3366; background: rgba(255, 51, 102, 0.05); }
        .input-control.is-invalid ~ .input-neon-line { background: #ff3366; width: 100%; box-shadow: 0 -2px 10px rgba(255, 51, 102, 0.4); }
        .input-control.is-invalid ~ .input-label { color: #ff3366; }
        .input-control.is-invalid ~ .error-hint { opacity: 1; transform: translateY(0); } /* Tampilkan Keterangan */

        .input-control:focus, .input-control:not(:placeholder-shown) { background: var(--input-focus); }
        .input-control:focus ~ .input-label, .input-control:not(:placeholder-shown) ~ .input-label { top: -20px; left: 0; font-size: 0.7rem; color: var(--text-secondary); letter-spacing: 2px; text-transform: uppercase; font-weight: 700; }
        .input-control:focus:not(.is-invalid) ~ .input-neon-line { width: 100%; box-shadow: 0 -2px 10px var(--border-glow); }
        .input-control:focus:not(.is-invalid) ~ .input-label { color: #d4af37; }

        .icon-eye { position: absolute; right: 15px; top: 14px; color: var(--text-secondary); cursor: pointer; transition: 0.3s; font-size: 1.1rem; }
        .icon-eye:hover { color: var(--text-primary); transform: scale(1.1); }

        /* ================= 6. HOLOGRAPHIC CROP AREA ================= */
        .upload-zone { display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--input-bg); border: 1px dashed var(--input-line); border-bottom: 2px solid var(--input-line); border-radius: 16px; padding: 35px; text-align: center; cursor: pointer; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .upload-zone:hover { background: var(--input-focus); border-bottom-color: var(--border-glow); transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .upload-zone i { font-size: 2.5rem; color: #d4af37; margin-bottom: 12px; transition: transform 0.4s; }
        .upload-zone:hover i { transform: scale(1.2); filter: drop-shadow(0 0 10px rgba(212,175,55,0.5)); }
        .preview-area { display: none; text-align: center; margin-top: 15px; animation: fadeUp 0.5s forwards; }
        .preview-area img { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 3px solid #d4af37; box-shadow: 0 15px 30px rgba(0,0,0,0.6); }
        .btn-change-photo { margin-top: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--text-secondary); color: var(--text-secondary); padding: 8px 20px; border-radius: 20px; cursor: pointer; transition: 0.3s; font-weight: 600; font-size: 0.85rem;}

        /* ================= 7. MAGNETIC CHECKBOX & BUTTON ================= */
        .checkbox-container { display: flex; align-items: flex-start; gap: 15px; margin-top: 15px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid transparent; transition: 0.3s; position: relative; opacity: 1 !important; transform: none !important; animation: none !important; z-index: 10;}
        .checkbox-container.is-invalid { border-color: #ff3366; background: rgba(255, 51, 102, 0.05); animation: shakeError 0.4s !important; }
        [data-theme="light"] .checkbox-container { background: rgba(255,255,255,0.4); }
        .checkbox-container input { width: 20px; height: 20px; accent-color: #d4af37; cursor: pointer; margin-top: 3px; }
        .checkbox-container label { color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6; cursor: pointer; margin: 0; pointer-events: auto;}

        .btn-magnetic-wrapper { display: inline-block; width: 100%; position: relative; margin-top: 20px; } 
        .btn-prime { position: relative; width: 100%; padding: 22px; border-radius: 18px; border: none; background: var(--gold-liquid); background-size: 200% auto; color: #05090a; font-weight: 800; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 3px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.5), inset 0 2px 0 rgba(255,255,255,0.4); transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.27); will-change: transform; }
        .btn-prime:hover { animation: shimmerLiquid 2.5s linear infinite; box-shadow: 0 20px 40px rgba(212, 175, 55, 0.4); }

        @keyframes shakeError { 10%, 90% { transform: translate3d(-2px, 0, 0); } 20%, 80% { transform: translate3d(4px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-8px, 0, 0); } 40%, 60% { transform: translate3d(8px, 0, 0); } }
        .shake-anim { animation: shakeError 0.5s cubic-bezier(.36,.07,.19,.97) both !important; box-shadow: 0 0 30px rgba(255,51,102,0.6) !important; border: 1px solid #ff3366 !important; }

        /* ================= 8. VOLUMETRIC ALERTS ================= */
        .quantum-toast { position: fixed; top: 40px; right: -600px; padding: 20px 30px; border-radius: 20px; background: var(--glass-surface); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-edge); color: var(--text-primary); display: flex; align-items: center; gap: 20px; box-shadow: var(--glass-shadow); transition: right 0.8s cubic-bezier(0.34, 1.56, 0.64, 1.2); z-index: 9999; transform-style: preserve-3d; }
        .quantum-toast.show { right: 40px; }
        .toast-icon { font-size: 2rem; transform: translateZ(25px); filter: drop-shadow(0 10px 10px rgba(0,0,0,0.5)); }
        .toast-success { border-bottom: 4px solid #00ff88; border-right: 2px solid #00ff88; } .toast-success i { color: #00ff88; }
        .toast-error { border-bottom: 4px solid #ff3366; border-right: 2px solid #ff3366; } .toast-error i { color: #ff3366; }

        /* ================= 9. CROPPER & BIOMETRIC GATEWAY ================= */
        .crop-modal, .auth-vault { display: none; position: fixed; inset: 0; background: rgba(2,4,6,0.95); z-index: 10000; justify-content: center; align-items: center; backdrop-filter: blur(25px); flex-direction: column; }
        .auth-vault { z-index: 10001; } /* Harus lebih di atas dari cropper */
        .crop-content { background: var(--glass-bg); border: 1px solid var(--glass-edge); padding: 40px; border-radius: 28px; width: 90%; max-width: 500px; box-shadow: var(--glass-shadow); text-align: center; }
        .crop-img-wrap { width: 100%; max-height: 50vh; margin-bottom: 30px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5); } .crop-img-wrap img { max-width: 100%; }
        .crop-actions { display: flex; justify-content: flex-end; gap: 15px; }
        .crop-btn-cancel { padding: 14px 28px; background: transparent; border: 1px solid var(--text-secondary); color: var(--text-secondary); border-radius: 16px; cursor: pointer; transition: 0.3s; font-weight: 600; }
        .crop-btn-cancel:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: #fff; }
        .crop-btn-apply { padding: 14px 28px; background: var(--gold-liquid); background-size: 200% auto; border: none; color: #000; font-weight: bold; border-radius: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 20px rgba(212,175,55,0.3); }
        .crop-btn-apply:hover { animation: shimmerLiquid 2.5s linear infinite; box-shadow: 0 15px 30px rgba(212,175,55,0.5); transform: translateY(-2px); }

        /* Leica Retina Scanner Custom Elements */
        .retina-container { position: relative; width: 320px; height: 320px; display: flex; justify-content: center; align-items: center; margin-bottom: 50px; }
        .focus-ring { position: absolute; inset: 0; border: 1px solid var(--glass-edge); border-radius: 50%; box-shadow: inset 0 0 40px var(--glass-bg), 0 20px 60px rgba(0,0,0,0.3); z-index: 1; transition: border-color 0.8s ease, box-shadow 0.8s ease; }
        [data-theme="light"] .focus-ring { box-shadow: inset 0 0 40px var(--glass-bg), 0 20px 60px rgba(0,0,0,0.05); }
        .bracket { position: absolute; width: 40px; height: 40px; border: 2px solid transparent; z-index: 20; transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.5s; }
        .bracket-tl { top: 25px; left: 25px; border-top-color: #d4af37; border-left-color: #d4af37; }
        .bracket-tr { top: 25px; right: 25px; border-top-color: #d4af37; border-right-color: #d4af37; }
        .bracket-bl { bottom: 25px; left: 25px; border-bottom-color: #d4af37; border-left-color: #d4af37; }
        .bracket-br { bottom: 25px; right: 25px; border-bottom-color: #d4af37; border-right-color: #d4af37; }
        .scanning .bracket-tl { transform: translate(-10px, -10px); } .scanning .bracket-tr { transform: translate(10px, -10px); }
        .scanning .bracket-bl { transform: translate(-10px, 10px); } .scanning .bracket-br { transform: translate(10px, 10px); }
        
        .camera-frame { position: absolute; width: 250px; height: 250px; border-radius: 50%; overflow: hidden; z-index: 10; background: #050505; }
        .camera-frame::after { content: ''; position: absolute; inset: 0; border-radius: 50%; background: radial-gradient(circle at 50% 50%, transparent 60%, rgba(0,0,0,0.8) 100%); pointer-events: none; }
        .camera-feed { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); filter: contrast(1.1) saturate(1.1) grayscale(0.1); }
        .lens-dust { position: absolute; inset: 0; z-index: 15; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.15'/%3E%3C/svg%3E"); pointer-events: none; mix-blend-mode: color-dodge; }
        .scan-line { position: absolute; top: 0; left: 0; width: 100%; height: 1px; background: #d4af37; opacity: 0; z-index: 16; box-shadow: 0 0 20px 2px #d4af37; pointer-events: none; }
        
        .liveness-indicator { display: flex; align-items: center; gap: 25px; margin-bottom: 35px; }
        .live-node { width: 5px; height: 5px; border-radius: 50%; background: var(--glass-edge); transition: 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        .live-node.active { background: #d4af37; box-shadow: 0 0 15px #d4af37; transform: scale(2); }
        .live-node.done { background: var(--text-primary); }

        .status-display { text-align: center; min-width: 320px; padding: 20px; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-edge); border-radius: 20px; box-shadow: var(--glass-shadow); }
        .status-title { font-family: 'Inter', sans-serif; font-size: 0.65rem; color: var(--text-secondary); letter-spacing: 4px; text-transform: uppercase; margin-bottom: 15px; }
        .status-value { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 500; color: var(--text-primary); letter-spacing: 1px; min-height: 35px; }

        @media (max-width: 768px) {
            .main-container { padding: 100px 15px 40px 15px; }
            .form-grid { grid-template-columns: 1fr; gap: 30px; }
            .register-vault { padding: 40px 25px; border-radius: 28px; }
            .floating-nav { top: 25px; left: 20px; padding: 8px 16px; font-size: 0.8rem; } .nav-text { display: none; } 
            .theme-widget { top: 25px; right: 20px; padding: 6px; } .widget-text { display: none; }
            .quantum-toast.show { right: 20px; left: 20px; min-width: calc(100% - 40px); }
        }
    </style>
    
    <script src="/vendor/gsap/gsap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
</head>
<body data-theme="dark">

    <div class="aurora-container">
        <div class="aurora-blob blob-1"></div>
        <div class="aurora-blob blob-2"></div>
        <div class="aurora-blob blob-3"></div>
    </div>
    <canvas id="particles-js"></canvas>

    <a href="/login" class="floating-nav"><i class="fa-solid fa-arrow-left-long"></i> <span class="nav-text">Kembali</span></a>
    <button class="theme-widget" id="btnTheme" title="Ganti Mode"><div class="icon-orb"><i class="fa-solid fa-moon" id="toggleIcon"></i></div><span class="widget-text" id="themeText">Malam</span></button>

    <div id="dynamicToast" class="quantum-toast <?= session()->getFlashdata('success') ? 'toast-success show' : (session()->getFlashdata('error') ? 'toast-error show' : '') ?>">
        <div class="toast-icon"><i class="<?= session()->getFlashdata('success') ? 'fa-solid fa-check-double' : 'fa-solid fa-shield-virus' ?>" id="toastIconHtml"></i></div>
        <div style="transform: translateZ(15px);">
            <strong style="font-family:'Playfair Display', serif; font-size:1.15rem; letter-spacing: 1px;" id="toastTitleHtml"><?= session()->getFlashdata('success') ? 'Akses Diverifikasi' : (session()->getFlashdata('error') ? 'Akses Ditolak' : '') ?></strong><br>
            <span style="font-size:0.85rem; color:var(--text-secondary);" id="toastMessageHtml"><?= session()->getFlashdata('success') ?? session()->getFlashdata('error') ?></span>
        </div>
    </div>

    <div class="main-container">
        <div class="register-vault" id="mainVault">
            
            <?php if(session()->getFlashdata('validation_errors')): ?>
                <div class="php-error-box">
                    <h4><i class="fa-solid fa-triangle-exclamation"></i> Inisiasi Gagal Divalidasi:</h4>
                    <ul>
                        <?php foreach(session()->getFlashdata('validation_errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="vault-header">
                <div class="logo-container" id="logoContainer">
                    <img src="/images/kristal-puncak.png" class="logo-part part-1" alt="Part">
                    <img src="/images/tanduk-perak.png" class="logo-part part-2" alt="Part">
                    <img src="/images/zamrud-hijau.png" class="logo-part part-3" alt="Part">
                    <img src="/images/cincin-emas.png" class="logo-part part-4" alt="Part">
                    <img src="/images/mahkota-emas.png" class="logo-part part-5" alt="Part">
                    <img src="/images/logo-utuh.png" class="logo-utuh" alt="Expedient Logo" id="logoUtuh">
                </div>
                <h1 class="title-holographic">Inisiasi Angkatan</h1>
                <p class="subtitle-spec">Pahat identitas Anda dalam sejarah 42nd Arrisalah Expedient Generation.</p>
            </div>

            <form action="/auth/register" method="POST" id="registerForm" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="face_data" id="faceDataInput">
                
                <div class="form-grid">
                    
                    <div class="input-group">
                        <input type="text" name="nama_lengkap" class="input-control" required minlength="3" placeholder=" " value="<?= old('nama_lengkap') ?>">
                        <label class="input-label">Nama Lengkap (Sesuai Ijazah)</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div> </div>
                    
                    <div class="input-group">
                        <input type="text" name="nama_panggilan" class="input-control" required minlength="2" placeholder=" " value="<?= old('nama_panggilan') ?>">
                        <label class="input-label">Nama Panggilan</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>

                    <div class="input-group">
                        <select name="jenis_kelamin" class="input-control" required>
                            <option value="" disabled <?= !old('jenis_kelamin') ? 'selected' : '' ?> hidden>Pilih Gender...</option>
                            <option value="Laki-laki" <?= old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                        <label class="input-label">Jenis Kelamin</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" name="tempat_lahir" class="input-control" required placeholder=" " value="<?= old('tempat_lahir') ?>">
                        <label class="input-label">Tempat Lahir</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>

                    <div class="input-group">
                        <input type="date" name="tanggal_lahir" class="input-control" required placeholder=" " value="<?= old('tanggal_lahir') ?>" style="color-scheme: dark;">
                        <label class="input-label" style="top:-20px; font-size:0.75rem; color:var(--text-secondary); letter-spacing:2px; font-weight:700;">Tanggal Lahir</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>

                    <div class="input-group span-full">
                        <input type="text" name="alamat_lengkap" class="input-control" required minlength="10" placeholder=" " value="<?= old('alamat_lengkap') ?>">
                        <label class="input-label">Alamat Lengkap Domisili</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" class="input-control" required placeholder=" " value="<?= old('email') ?>">
                        <label class="input-label">Surel Resmi (Email Aktif)</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" name="no_whatsapp" class="input-control" required minlength="10" pattern="[0-9]+" placeholder=" " value="<?= old('no_whatsapp') ?>">
                        <label class="input-label">Nomor WhatsApp</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                    </div>

                    <div class="input-group span-full">
                        <input type="password" id="inputRegPw" name="password" class="input-control" required minlength="8" placeholder=" ">
                        <label class="input-label">Kata Sandi Akses</label>
                        <div class="input-neon-line"></div>
                        <div class="error-hint"></div>
                        <i class="fa-solid fa-eye icon-eye" id="toggleRegPw"></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="motivasi_hidup" class="input-control" placeholder=" " value="<?= old('motivasi_hidup') ?>">
                        <label class="input-label">Motivasi & Filosofi Hidup</label>
                        <div class="input-neon-line"></div>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" name="cita_cita" class="input-control" placeholder=" " value="<?= old('cita_cita') ?>">
                        <label class="input-label">Cita-Cita Terbesar</label>
                        <div class="input-neon-line"></div>
                    </div>

                    <div class="input-group">
                        <input type="text" name="akun_ig" class="input-control" placeholder=" " value="<?= old('akun_ig') ?>">
                        <label class="input-label">Instagram (@username)</label>
                        <div class="input-neon-line"></div>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" name="akun_tiktok" class="input-control" placeholder=" " value="<?= old('akun_tiktok') ?>">
                        <label class="input-label">TikTok (@username)</label>
                        <div class="input-neon-line"></div>
                    </div>

                    <div class="input-group span-full">
                        <label class="input-label" style="top:-20px; font-size:0.75rem; color:var(--text-secondary); letter-spacing:2px; font-weight:700;">Foto Profil Eksklusif</label>
                        <label class="upload-zone" id="uploadZone">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span style="color:var(--text-primary); font-weight:600; margin-bottom:5px;">Unggah Pasfoto Terbaik</span>
                            <span style="color:var(--text-secondary); font-size:0.8rem;">Tap/Klik di area ini untuk menelusuri galeri</span>
                            <input type="file" id="imageInput" accept="image/*" style="display:none;">
                        </label>
                        <div class="preview-area" id="previewArea">
                            <img id="imgPreview" src="" alt="Pratinjau Foto"><br>
                            <button type="button" class="btn-change-photo" onclick="document.getElementById('imageInput').click()">Ubah Pilihan Foto</button>
                        </div>
                        <input type="hidden" name="foto_profil_base64" id="fotoBase64">
                    </div>

                    <div class="input-group span-full checkbox-container" id="snkContainer">
                        <input type="checkbox" id="snk" name="snk" required>
                        <label for="snk">Saya menyatakan dengan sadar bahwa data ini benar dan menyetujui penyimpanannya ke dalam direktori angkatan Expedient.</label>
                        <div class="error-hint" style="bottom:-15px;"></div>
                    </div>

                    <div class="span-full btn-magnetic-wrapper">
                        <button type="submit" class="btn-prime magnetic-btn" id="btnSubmitForm">Selesaikan Inisiasi <i class="fa-solid fa-check"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="crop-modal" id="cropModal">
        <div class="crop-content">
            <h3 style="color:var(--text-primary); text-align:center; margin-bottom:20px; font-weight:700;">Sesuaikan Presisi Foto</h3>
            <div class="crop-img-wrap"><img id="cropTarget" src=""></div>
            <div class="crop-actions">
                <button type="button" class="crop-btn-cancel" id="btnCancelCrop">Batalkan</button>
                <button type="button" class="crop-btn-apply" id="btnApplyCrop">Terapkan Pemotongan</button>
            </div>
        </div>
    </div>

    <div class="auth-vault" id="faceModal">
        <div class="retina-container" id="retinaContainer">
            <div class="focus-ring" id="focusRing"></div>
            <!-- Leica Brackets -->
            <div class="bracket bracket-tl"></div>
            <div class="bracket bracket-tr"></div>
            <div class="bracket bracket-bl"></div>
            <div class="bracket bracket-br"></div>
            
            <div class="camera-frame">
                <video id="faceVideo" class="camera-feed" autoplay playsinline muted></video>
                <div class="lens-dust"></div>
                <div class="scan-line" id="scanLine"></div>
            </div>
        </div>

        <div class="liveness-indicator">
            <div class="live-node" id="step1"></div>
            <div class="live-node" id="step2"></div>
            <div class="live-node" id="step3"></div>
        </div>

        <div class="status-display" id="statusBadge">
            <div class="status-title">Protokol Keamanan VVIP</div>
            <div class="status-value" id="faceStatus">Memuat Kalibrasi...</div>
        </div>

        <button type="button" class="crop-btn-cancel" style="margin-top: 40px; font-size: 0.75rem;" onclick="closeFaceScanner()">BATALKAN INISIASI</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        // 1. KUNCI SMART INLINE VALIDATION
        const registerForm = document.getElementById('registerForm');
        const btnSubmit = document.getElementById('btnSubmitForm');
        
        // Membersihkan error saat diketik ulang
        const allInputs = document.querySelectorAll('.input-control, input[type="checkbox"]');
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                if(this.id === 'snk') document.getElementById('snkContainer').classList.remove('is-invalid');
            });
        });

        // =========================================================================
        // LOGIKA BARU: INTERSEPTOR FORM SUBMIT UNTUK FACE ID
        // =========================================================================
        registerForm.addEventListener('submit', function(e) {
            // Jika Form Tidak Valid (Ada yang kosong / salah)
            if (!this.checkValidity()) {
                e.preventDefault(); // Stop form dikirim
                
                let firstInvalidElement = null;
                const invalidInputs = this.querySelectorAll(':invalid');
                invalidInputs.forEach(input => {
                    if (input.id === 'snk') {
                        document.getElementById('snkContainer').classList.add('is-invalid');
                        const hint = document.getElementById('snkContainer').querySelector('.error-hint');
                        if(hint) hint.innerText = "Persetujuan ini wajib dicentang.";
                    } 
                    else {
                        input.classList.add('is-invalid');
                        const hint = input.parentElement.querySelector('.error-hint');
                        if (hint) {
                            if (input.validity.valueMissing) { hint.innerText = "Kolom ini wajib diisi."; } 
                            else if (input.validity.typeMismatch && input.type === 'email') { hint.innerText = "Gunakan format email yang sah (misal: nama@gmail.com)."; } 
                            else if (input.validity.tooShort) { hint.innerText = `Minimal harus ${input.getAttribute('minlength')} karakter.`; } 
                            else if (input.validity.patternMismatch) { hint.innerText = "Hanya boleh berisi angka."; } 
                            else { hint.innerText = "Format tidak sesuai."; }
                        }
                    }
                    if (!firstInvalidElement) firstInvalidElement = input;
                });

                if (firstInvalidElement) { firstInvalidElement.focus(); }

                btnSubmit.classList.add('shake-anim');
                if (navigator.vibrate) navigator.vibrate([100, 50, 100]); 
                setTimeout(() => btnSubmit.classList.remove('shake-anim'), 500);

            } else {
                // FORM SUDAH VALID! (Tidak ada error)
                // Cek apakah data wajah sudah ada di hidden input?
                const faceDataVal = document.getElementById('faceDataInput').value;
                
                if (!faceDataVal) {
                    // Jika data wajah KOSONG -> Cegat form, buka Modal Kamera!
                    e.preventDefault();
                    startFaceScanner();
                } else {
                    // Jika data wajah SUDAH ADA -> Lanjut kirim ke server (CI4)
                    btnSubmit.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan Matriks Wajah...';
                    btnSubmit.style.pointerEvents = 'none';
                    // Form akan otomatis ter-submit.
                }
            }
        });

        // =========================================================================
        // LOGIKA LIVENESS DETECTION (FACE-API.JS) DENGAN LEICA UI
        // =========================================================================
        let isFaceModelLoaded = false;
        let faceStreamRef = null;
        let faceCheckInterval = null;
        let scanAnim = null;
        
        const faceModal = document.getElementById('faceModal');
        const faceVideo = document.getElementById('faceVideo');
        const faceStatus = document.getElementById('faceStatus');
        const retinaContainer = document.getElementById('retinaContainer');
        const scanLine = document.getElementById('scanLine');
        const s1 = document.getElementById('step1');
        const s2 = document.getElementById('step2');
        const s3 = document.getElementById('step3');

        const updateTextFade = (text, color) => {
            gsap.to(faceStatus, {
                opacity: 0, y: -10, duration: 0.3,
                onComplete: () => {
                    faceStatus.innerText = text;
                    if(color) faceStatus.style.color = color;
                    gsap.fromTo(faceStatus, { y: 10 }, { opacity: 1, y: 0, duration: 0.4, ease: "power2.out" });
                }
            });
        };

        const updateHUD = (text, color, isScanning, step) => {
            if(faceStatus.innerText !== text) { updateTextFade(text, color); }
            
            if (isScanning && !scanAnim) {
                retinaContainer.classList.add('scanning');
                gsap.set(scanLine, { opacity: 1 });
                scanAnim = gsap.to(scanLine, { top: "100%", duration: 2, repeat: -1, yoyo: true, ease: "sine.inOut" });
            } else if (!isScanning && scanAnim) {
                retinaContainer.classList.remove('scanning');
                scanAnim.kill(); scanAnim = null;
                gsap.to(scanLine, { opacity: 0, duration: 0.3 });
            }

            s1.className = 'live-node'; s2.className = 'live-node'; s3.className = 'live-node';
            if (step === 1) { s1.classList.add('active'); }
            else if (step === 2) { s1.classList.add('done'); s2.classList.add('active'); }
            else if (step === 3) { s1.classList.add('done'); s2.classList.add('done'); s3.classList.add('active'); }
            else if (step === 4) { s1.classList.add('done'); s2.classList.add('done'); s3.classList.add('done'); }
        };

        async function startFaceScanner() {
            faceModal.style.display = 'flex';
            gsap.fromTo(faceModal, { opacity: 0 }, { opacity: 1, duration: 0.8 });
            
            updateHUD("MEMUAT KECERDASAN BUATAN...", "#d4af37", false, 0);

            if (!isFaceModelLoaded) {
                try {
                    const MODEL_URL = window.location.origin + '/assets/models';
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                        faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
                    ]);
                    isFaceModelLoaded = true;
                } catch (err) {
                    console.error(err);
                    updateHUD("ERROR GAGAL MEMUAT MODEL AI", "#ff3366", false, 0);
                    return;
                }
            }

            updateHUD("MENGAKSES OPTIK KAMERA...", "#d4af37", false, 1);
            try {
                faceStreamRef = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
                faceVideo.srcObject = faceStreamRef;
            } catch (err) {
                updateHUD("AKSES KAMERA DITOLAK", "#ff3366", false, 0);
                return;
            }

            faceVideo.onplay = () => {
                updateHUD("MEMBACA STRUKTUR WAJAH...", "#d4af37", true, 1);

                faceCheckInterval = setInterval(async () => {
                    const detection = await faceapi.detectSingleFace(faceVideo, new faceapi.TinyFaceDetectorOptions())
                                                   .withFaceLandmarks()
                                                   .withFaceExpressions()
                                                   .withFaceDescriptor();
                    
                    if (detection) {
                        if (detection.expressions.happy > 0.8) {
                            clearInterval(faceCheckInterval);
                            
                            updateHUD("VERIFIKASI SUKSES! MENGUNCI DATA...", "#00ff88", false, 4);
                            
                            const descriptorArray = Array.from(detection.descriptor);
                            document.getElementById('faceDataInput').value = JSON.stringify(descriptorArray);

                            if(faceStreamRef) faceStreamRef.getTracks().forEach(track => track.stop());

                            setTimeout(() => {
                                gsap.to(faceModal, { opacity: 0, duration: 0.5, onComplete: () => {
                                    faceModal.style.display = 'none';
                                    document.getElementById('btnSubmitForm').innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan Berkas...';
                                    registerForm.submit();
                                }});
                            }, 1500);
                        } else {
                            updateHUD("TERDETEKSI. TERSENYUM UNTUK VERIFIKASI", "#d4af37", true, 2);
                        }
                    } else {
                        updateHUD("WAJAH TIDAK TERLIHAT. POSISIKAN DI TENGAH.", "#ff3366", false, 1);
                    }
                }, 400);
            };
        }

        function closeFaceScanner() {
            if (faceCheckInterval) clearInterval(faceCheckInterval);
            if (faceStreamRef) faceStreamRef.getTracks().forEach(track => track.stop());
            gsap.to(faceModal, { opacity: 0, duration: 0.5, onComplete: () => {
                faceModal.style.display = 'none';
                if(scanAnim) { scanAnim.kill(); scanAnim = null; }
            }});
        }


        // =========================================================================
        // KODE LAMA: ANIMASI, THEME, DAN CROPPER (TIDAK DIUBAH)
        // =========================================================================
        const logoContainer = document.getElementById('logoContainer');
        function triggerLogoExplosion() {
            logoContainer.classList.remove('united');
            logoContainer.classList.add('exploding');
            setTimeout(() => {
                logoContainer.classList.remove('exploding');
                logoContainer.classList.add('united');
            }, 1000);
        }
        window.addEventListener('load', triggerLogoExplosion);

        const btnTheme = document.getElementById('btnTheme');
        const themeText = document.getElementById('themeText');
        const toggleIcon = document.getElementById('toggleIcon');
        btnTheme.addEventListener('click', () => {
            if (navigator.vibrate) navigator.vibrate(50);
            triggerLogoExplosion(); 
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            document.body.setAttribute('data-theme', isDark ? 'light' : 'dark');
            for(let i=0; i<20; i++) particlesArray.push(new Particle(window.innerWidth - 50, 50, Math.random()*3+1, -(Math.random()*4+2)));
            if (isDark) { themeText.innerText = 'Siang'; toggleIcon.className = 'fa-solid fa-sun'; } 
            else { themeText.innerText = 'Malam'; toggleIcon.className = 'fa-solid fa-moon'; }
        });

        document.getElementById('toggleRegPw').addEventListener('click', function() {
            const pw = document.getElementById('inputRegPw');
            pw.type = pw.type === 'password' ? 'text' : 'password';
            this.classList.toggle('fa-eye'); this.classList.toggle('fa-eye-slash');
        });

        const magneticBtns = document.querySelectorAll('.magnetic-btn');
        magneticBtns.forEach(btn => {
            btn.addEventListener('mousemove', function(e) {
                if(window.innerWidth > 768) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left - (rect.width/2);
                    const y = e.clientY - rect.top - (rect.height/2);
                    this.style.transition = 'none';
                    this.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px) scale(1.02)`;
                }
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transition = 'transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.27)';
                this.style.transform = `translate(0px, 0px) scale(1)`;
            });
        });

        const canvas = document.getElementById("particles-js");
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth; canvas.height = window.innerHeight;
        let particlesArray = []; let mouse = { x: null, y: null, radius: 100 };
        window.addEventListener('mousemove', e => { mouse.x = e.x; mouse.y = e.y; });
        window.addEventListener('mouseleave', () => { mouse.x = undefined; mouse.y = undefined; });
        class Particle {
            constructor(x, y, size, weight) { this.x = x; this.y = y; this.size = size; this.weight = weight; }
            update() {
                this.y -= this.weight;
                if (this.y < 0 - this.size) { this.y = canvas.height + this.size; this.x = Math.random() * canvas.width; }
                if (mouse.x != null) {
                    let dx = mouse.x - this.x; let dy = mouse.y - this.y; let distance = Math.sqrt(dx*dx + dy*dy);
                    if (distance < mouse.radius) {
                        const forceDirectionX = dx / distance; const forceDirectionY = dy / distance;
                        const force = (mouse.radius - distance) / mouse.radius;
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
        function initParticles() { particlesArray = []; for (let i = 0; i < 60; i++) particlesArray.push(new Particle(Math.random() * innerWidth, Math.random() * innerHeight, (Math.random() * 2) + 0.5, (Math.random() * 0.5) + 0.2)); }
        function animateParticles() { ctx.clearRect(0, 0, canvas.width, canvas.height); for (let i = 0; i < particlesArray.length; i++) { particlesArray[i].update(); particlesArray[i].draw(); } requestAnimationFrame(animateParticles); }
        initParticles(); animateParticles();
        window.addEventListener('resize', () => { canvas.width = innerWidth; canvas.height = innerHeight; initParticles(); });

        let cropper;
        const imgInput = document.getElementById('imageInput'), cropModal = document.getElementById('cropModal');
        const cropTarget = document.getElementById('cropTarget'), previewArea = document.getElementById('previewArea');
        const imgPreview = document.getElementById('imgPreview'), uploadZone = document.getElementById('uploadZone');

        imgInput.addEventListener('change', e => {
            if(e.target.files && e.target.files.length > 0) {
                const reader = new FileReader();
                reader.onload = ev => {
                    cropTarget.src = ev.target.result; cropModal.style.display = 'flex';
                    if(cropper) cropper.destroy();
                    cropper = new Cropper(cropTarget, { aspectRatio: 1, viewMode: 2, background: false });
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
        document.getElementById('btnCancelCrop').addEventListener('click', () => { cropModal.style.display = 'none'; imgInput.value = ''; });
        document.getElementById('btnApplyCrop').addEventListener('click', () => {
            if(!cropper) return;
            const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
            const b64 = canvas.toDataURL('image/jpeg', 0.8);
            imgPreview.src = b64; previewArea.style.display = 'block'; uploadZone.style.display = 'none';
            document.getElementById('fotoBase64').value = b64; cropModal.style.display = 'none';
        });
    </script>
</body>
</html>