<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Museum Utama Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ================= 1. STAGE (MENYATU DENGAN TEMPLATE) ================= */
    .mecha-stage {
        position: relative; 
        width: 100%; 
        height: 100vh;
        background: transparent; 
        overflow: hidden; 
        display: flex;
        justify-content: center; 
        align-items: center;
        perspective: 2500px; 
        user-select: none; 
        z-index: 10; 
    }

    .god-rays {
        position: absolute; 
        top: -20%; 
        left: 0; 
        width: 100%; 
        height: 120%;
        background: radial-gradient(ellipse at 50% -10%, rgba(212,175,55,0.15) 0%, rgba(212,175,55,0.02) 40%, transparent 70%);
        pointer-events: none; 
        z-index: 2; 
        mix-blend-mode: screen;
        transform-origin: top center; 
        will-change: transform, opacity;
    }

    :root[data-theme="light"] .god-rays { mix-blend-mode: normal; opacity: 0.6; }

    .monumental-text {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        font-family: 'Playfair Display', serif; font-size: 15vw; font-weight: 900;
        color: rgba(212,175,55,0.08); -webkit-text-stroke: 3px rgba(212,175,55,0.4); 
        text-shadow: 0 0 20px rgba(212,175,55,0.1); letter-spacing: 2vw; 
        white-space: nowrap; pointer-events: none; z-index: 2; will-change: transform;
    }

    :root[data-theme="light"] .monumental-text {
        color: rgba(0,0,0,0.03); -webkit-text-stroke: 3px rgba(0,0,0,0.2); text-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    #constellationCanvas { position: absolute; inset: 0; pointer-events: none; z-index: 12; opacity: 1; }

    /* ================= 2. KANVAS LOGO & SERPIHAN ================= */
    .logo-utuh-container {
        position: absolute; top: 50%; left: 50%; width: 800px; height: 450px; 
        margin-top: -225px; margin-left: -400px; z-index: 10; display: flex; 
        justify-content: center; align-items: center; cursor: grab; will-change: filter; transition: filter 0.1s ease-out;
    }
    .logo-utuh-container:active { cursor: grabbing; }
    
    .shards-universe { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 15; }

    .shard-wrapper {
        position: absolute; top: 50%; left: 50%; width: 800px; height: 450px; 
        margin-top: -225px; margin-left: -400px; display: flex; justify-content: center; align-items: center;
        transform-origin: center center; opacity: 0; pointer-events: none;
        will-change: transform, opacity, filter; transition: filter 0.1s ease-out;
    }

    canvas, .shard-static, #fullLogoStatic { position: absolute; width: 100%; height: 100%; object-fit: contain; }
    .shard-static, #fullLogoStatic { display: none; z-index: 1; } 
    canvas { display: block; z-index: 2; }

    .mecha-stage.is-scattered .shard-wrapper { pointer-events: auto; cursor: grab; }
    .mecha-stage.is-scattered .shard-wrapper:active { cursor: grabbing; }
    .mecha-stage.is-scattered .shard-wrapper:hover { z-index: 50; transition: filter 0.3s ease; }

    .merge-flash { position: absolute; inset: 0; background: var(--text-primary); opacity: 0; z-index: 900; pointer-events: none; mix-blend-mode: overlay; }

    /* ================= 3. UI CONTROLS ================= */
    .seq-loader {
        position: absolute; inset: 0; background: var(--bg-main); z-index: 9999;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        color: #d4af37; font-family: 'Courier New', monospace; transition: opacity 0.8s;
    }
    .progress-bar { width: 300px; height: 2px; background: rgba(212,175,55,0.2); margin-top: 20px; }
    .progress-fill { width: 0%; height: 100%; background: #d4af37; transition: width 0.1s; }
    .loader-text { letter-spacing: 5px; font-size: 0.9rem; text-transform: uppercase; text-align: center; line-height: 1.5; }

    .hud-controls { position: absolute; bottom: 40px; z-index: 200; display: flex; flex-direction: column; align-items: center; gap: 15px; pointer-events: none; }
    .hud-hint { color: var(--text-secondary); font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase; font-weight: 600; text-shadow: 0 0 10px var(--bg-main); transition: 0.3s; }

    .btn-mecha {
        pointer-events: auto; background: var(--glass-bg); backdrop-filter: var(--glass-blur);
        border: 1px solid rgba(212, 175, 55, 0.5); color: #d4af37; padding: 15px 40px; border-radius: 5px; 
        text-transform: uppercase; letter-spacing: 4px; font-weight: 800; cursor: pointer; transition: 0.4s;
    }
    .btn-mecha:hover { background: #d4af37; color: var(--bg-main); transform: translateY(-3px); box-shadow: 0 10px 30px rgba(212,175,55,0.3); }

    @keyframes bounceIndicator { 0%, 20%, 50%, 80%, 100% { transform: translate(-50%, 0); } 40% { transform: translate(-50%, -15px); } 60% { transform: translate(-50%, -7px); } }

    /* ================= 4. PHILOSOPHY MODAL ================= */
    .phil-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(10px); z-index: 9000; display: flex; justify-content: center; align-items: center; opacity: 0; pointer-events: none; transition: 0.4s; }
    :root[data-theme="light"] .phil-modal { background: rgba(255,255,255,0.6); }
    .phil-modal.active { opacity: 1; pointer-events: auto; }
    .phil-content { background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-border); padding: 40px; border-radius: 15px; max-width: 500px; text-align: center; transform: scale(0.9); transition: 0.4s; box-shadow: var(--glass-shadow); }
    .phil-modal.active .phil-content { transform: scale(1); }
    .phil-title { color: #d4af37; font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 15px; text-transform: uppercase; }
    .phil-desc { color: var(--text-primary); line-height: 1.7; font-size: 0.95rem; }
    
    @media (max-width: 768px) {
        .mecha-stage { height: calc(100vh - 75px); }
        .btn-mecha { padding: 12px 25px; font-size: 0.8rem; }
        .phil-content { padding: 30px 20px; width: 90%; }
    }

    /* ================= MUSEUM HALLS (RUANG PAMERAN) ================= */
    .museum-halls { position: relative; z-index: 20; width: 100%; overflow-x: hidden; padding-bottom: 10vh; background: transparent; }
    .hall-section { padding: clamp(50px, 10vh, 120px) clamp(20px, 5vw, 80px); max-width: 1400px; margin: 0 auto; }
    .section-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 3.5rem); color: #d4af37; text-align: center; margin-bottom: clamp(30px, 6vh, 60px); text-transform: uppercase; letter-spacing: 2px; }
    
    .glass-panel { background: var(--glass-bg, rgba(4, 10, 7, 0.6)); backdrop-filter: blur(20px); border: 1px solid var(--glass-border, rgba(0, 255, 136, 0.15)); border-radius: 16px; padding: 30px; }
    :root[data-theme="light"] .glass-panel { background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(212, 175, 55, 0.4); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

    .epigraph-section { text-align: center; min-height: 50vh; display: flex; align-items: center; justify-content: center; }
    .grand-text { font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 4vw, 3rem); line-height: 1.4; font-weight: 500; color: var(--text-main, #f0f5f2); }
    :root[data-theme="light"] .grand-text { color: var(--text-main, #041008); }
    .highlight-gold { color: #d4af37; font-style: italic; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; text-align: center; }
    .stat-number { font-size: clamp(3rem, 6vw, 5rem); color: #d4af37; font-weight: 900; margin-bottom: 10px; font-family: 'Playfair Display', serif; }
    .stat-label { font-size: clamp(0.9rem, 1.5vw, 1.1rem); color: var(--text-muted, #5e7a6b); text-transform: uppercase; letter-spacing: 3px; font-weight: 600; }
    :root[data-theme="light"] .stat-label { color: var(--text-muted, #6a8275); }

    .horizontal-scroll-snap { display: flex; gap: 20px; overflow-x: auto; padding-bottom: 30px; scroll-snap-type: x mandatory; scrollbar-width: none; -ms-overflow-style: none; }
    .horizontal-scroll-snap::-webkit-scrollbar { display: none; }
    .echo-frame { flex: 0 0 clamp(280px, 70vw, 400px); scroll-snap-align: center; position: relative; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border); aspect-ratio: 4/5; filter: grayscale(100%); transition: 0.5s cubic-bezier(0.25, 1, 0.5, 1); cursor: pointer; }
    .echo-frame:hover, .echo-frame:active { filter: grayscale(0%); transform: scale(1.02); box-shadow: 0 20px 40px rgba(212, 175, 55, 0.2); }
    .echo-frame img { width: 100%; height: 100%; object-fit: cover; }
    .echo-caption { position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: #fff; font-family: 'Playfair Display', serif; font-size: 1.2rem; transform: translateY(100%); transition: 0.4s; }
    .echo-frame:hover .echo-caption, .echo-frame:active .echo-caption { transform: translateY(0); }

    .news-list { display: flex; flex-direction: column; gap: 20px; max-width: 800px; margin: 0 auto; }
    .news-item { padding: 25px 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; flex-direction: column; gap: 10px; transition: 0.3s; }
    .news-item:hover { transform: translateX(10px); }
    .news-date { color: #d4af37; font-family: 'Courier New', monospace; font-size: 0.85rem; letter-spacing: 2px; }
    .news-title { font-size: clamp(1.2rem, 2.5vw, 1.8rem); font-family: 'Playfair Display', serif; color: var(--text-main, #f0f5f2); }
    :root[data-theme="light"] .news-title { color: var(--text-main, #041008); }
    .news-link { color: var(--text-muted, #5e7a6b); text-decoration: none; font-size: 0.9rem; font-weight: 600; letter-spacing: 1px; transition: 0.3s; margin-top: 10px; display: inline-block; }
    .news-item:hover .news-link { color: #d4af37; letter-spacing: 3px; }

    .curator-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
    .curator-card { text-align: center; padding: 40px 20px; transition: 0.4s; }
    .curator-card:hover { transform: translateY(-10px); border-color: #d4af37; }
    .curator-img-wrap { width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 20px; overflow: hidden; border: 2px solid rgba(212, 175, 55, 0.5); padding: 5px; }
    .curator-img-wrap img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
    .curator-card h4 { color: #d4af37; font-size: 1.3rem; margin-bottom: 5px; }
    .curator-card p { color: var(--text-muted, #5e7a6b); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; }
    :root[data-theme="light"] .curator-card p { color: var(--text-muted, #6a8275); }

    .monolith-section { display: flex; justify-content: center; padding-top: 10vh; padding-bottom: 10vh; }
    .monolith-pillar { border-left: 2px solid rgba(212, 175, 55, 0.3); padding-left: clamp(20px, 5vw, 50px); }
    .gold-engraving { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 4rem); color: transparent; -webkit-text-stroke: 1px rgba(212, 175, 55, 0.5); line-height: 1.5; transition: 0.5s; cursor: pointer; }
    .gold-engraving:hover, .gold-engraving.is-active { color: #d4af37; -webkit-text-stroke: 0; text-shadow: 0 0 20px rgba(212, 175, 55, 0.5); transform: translateX(10px); }

    .golden-timeline { position: relative; max-width: 800px; margin: 0 auto; padding-left: 20px; }
    .golden-timeline::before { content: ''; position: absolute; left: 0; top: 0; width: 2px; height: 100%; background: linear-gradient(to bottom, #d4af37, transparent); }
    
    .ledger-section { max-width: 600px; margin: 0 auto; text-align: center; }
    .ledger-form { display: flex; flex-direction: column; gap: 30px; }
    .luxury-input { width: 100%; background: transparent; border: none; border-bottom: 1px solid rgba(212, 175, 55, 0.4); color: var(--text-main, #f0f5f2); font-size: 1.1rem; padding: 15px 0; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif; resize: none; }
    :root[data-theme="light"] .luxury-input { color: var(--text-main, #041008); border-bottom: 1px solid rgba(212, 175, 55, 0.6); }
    .luxury-input:focus { border-bottom-color: #d4af37; box-shadow: 0 10px 10px -10px rgba(212, 175, 55, 0.3); }
    .luxury-input::placeholder { color: var(--text-muted, #5e7a6b); font-style: italic; }
    .btn-stamp { background: transparent; border: 1px solid #d4af37; color: #d4af37; padding: 15px 40px; font-size: 0.9rem; letter-spacing: 4px; text-transform: uppercase; font-weight: 700; cursor: pointer; transition: 0.4s; border-radius: 4px; display: inline-block; margin-top: 20px; }
    .btn-stamp:hover { background: #d4af37; color: #030504; transform: translateY(-3px); box-shadow: 0 15px 25px rgba(212, 175, 55, 0.2); }
    :root[data-theme="light"] .btn-stamp:hover { color: #ffffff; }

    /* GSAP SCROLLTRIGGER CLASSES */
    .reveal-up { visibility: hidden; opacity: 0; transform: translateY(50px); }
    .timeline-node { position: relative; margin-bottom: 50px; padding-left: 40px; visibility: hidden; opacity: 0; transform: translateY(30px); }
    .node-dot { position: absolute; left: -6px; top: 0; width: 14px; height: 14px; background: #030504; border: 2px solid #d4af37; border-radius: 50%; box-shadow: 0 0 10px #d4af37; }
    :root[data-theme="light"] .node-dot { background: #f0f5f3; }
    /* MODAL PANCA JIWA */
    .jiwa-modal {
        position: fixed; inset: 0; background: rgba(5, 5, 5, 0.98); backdrop-filter: blur(20px);
        z-index: 99999; display: flex; flex-direction: column; justify-content: center; align-items: center;
        opacity: 0; pointer-events: none; transition: 0.8s cubic-bezier(0.25, 1, 0.5, 1); overflow: hidden;
    }
    :root[data-theme="light"] .jiwa-modal {
        background: rgba(240, 245, 243, 0.98);
    }
    .jiwa-modal.active { opacity: 1; pointer-events: auto; }
    
    .jiwa-anim-container {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        display: flex; justify-content: center; align-items: center; z-index: 1;
        perspective: 1000px;
    }
    
    .jiwa-content {
        position: relative; z-index: 10; max-width: 800px; padding: 50px;
        text-align: center; opacity: 0; transform: translateY(50px); transition: 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        background: rgba(10,10,10,0.6); border: 1px solid rgba(212,175,55,0.3); border-radius: 20px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.9), inset 0 0 30px rgba(212,175,55,0.05);
        backdrop-filter: blur(10px);
    }
    :root[data-theme="light"] .jiwa-content {
        background: rgba(255,255,255,0.7); box-shadow: 0 30px 60px rgba(0,0,0,0.1), inset 0 0 30px rgba(212,175,55,0.1);
    }
    .jiwa-content.show { opacity: 1; transform: translateY(0); }
    
    .jiwa-title {
        font-family: 'Playfair Display', serif; font-size: 3.5rem; color: #d4af37;
        margin-bottom: 25px; letter-spacing: 5px; text-transform: uppercase;
        text-shadow: 0 0 30px rgba(212,175,55,0.4);
    }
    .jiwa-desc {
        font-family: 'Inter', sans-serif; font-size: 1.05rem; color: #ddd; line-height: 1.8;
        text-align: justify;
    }
    :root[data-theme="light"] .jiwa-desc { color: #333; }

    /* GOD-TIER ANIMATION HELPERS */
    .god-tier-element { position: absolute; pointer-events: none; }
    .god-tier-svg { overflow: visible; position: absolute; }

    /* SOVEREIGN ARCHIVES MODAL */
    .archive-modal {
        position: fixed; inset: 0; z-index: 99999; display: flex; justify-content: center; align-items: center;
        opacity: 0; pointer-events: none; transition: 0.5s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .archive-modal.active { opacity: 1; pointer-events: auto; }
    .archive-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); }
    :root[data-theme="light"] .archive-backdrop { background: rgba(255,255,255,0.7); }
    
    .archive-paper {
        position: relative; width: 90%; max-width: 800px; max-height: 85vh; overflow-y: auto;
        background: var(--glass-bg); color: var(--text-primary); padding: 50px; 
        border: 1px solid var(--glass-border); border-radius: 20px;
        box-shadow: var(--glass-shadow); transform: translateY(50px) scale(0.95); transition: 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    }
    .archive-modal.active .archive-paper { transform: translateY(0) scale(1); }
    
    .archive-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); padding-bottom: 20px; margin-bottom: 30px; font-weight: 500; font-family: 'Inter', sans-serif; letter-spacing: 3px; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; }
    .archive-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 30px; color: var(--gold-premium); line-height: 1.2; font-weight: 400; text-align: center; }
    .archive-body p { line-height: 1.8; margin-bottom: 20px; font-size: 1.05rem; font-family: 'Inter', sans-serif; color: var(--text-primary); font-weight: 300; text-align: justify; }
    
    /* VVIP MOBILE RESPONSIVE (BOTTOM SHEETS) */
    @media (max-width: 768px) {
        .archive-modal { align-items: flex-end; }
        .archive-paper { width: 100%; border-radius: 30px 30px 0 0; padding: 40px 25px; transform: translateY(100%) scale(1); max-height: 90vh; }
        .archive-paper::before { content: ''; position: absolute; top: 15px; left: 50%; transform: translateX(-50%); width: 40px; height: 4px; background: var(--glass-border); border-radius: 10px; }
        .archive-title { font-size: 1.8rem; }
        
        .jiwa-modal { align-items: flex-end; justify-content: flex-end; }
        .jiwa-content { width: 100%; border-radius: 30px 30px 0 0; padding: 40px 25px; transform: translateY(100%); margin-bottom: 0; }
        .jiwa-content.show { transform: translateY(0); }
        .jiwa-anim-container { height: 50vh; top: 0; }
    }

    /* SWIPE TO SEAL ELEMENT (MOBILE) */
    .swipe-seal-container { position: relative; width: 100%; height: 60px; background: rgba(212,175,55,0.05); border: 1px solid var(--glass-border); border-radius: 30px; overflow: hidden; margin-top: 30px; display: none; }
    @media (max-width: 768px) {
        .swipe-seal-container { display: block; }
        .btn-stamp { display: none; }
    }
    .swipe-text { position: absolute; inset: 0; display: flex; justify-content: center; align-items: center; color: var(--gold-premium); font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase; font-weight: 600; pointer-events: none; z-index: 1; transition: 0.3s; opacity: 0.7; }
    .swipe-knob { position: absolute; top: 5px; left: 5px; width: 50px; height: 50px; background: var(--gold-premium); border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #030504; font-size: 1.2rem; cursor: grab; z-index: 2; box-shadow: 0 0 15px rgba(212,175,55,0.4); transition: transform 0.1s; }
    .swipe-knob:active { cursor: grabbing; transform: scale(0.95); }
    .swipe-fill { position: absolute; top: 0; left: 0; height: 100%; width: 0; background: rgba(212,175,55,0.15); z-index: 0; }

    /* HAPTIC HOLD EFFECT */
    .haptic-ring { position: fixed; border: 2px solid var(--gold-premium); border-radius: 50%; pointer-events: none; opacity: 0; transform: scale(0); z-index: 999999; box-shadow: 0 0 20px rgba(212,175,55,0.5); }
    
    /* GANTI FONT SIGNATURE */
    .input-signature { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.5rem !important; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="seq-loader" id="loader">
    <div class="loader-text">MENYIAPKAN RUANG PAMERAN<br><span style="color:#d4af37; font-size:2rem; font-weight:bold;" id="loadPercent">0%</span></div>
    <div class="progress-bar"><div class="progress-fill" id="loadBar"></div></div>
</div>

<div class="mecha-stage" id="stage">
    
    <div class="god-rays" id="godRays"></div>
    <div class="monumental-text" id="monumentalText">E X P E D I E N T</div>

    <canvas id="constellationCanvas"></canvas>

    <div class="logo-utuh-container hover-trigger" id="fullLogoBox">
        <canvas id="fullLogoCanvas" width="800" height="450"></canvas>
        <img src="/images/logo-utuh.png" id="fullLogoStatic" alt="Static">
    </div>
    
    <div class="shards-universe" id="shardsContainer"></div>
    <div class="merge-flash" id="flashEffect"></div>
    
    <div class="hud-controls">
        <div class="hud-hint" id="hudHint"><i class="fa-solid fa-arrows-left-right"></i> Tahan & Geser Untuk Memutar</div>
        <button class="btn-mecha hover-trigger" id="btnAction"><i class="fa-solid fa-expand"></i> Pencar Formasi</button>
    </div>

    <i class="fa-solid fa-chevron-down scroll-indicator" style="position: absolute; bottom: 5vh; left: 50%; transform: translateX(-50%); color: #d4af37; font-size: 2rem; animation: bounceIndicator 2s infinite; z-index: 20; opacity: 0.7;"></i>
</div>

<div class="phil-modal" id="philModal">
    <div class="phil-content">
        <h2 class="phil-title" id="modalTitle">Judul</h2>
        <p class="phil-desc" id="modalDesc">Deskripsi filosofi.</p>
        <button class="btn-mecha hover-trigger" style="margin-top:25px; padding:10px 25px; font-size:0.8rem;" onclick="closeModal()">TUTUP</button>
    </div>
</div>

<main class="museum-halls">

    <section class="hall-section epigraph-section">
        <h1 class="grand-text reveal-up">
            Kami bukan sekadar angkatan.<br>
            Kami adalah <span class="highlight-gold">barisan pelopor</span> yang lahir dari rahim Arrisalah,<br>
            dibentuk oleh waktu, dipersatukan oleh takdir.
        </h1>
    </section>

    <section class="hall-section">
        <div class="stats-grid">
            <div class="stat-card glass-panel reveal-up">
                <h3 class="stat-number"><span class="gsap-counter" data-target="124">0</span>+</h3>
                <p class="stat-label">Entitas Expedient</p>
            </div>
            <div class="stat-card glass-panel reveal-up">
                <h3 class="stat-number"><span class="gsap-counter" data-target="34">0</span></h3>
                <p class="stat-label">Wilayah Sebaran</p>
            </div>
            <div class="stat-card glass-panel reveal-up">
                <h3 class="stat-number"><span class="gsap-counter" data-target="2025">0</span></h3>
                <p class="stat-label">Tahun Kebangkitan</p>
            </div>
        </div>
    </section>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Lorong Kenangan</h2>
        <div class="horizontal-scroll-snap reveal-up">
            <div class="echo-frame">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=400&auto=format&fit=crop" alt="Kenangan">
                <div class="echo-caption">Kenangan Masa Perjuangan</div>
            </div>
            <div class="echo-frame">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=400&auto=format&fit=crop" alt="Visi">
                <div class="echo-caption">Deklarasi Visi Expedient</div>
            </div>
            <div class="echo-frame">
                <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=400&auto=format&fit=crop" alt="Keakraban">
                <div class="echo-caption">Malam Keakraban Solidaritas</div>
            </div>
        </div>
    </section>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Manuskrip Sejarah</h2>
        <div class="news-list">
            <article class="news-item reveal-up press-hold-trigger" data-archive="visi">
                <span class="news-date">30 MARET 2026</span>
                <h3 class="news-title">Penetapan Visi Angkatan</h3>
                <a href="javascript:void(0)" class="news-link"><span class="desktop-hint">BACA DOKUMEN</span><span class="mobile-hint" style="display:none;">TAHAN UNTUK MEMBUKA</span> <i class="fa-solid fa-book-open"></i></a>
            </article>
            <article class="news-item reveal-up press-hold-trigger" data-archive="simpul">
                <span class="news-date">15 FEBRUARI 2026</span>
                <h3 class="news-title">Simpul Kesucian: Menjaga Nilai-Nilai Arrisalah</h3>
                <a href="javascript:void(0)" class="news-link"><span class="desktop-hint">BACA DOKUMEN</span><span class="mobile-hint" style="display:none;">TAHAN UNTUK MEMBUKA</span> <i class="fa-solid fa-book-open"></i></a>
            </article>
        </div>
    </section>

    <!-- Modal Sovereign Archives -->
    <div class="archive-modal" id="archiveModal">
        <div class="archive-backdrop" onclick="closeArchive()"></div>
        <div class="archive-paper" id="archivePaper">
            <div class="archive-header">
                <div>SOVEREIGN ARCHIVE</div>
                <div id="arcDate">---</div>
            </div>
            <h1 class="archive-title" id="arcTitle">TITLE</h1>
            <div class="archive-body" id="arcBody">
                <!-- Content injected here -->
            </div>
            <button class="btn-stamp" style="margin-top:40px; display:block; width:100%; border-color: var(--glass-border); color: var(--text-primary);" onclick="closeArchive()">TUTUP MANUSKRIP</button>
        </div>
    </div>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Para Kurator</h2>
        <div class="curator-grid">
            <div class="curator-card glass-panel reveal-up">
                <div class="curator-img-wrap"><img src="https://ui-avatars.com/api/?name=Ketua+Angkatan&background=d4af37&color=000" alt="Ketua"></div>
                <div class="curator-info">
                    <h4>Algojo</h4>
                    <p>Ketua Angkatan</p>
                </div>
            </div>
            <div class="curator-card glass-panel reveal-up">
                <div class="curator-img-wrap"><img src="https://ui-avatars.com/api/?name=Divisi+Data&background=d4af37&color=000" alt="Sekretaris"></div>
                <div class="curator-info">
                    <h4>Nur Taufiqi</h4>
                    <p>Presiden Keren</p>
                </div>
            </div>
        </div>
    </section>

    <section class="hall-section monolith-section">
        <div class="monolith-pillar">
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('keikhlasan')">Keikhlasan</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('kesederhanaan')">Kesederhanaan</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('kemandirian')">Kemandirian</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('ukhuwah')">Ukhuwwah Islamiyyah</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('kebebasan')">Kebebasan</h2>
        </div>
    </section>

    <!-- Modal Khusus Panca Jiwa -->
    <div class="jiwa-modal" id="jiwaModal">
        <div class="jiwa-anim-container" id="jiwaAnimContainer">
            <!-- Animasi di-inject lewat JS -->
        </div>
        <div class="jiwa-content" id="jiwaContent">
            <h2 class="jiwa-title" id="jiwaTitle">Judul</h2>
            <div class="jiwa-desc" id="jiwaDesc">Penjelasan...</div>
            <button class="btn-stamp" style="margin-top: 30px; font-size: 0.8rem; padding: 10px 20px;" onclick="closeJiwa()">Tutup Penjelasan</button>
        </div>
    </div>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Garis Waktu</h2>
        <div class="golden-timeline">
            <div class="timeline-node">
                <div class="node-dot"></div>
                <div class="node-content glass-panel">
                    <span class="node-year">AWAL MULA</span>
                    <p>Angkatan Expedient pertama kali menapakkan jejaknya di bumi Arrisalah, mengikat janji untuk menjadi barisan pelopor peradaban.</p>
                </div>
            </div>
            <div class="timeline-node">
                <div class="node-dot"></div>
                <div class="node-content glass-panel">
                    <span class="node-year">MASA PENEMPAAN</span>
                    <p>Melewati berbagai ujian dan dinamika pondok yang membentuk mental baja, kemandirian, serta ukhuwah islamiyah yang tak tergoyahkan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="hall-section ledger-section reveal-up">
        <h2 class="section-title" style="margin-bottom: 20px;">Buku Tamu Eksklusif</h2>
        <p style="color: var(--text-muted, #5e7a6b); margin-bottom: 40px; font-size: 0.9rem;">Segel kehadiran Anda di dalam sejarah peradaban.</p>
        
        <form action="/beranda/simpan_pesan" method="POST" class="ledger-form" id="ledgerForm">
            <?= csrf_field() ?>
            <input type="text" name="nama" class="luxury-input input-signature" placeholder="Tanda Tangan (Nama)" required>
            <textarea name="pesan" class="luxury-input" placeholder="Tuliskan pesan berharga Anda..." rows="2" required></textarea>
            <div>
                <button type="submit" class="btn-stamp" id="desktopSubmitBtn">STEMPEL KEHADIRAN</button>
                
                <!-- Tuas Segel Emas (Mobile Swipe) -->
                <div class="swipe-seal-container" id="swipeSealContainer">
                    <div class="swipe-fill" id="swipeFill"></div>
                    <div class="swipe-text" id="swipeText">GESER UNTUK MENYEGEL <i class="fa-solid fa-arrow-right" style="margin-left:10px;"></i></div>
                    <div class="swipe-knob" id="swipeKnob"><i class="fa-solid fa-fingerprint"></i></div>
                </div>
            </div>
        </form>
    </section>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.config({ force3D: true });
        gsap.registerPlugin(ScrollTrigger);

        const TOTAL_FRAMES = 192; 
        let isScattered = false;
        let isAnimating = false; 
        let isPlaying = false;

        const frameData = { full: 0, shards: {} };
        const basePath = '/assets/sequence/'; 

        // =========================================================
        // TRACK MOUSE UNTUK EFEK RIPPLE REPULSE (DIPERBAIKI)
        // =========================================================
        let mouseX = -1000, mouseY = -1000;
        window.addEventListener('mousemove', (e) => {
            const stage = document.getElementById('stage');
            if (stage) {
                const rect = stage.getBoundingClientRect();
                mouseX = e.clientX - rect.left; 
                mouseY = e.clientY - rect.top;
            }
        });

        // =========================================================
        // SPATIAL CINEMATIC SOUNDSCAPE
        // =========================================================
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        let audioCtx;
        
        const initAudio = () => { 
            if(!audioCtx) audioCtx = new AudioContext(); 
            if(audioCtx.state === 'suspended') audioCtx.resume(); 
        };

        const playTick = (velocity) => {
            if(!audioCtx) return;
            const osc = audioCtx.createOscillator(); 
            const gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.type = 'sine'; osc.frequency.setValueAtTime(400 + velocity * 10, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(10, audioCtx.currentTime + 0.03);
            gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.03);
            osc.start(); osc.stop(audioCtx.currentTime + 0.04);
        };

        const playSwoosh = () => {
            if(!audioCtx) return;
            const osc = audioCtx.createOscillator(); 
            const gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.type = 'triangle'; osc.frequency.setValueAtTime(100, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(800, audioCtx.currentTime + 0.5);
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.25);
            gain.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 0.5);
            osc.start(); osc.stop(audioCtx.currentTime + 0.5);
        };

        const playBassDrop = () => {
            if(!audioCtx) return;
            const osc = audioCtx.createOscillator(); 
            const gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.type = 'sine'; osc.frequency.setValueAtTime(150, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(20, audioCtx.currentTime + 2);
            gain.gain.setValueAtTime(0.8, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 2);
            osc.start(); osc.stop(audioCtx.currentTime + 2);
        };

        // =========================================================
        // EXPEDIENT CORE (236 PARTIKEL KRISTAL EMAS) 
        // =========================================================
        let coreParticles = [];
        const initCore = () => {
            coreParticles = [];
            const stage = document.getElementById('stage');
            const cw = stage ? stage.offsetWidth : window.innerWidth;
            const ch = stage ? stage.offsetHeight : window.innerHeight;

            for(let i=0; i<236; i++) {
                let r = Math.random() * 180 + 80;
                let g = Math.random() * 0.5 + 0.2;
                let s = Math.random() * 0.015 + 0.005;
                coreParticles.push({
                    baseX: cw / 2, baseY: ch / 2, angle: Math.random() * Math.PI * 2,
                    radius: r, baseRadius: r, speed: s, baseSpeed: s, size: Math.random() * 2.5 + 1,
                    glow: g, baseGlow: g, offsetX: 0, offsetY: 0 
                });
            }
        };
        initCore();
        window.addEventListener('resize', initCore);

        // =========================================================
        // KANVAS CONSTELLATION & PARTIKEL
        // =========================================================
        const constelCanvas = document.getElementById('constellationCanvas');
        const ctxConstel = constelCanvas.getContext('2d');
        
        const resizeCanvas = () => { 
            const stage = document.getElementById('stage');
            constelCanvas.width = stage.offsetWidth; constelCanvas.height = stage.offsetHeight; 
        };
        resizeCanvas(); window.addEventListener('resize', resizeCanvas);

        const drawConstellation = () => {
            ctxConstel.clearRect(0, 0, constelCanvas.width, constelCanvas.height);
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            const time = Date.now();

            // 1. Render Partikel Latar Belakang
            coreParticles.forEach(p => {
                p.angle += p.speed;
                let targetX = p.baseX + Math.cos(p.angle) * p.radius;
                let targetY = p.baseY + Math.sin(p.angle) * (p.radius * 0.5); 

                if (isScattered) {
                    let dx = mouseX - (targetX + p.offsetX);
                    let dy = mouseY - (targetY + p.offsetY);
                    let dist = Math.sqrt(dx*dx + dy*dy);
                    if (dist < 150) { 
                        let force = (150 - dist) / 150;
                        p.offsetX -= (dx/dist) * force * 15; 
                        p.offsetY -= (dy/dist) * force * 15;
                    }
                }

                p.offsetX += (0 - p.offsetX) * 0.05;
                p.offsetY += (0 - p.offsetY) * 0.05;

                let drawX = targetX + p.offsetX;
                let drawY = targetY + p.offsetY;

                ctxConstel.beginPath();
                ctxConstel.arc(drawX, drawY, p.size, 0, Math.PI*2);
                
                let glowRatio = p.glow / p.baseGlow;

                if (isLight) {
                    let shimmer = Math.sin(time / 150 + p.angle) * 0.5 + 0.5;
                    let alpha = (0.5 + shimmer * 0.5) * glowRatio;
                    ctxConstel.fillStyle = `rgba(255, 215, 0, ${alpha})`;
                    ctxConstel.shadowBlur = (10 + shimmer * 15) * glowRatio;
                    ctxConstel.shadowColor = `rgba(255, 215, 0, ${alpha})`;
                } else {
                    let alpha = p.glow + (0.3 * glowRatio);
                    ctxConstel.fillStyle = `rgba(212, 175, 55, ${alpha})`;
                    ctxConstel.shadowBlur = 10 * glowRatio;
                    ctxConstel.shadowColor = '#d4af37';
                }
                ctxConstel.fill();
                ctxConstel.shadowBlur = 0; 
            });

            // 2. Render Garis Konstelasi Dinamis (Super Duper Upgrade)
            if(isScattered || isAnimating) {
                ctxConstel.strokeStyle = isLight ? 'rgba(212, 175, 55, 0.4)' : 'rgba(212, 175, 55, 0.3)';
                ctxConstel.shadowBlur = 8;
                ctxConstel.shadowColor = '#d4af37';
                ctxConstel.lineWidth = 1.5; 
                ctxConstel.lineJoin = "round";
                ctxConstel.beginPath();
                
                const cx = constelCanvas.width / 2; 
                const cy = constelCanvas.height / 2;

                // Membaca X & Y animasi DOM secara realtime!
                const dynamicPositions = assetsData.shards.map(s => {
                    const el = document.getElementById(`shardWrap_${s.id}`);
                    const currX = gsap.getProperty(el, "x") || (isAnimating ? 0 : s.tx);
                    const currY = gsap.getProperty(el, "y") || (isAnimating ? 0 : s.ty);
                    return { x: cx + currX, y: cy + currY };
                });

                dynamicPositions.forEach((pos, i) => {
                    ctxConstel.moveTo(cx, cy); 
                    ctxConstel.lineTo(pos.x, pos.y);
                    if(i > 0) {
                        const prev = dynamicPositions[i-1];
                        ctxConstel.moveTo(prev.x, prev.y); 
                        ctxConstel.lineTo(pos.x, pos.y);
                    }
                });
                
                if(dynamicPositions.length > 1) {
                    const first = dynamicPositions[0]; 
                    const last = dynamicPositions[dynamicPositions.length-1];
                    ctxConstel.moveTo(last.x, last.y); 
                    ctxConstel.lineTo(first.x, first.y);
                }
                ctxConstel.stroke();
                ctxConstel.shadowBlur = 0; // Reset
            }
        };

        const getLayoutConfig = () => {
            const screenWidth = window.innerWidth;
            if (screenWidth <= 768) {
                return {
                    scale: 0.18, 
                    coords: [
                        { tx: 0, ty: -170 }, { tx: -70, ty: -115 }, { tx: 70, ty: -115 }, { tx: -85, ty: -55 }, { tx: 85, ty: -55 }, { tx: -95, ty: 0 },  { tx: 95, ty: 0 }, { tx: -85, ty: 55 },    { tx: 85, ty: 55 }, { tx: -70, ty: 115 },   { tx: 70, ty: 115 }, { tx: -35, ty: 170 },  { tx: 35, ty: 170 } 
                    ]
                };
            } else {
                return {
                    scale: 0.3, 
                    coords: [
                        { tx: 0, ty: 0 }, { tx: -280, ty: -160 }, { tx: 0, ty: -180 }, { tx: 280, ty: -160 }, { tx: -350, ty: 0 }, { tx: 350, ty: 0 }, { tx: -280, ty: 160 },  { tx: 0, ty: 180 },  { tx: 280, ty: 160 }, { tx: -140, ty: -90 },  { tx: 140, ty: -90 }, { tx: -140, ty: 90 },   { tx: 140, ty: 90 }
                    ]
                };
            }
        };

        let layout = getLayoutConfig();

        const assetsData = {
            full: { folder: 'logo_utuh', static: '/images/logo-utuh.png', images: [] },
            shards: [
                { id: 1, folder: 'shard_1', static: '/images/globe.png', title: 'Wawasan Global', desc: 'Pandangan luas menembus batas cakrawala.' },
                { id: 2, folder: 'shard_2', static: '/images/teks-gabungan.png', title: 'Identitas Angkatan', desc: 'Nama yang terukir abadi.' },
                { id: 3, folder: 'shard_3', static: '/images/cincin-emas.png', title: 'Lingkar Persaudaraan', desc: 'Ikatan tak terputus.' },
                { id: 4, folder: 'shard_4', static: '/images/pita-putih.png', title: 'Simpul Kesucian', desc: 'Niat tulus dan ikhlas.' },
                { id: 5, folder: 'shard_5', static: '/images/perisai-bendera.png', title: 'Jiwa Nasionalis', desc: 'Cinta tanah air.' },
                { id: 6, folder: 'shard_6', static: '/images/tanduk-perak.png', title: 'Pertahanan Baja', desc: 'Keberanian melindungi nilai.' },
                { id: 7, folder: 'shard_7', static: '/images/segi-delapan-perak.png', title: 'Bingkai Perak', desc: 'Ketahanan menghadapi zaman.' },
                { id: 8, folder: 'shard_8', static: '/images/bingkai-kristal-biru.png', title: 'Aura Samudra', desc: 'Ketenangan batin.' },
                { id: 9, folder: 'shard_9', static: '/images/ornamen-bawah-emas.png', title: 'Akar Kestabilan', desc: 'Kekayaan moral.' },
                { id: 10, folder: 'shard_10', static: '/images/segi-delapan-gelap.png', title: 'Fondasi Utama', desc: 'Batu pijakan yang kokoh.' },
                { id: 11, folder: 'shard_11', static: '/images/mahkota-emas.png', title: 'Mahkota Kepemimpinan', desc: 'Amanah besar.' },
                { id: 12, folder: 'shard_12', static: '/images/kristal-puncak.png', title: 'Puncak Visi', desc: 'Cita-cita tertinggi.' },
                { id: 13, folder: 'shard_13', static: '/images/zamrud-hijau.png', title: 'Titik Harapan', desc: 'Cahaya bimbingan.' }
            ]
        };

        assetsData.shards.forEach((shard, i) => { 
            shard.tx = layout.coords[i].tx; shard.ty = layout.coords[i].ty; shard.scale = layout.scale; 
        });

        window.addEventListener('resize', () => {
            layout = getLayoutConfig();
            assetsData.shards.forEach((shard, i) => { shard.tx = layout.coords[i].tx; shard.ty = layout.coords[i].ty; shard.scale = layout.scale; });
            if (isScattered && !isAnimating) {
                assetsData.shards.forEach((shard) => { gsap.to(`#shardWrap_${shard.id}`, { x: shard.tx, y: shard.ty, scale: shard.scale, duration: 0.4, ease: "power2.out" }); });
            } else if (!isScattered && !isAnimating) {
                gsap.to('#fullLogoBox', { scale: layout.scale * 1.5, duration: 0.4, ease: "power2.out" });
            }
        });

        const pad = (num) => num.toString().padStart(3, '0');
        const canvasFull = document.getElementById('fullLogoCanvas');
        const ctxFull = canvasFull.getContext('2d'); 
        const shardCanvases = []; 

        const totalImagesToLoad = TOTAL_FRAMES + (assetsData.shards.length * TOTAL_FRAMES);
        let loadedImages = 0;

        const updateProgress = () => {
            loadedImages++;
            const pct = Math.floor((loadedImages / totalImagesToLoad) * 100);
            document.getElementById('loadPercent').innerText = pct + '%'; 
            document.getElementById('loadBar').style.width = pct + '%';

            if(loadedImages === totalImagesToLoad) {
                setTimeout(() => {
                    gsap.to('#loader', { duration: 0.5, opacity: 0, onComplete: () => {
                        document.getElementById('loader').style.display = 'none';
                        gsap.set('#fullLogoBox', { scale: layout.scale * 1.5 }); isPlaying = true; 
                    }});
                }, 500);
            }
        };

        const preloadImages = () => {
            for(let i=1; i<=TOTAL_FRAMES; i++) {
                const img = new Image(); img.src = `${basePath}${assetsData.full.folder}/frame_${pad(i)}.webp`;
                img.onload = updateProgress; img.onerror = () => { updateProgress(); }; assetsData.full.images.push(img);
            }

            const container = document.getElementById('shardsContainer');
            assetsData.shards.forEach((shard) => {
                shard.images = []; frameData.shards[shard.id] = 0; 
                const wrap = document.createElement('div'); wrap.className = 'shard-wrapper hover-trigger cursor-bind'; wrap.id = `shardWrap_${shard.id}`;
                const c = document.createElement('canvas'); c.width = 800; c.height = 450; c.className = 'shard-canvas'; c.id = `shardCanvas_${shard.id}`;
                
                const ctx = c.getContext('2d');
                shardCanvases.push({ ctx: ctx, images: shard.images, id: shard.id });
                const statImg = document.createElement('img'); statImg.src = shard.static; statImg.className = 'shard-static'; statImg.id = `shardStatic_${shard.id}`;
                wrap.appendChild(c); wrap.appendChild(statImg); container.appendChild(wrap);

                wrap.addEventListener('mousedown', (e) => handleDragStart(e, shard.id));
                wrap.addEventListener('touchstart', (e) => handleDragStart(e, shard.id), {passive: false});
                wrap.addEventListener('click', (e) => {
                    if(!isScattered || isAnimating || hasDragged) return; 
                    document.getElementById('modalTitle').innerText = shard.title; document.getElementById('modalDesc').innerText = shard.desc;
                    document.getElementById('philModal').classList.add('active');
                });

                for(let i=1; i<=TOTAL_FRAMES; i++) {
                    const img = new Image(); img.src = `${basePath}${shard.folder}/frame_${pad(i)}.webp`;
                    img.onload = updateProgress; img.onerror = () => { updateProgress(); }; shard.images.push(img);
                }
            });
            document.getElementById('fullLogoBox').addEventListener('mousedown', (e) => handleDragStart(e, 'full'));
            document.getElementById('fullLogoBox').addEventListener('touchstart', (e) => handleDragStart(e, 'full'), {passive: false});
        };

        preloadImages();

        const monuText = document.getElementById('monumentalText');
        const godRays = document.getElementById('godRays');

        const applyParallax = (xNorm, yNorm) => {
            if(isAnimating) return; 
            gsap.to(monuText, { xPercent: -50 + (xNorm * -3), yPercent: -50 + (yNorm * -3), duration: 1, ease: "power2.out", overwrite: "auto" });
            gsap.to(godRays, { rotation: xNorm * 10, duration: 1, ease: "power2.out", overwrite: "auto" });
        };

        window.addEventListener('mousemove', (e) => { if(window.innerWidth > 768) { applyParallax((e.clientX / window.innerWidth - 0.5) * 2, (e.clientY / window.innerHeight - 0.5) * 2); } });
        window.addEventListener('deviceorientation', (e) => { if(window.innerWidth <= 768 && e.gamma !== null && e.beta !== null) { applyParallax(Math.max(-1, Math.min(1, e.gamma / 45)), Math.max(-1, Math.min(1, (e.beta - 45) / 45))); } });

        // =========================================================
        // SAFE RENDER ENGINE (PERBAIKAN CANVAS CRASH DOMException)
        // =========================================================
        const renderCurrentFrame = () => {
            if (!isScattered) {
                const fIdx = Math.floor(frameData.full);
                const img = assetsData.full.images[fIdx];
                // Wajib cek naturalWidth untuk menghindari crash dari frame yg gagal dimuat (broken image)
                if(img && img.complete && img.naturalWidth !== 0) { 
                    ctxFull.clearRect(0, 0, canvasFull.width, canvasFull.height);
                    ctxFull.drawImage(img, 0, 0, canvasFull.width, canvasFull.height); 
                }
            } else {
                shardCanvases.forEach(shardObj => {
                    const fIdx = Math.floor(frameData.shards[shardObj.id]);
                    const img = shardObj.images[fIdx];
                    if(img && img.complete && img.naturalWidth !== 0) { 
                        shardObj.ctx.clearRect(0, 0, 800, 450);
                        shardObj.ctx.drawImage(img, 0, 0, 800, 450); 
                    }
                });
            }
        };

        const autoPlayEngine = () => {
            if (!isPlaying) return; let needsRender = false;
            if (!isScattered) {
                if (draggedItem !== 'full') { frameData.full = (frameData.full + 0.6) % TOTAL_FRAMES; needsRender = true; }
            } else {
                assetsData.shards.forEach(s => { if (draggedItem !== s.id) { frameData.shards[s.id] = (frameData.shards[s.id] + 0.6) % TOTAL_FRAMES; needsRender = true; } });
            }
            if (needsRender || draggedItem !== null) { renderCurrentFrame(); }
        };
        gsap.ticker.add(() => { autoPlayEngine(); drawConstellation(); });

        let draggedItem = null; let hasDragged = false; let startX = 0; let lastX = 0; let frameAtDragStart = 0; let lastTickTime = 0; 

        const handleDragStart = (e, id) => {
            if(isAnimating) return; e.stopPropagation(); initAudio(); 
            draggedItem = id; hasDragged = false; startX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX; lastX = startX;
            frameAtDragStart = (id === 'full') ? frameData.full : frameData.shards[id];
            document.getElementById('hudHint').innerText = "MEMUTAR HOLOGRAM..."; document.getElementById('hudHint').style.color = "#d4af37";
            if (document.body.classList.contains('cursor-hovering')) { document.body.classList.remove('cursor-hovering'); }
        };

        const handleDragMove = (e) => {
            if(!draggedItem) return;
            const x = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX; const deltaX = x - startX; const velocity = Math.abs(x - lastX);
            if(Math.abs(deltaX) > 5) hasDragged = true; 
            
            const now = Date.now();
            if(velocity > 2 && (now - lastTickTime) > (100 - Math.min(velocity*2, 80))) { playTick(velocity); lastTickTime = now; }
            
            const ghostTarget = draggedItem === 'full' ? document.getElementById('fullLogoBox') : document.getElementById(`shardWrap_${draggedItem}`);
            ghostTarget.style.filter = `none`;

            let frameShift = deltaX / 3; let newFrame = frameAtDragStart + frameShift;
            while(newFrame >= TOTAL_FRAMES) newFrame -= TOTAL_FRAMES; while(newFrame < 0) newFrame += TOTAL_FRAMES;

            if (draggedItem === 'full') { frameData.full = newFrame; } else { frameData.shards[draggedItem] = newFrame; }
            lastX = x; 
        };

        const handleDragEnd = () => {
            if(!draggedItem) return;
            const ghostTarget = draggedItem === 'full' ? document.getElementById('fullLogoBox') : document.getElementById(`shardWrap_${draggedItem}`);
            if (ghostTarget) { ghostTarget.style.filter = `none`; }
            draggedItem = null; 
            if (!isScattered) { document.getElementById('hudHint').innerHTML = "<i class='fa-solid fa-arrows-left-right'></i> Tahan & Geser Untuk Memutar"; } else { document.getElementById('hudHint').innerHTML = "<i class='fa-solid fa-hand-pointer'></i> Geser Untuk Putar / Klik Untuk Data"; }
            document.getElementById('hudHint').style.color = "var(--text-secondary)"; setTimeout(() => hasDragged = false, 100); 
        };

        window.addEventListener('mousemove', handleDragMove); window.addEventListener('mouseup', handleDragEnd);
        window.addEventListener('touchmove', handleDragMove, {passive: false}); window.addEventListener('touchend', handleDragEnd);

        const btnAction = document.getElementById('btnAction');
        const swapToStatic = () => { gsap.set('.shard-canvas, #fullLogoCanvas', { display: 'none' }); gsap.set('.shard-static, #fullLogoStatic', { display: 'block', opacity: 1 }); };
        const swapToSequence = () => { gsap.set('.shard-static, #fullLogoStatic', { display: 'none' }); gsap.set('.shard-canvas, #fullLogoCanvas', { display: 'block', opacity: 1 }); };

        btnAction.addEventListener('click', () => {
            if(isAnimating) return; isAnimating = true; initAudio();

            if (!isScattered) {
                isScattered = true; document.getElementById('stage').classList.add('is-scattered'); playSwoosh(); 
                
                // --- EFEK SUPERNOVA: Kristal meluas jadi Cosmic Dust Field ---
                const isLightMode = document.documentElement.getAttribute('data-theme') === 'light';
                coreParticles.forEach(p => {
                    gsap.to(p, { speed: p.baseSpeed * 3, duration: 0.5, yoyo: true, repeat: 1, ease: "power2.out" });
                    gsap.to(p, { radius: p.baseRadius + 500 + (Math.random() * 1000), glow: isLightMode ? 0.2 : 0.1, duration: 2.5, ease: "expo.out" });
                });

                gsap.to('#fullLogoBox', { duration: 0.2, scale: 0, opacity: 0 });
                gsap.set('.shard-wrapper', { opacity: 1, x: 0, y: 0, scale: layout.scale * 1.5, rotationY: 0 }); 
                assetsData.shards.forEach(s => frameData.shards[s.id] = frameData.full); renderCurrentFrame(); swapToStatic(); 

                btnAction.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';
                if (navigator.vibrate) navigator.vibrate(50);

                const tl = gsap.timeline({ onComplete: () => { swapToSequence(); document.getElementById('hudHint').innerHTML = "<i class='fa-solid fa-hand-pointer'></i> Geser Untuk Putar / Klik Untuk Data"; btnAction.innerHTML = '<i class="fa-solid fa-compress"></i> Satukan Identitas'; isAnimating = false; }});
                tl.to('.shard-wrapper', { duration: 0.5, rotationY: -180, ease: "power2.in" });
                assetsData.shards.forEach((shard, index) => { tl.to(`#shardWrap_${shard.id}`, { duration: 2.5, x: shard.tx, y: shard.ty, scale: shard.scale, rotationY: -360, ease: "expo.out" }, 0.4 + (index * 0.02)); });
                gsap.to(godRays, { opacity: 0.2, duration: 2, ease: "expo.out" });

            } else {
                document.getElementById('stage').classList.remove('is-scattered'); document.getElementById('hudHint').innerHTML = "MENGUNCI FORMASI...";
                swapToStatic(); btnAction.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Merakit...';
                const isLight = document.documentElement.getAttribute('data-theme') === 'light'; gsap.to(godRays, { opacity: isLight ? 0.6 : 1, duration: 1.5 });

                // --- EFEK GRAVITY IMPLOSION: Kristal tersedot ke Black Hole ---
                coreParticles.forEach(p => {
                    gsap.to(p, { speed: p.baseSpeed * 4, duration: 1.5, ease: "expo.in" }); 
                    gsap.to(p, { radius: p.baseRadius, glow: p.baseGlow, duration: 1.5, ease: "expo.inOut", onComplete: () => p.speed = p.baseSpeed });
                });

                const wrapTargets = assetsData.shards.map(s => `#shardWrap_${s.id}`);
                const tl = gsap.timeline({ onComplete: () => { 
                    gsap.to('#flashEffect', { duration: 0.15, opacity: 1, yoyo: true, repeat: 1 }); playBassDrop(); if (navigator.vibrate) navigator.vibrate([100, 50, 150]); 
                    gsap.set('.shard-wrapper', { opacity: 0 }); 
                    gsap.to('#fullLogoBox', { duration: 0.1, scale: layout.scale * 1.5, opacity: 1 }); 
                    isScattered = false; 
                    swapToSequence(); 
                    document.getElementById('hudHint').innerHTML = "<i class='fa-solid fa-arrows-left-right'></i> Tahan & Geser Untuk Memutar"; 
                    btnAction.innerHTML = '<i class="fa-solid fa-expand"></i> Pencar Formasi'; 
                    isAnimating = false; 
                }});
                
                tl.to(wrapTargets, { duration: 1.5, x: 0, y: 0, scale: layout.scale * 1.5, rotationY: 0, ease: "expo.inOut", stagger: { each: 0.03, from: "edges" } });
                tl.to(wrapTargets, { duration: 0.8, rotationY: 360, scale: layout.scale * 1.6, ease: "power3.inOut" }, "-=0.5");
                tl.to(wrapTargets, { duration: 0.2, scale: layout.scale * 1.5, rotationY: 720, ease: "expo.out" });
            }
        });

        // =========================================================
        // PENGATURAN GSAP SCROLLTRIGGER (AUTO-ALPHA)
        // =========================================================
        const scrollContainer = ".main-wrapper";

        gsap.to("#fullLogoBox, .shard-wrapper, canvas", { yPercent: 30, ease: "none", scrollTrigger: { trigger: "#stage", scroller: scrollContainer, start: "top top", end: "bottom top", scrub: true } });
        gsap.to(".monumental-text", { yPercent: -50, ease: "none", scrollTrigger: { trigger: "#stage", scroller: scrollContainer, start: "top top", end: "bottom top", scrub: true } });

        document.querySelector('.main-wrapper').addEventListener('scroll', function() {
            const indicator = document.querySelector('.scroll-indicator');
            if(this.scrollTop > 100 && indicator) { indicator.style.opacity = '0'; }
        }, {passive: true});

        gsap.utils.toArray(".reveal-up").forEach((el) => {
            gsap.fromTo(el, 
                { autoAlpha: 0, y: 50 }, 
                { autoAlpha: 1, y: 0, duration: 1, ease: "power3.out", scrollTrigger: { trigger: el, scroller: scrollContainer, start: "top 85%", toggleActions: "play none none reverse" } }
            );
        });

        gsap.utils.toArray(".gsap-counter").forEach(counter => {
            const target = +counter.getAttribute('data-target');
            if(!isNaN(target) && target > 0) {
                gsap.fromTo(counter, { innerHTML: 0 }, { innerHTML: target, duration: 2.5, ease: "power2.out", snap: { innerHTML: 1 }, scrollTrigger: { trigger: counter.closest(".stats-grid"), scroller: scrollContainer, start: "top 80%" } });
            }
        });

        gsap.utils.toArray(".panca-jiwa").forEach((jiwa) => {
            ScrollTrigger.create({ trigger: jiwa, scroller: scrollContainer, start: "top center", end: "bottom center", onEnter: () => jiwa.classList.add("is-active"), onLeaveBack: () => jiwa.classList.remove("is-active"), onEnterBack: () => jiwa.classList.add("is-active"), onLeave: () => jiwa.classList.remove("is-active") });
        });

        gsap.utils.toArray(".timeline-node").forEach((node) => {
            gsap.fromTo(node, { autoAlpha: 0, y: 50 }, { autoAlpha: 1, y: 0, duration: 0.8, ease: "back.out(1.7)", scrollTrigger: { trigger: node, scroller: scrollContainer, start: "top 85%" } });
        });
    });

    window.closeModal = function() { document.getElementById('philModal').classList.remove('active'); };

    // =========================================================
    // LOGIK PANCA JIWA - GOD TIER ANIMATIONS
    // =========================================================
    const jiwaData = {
        'keikhlasan': {
            title: '1. Keikhlasan',
            desc: '<p>Jiwa yang pertama adalah keikhlasan. Prinsip ini berarti <em>sepi ing pamrih</em>, yakni berbuat sesuatu bukan karena didorong oleh keinginan untuk mendapatkan keuntungan tertentu, melainkan hanya untuk Allah SWT semata. Segala perbuatan dilakukan dengan niat semata-mata untuk ibadah, Lillah. Kiai dan guru ikhlas dalam mendidik, para pembantu Kiai ikhlas dalam membantu menjalankan proses pendidikan, serta para santri yang ikhlas dididik.</p><p>Jiwa ini menciptakan suasana kehidupan pondok yang harmonis antara Kiai yang disegani dengan santri yang taat, cinta dan penuh hormat. Jiwa ini pula yang menjadikan para santri senantiasa siap berjuang di jalan Allah, di manapun dan kapanpun.</p>'
        },
        'kesederhanaan': {
            title: '2. Kesederhanaan',
            desc: '<p>Kehidupan yang sederhana tentu sangat erat kaitannya dengan pondok pesantren. Kehidupan santri yang tentram bersahaja tentu jauh dari kata berlebihan, mubazir dan lain sebagainya. Sederhana tidak berarti pasif atau menerima begitu saja, tidak juga berarti miskin dan melarat.</p><p>Justru dalam jiwa kesederhanan itu terdapat nilai-nilai kekuatan, kesanggupan, ketabahan dan penguasaan diri dalam menghadapi perjuangan hidup.</p>'
        },
        'kemandirian': {
            title: '3. Kemandirian',
            desc: '<p>Kemandirian atau sering disebut juga dengan Berdikari (Berdiri di atas kaki sendiri) adalah kesanggupan menolong diri sendiri. Jiwa tersebut merupakan senjata ampuh yang dibekalkan pesantren kepada para santrinya. Berdikari tidak saja berarti bahwa santri sanggup belajar dan berlatih mengurus segala kepentingannya sendiri, tetapi pondok pesantren itu sendiri sebagai lembaga pendidikan juga harus sanggup berdikari sehingga tidak pernah menyandarkan kehidupannya kepada bantuan atau belas kasihan pihak lain.</p><p>Gontor menerapkan <em>Zelp-Berdruiping Systeem</em> (sama-sama memberikan iuran dan sama-sama memakai). Semua pekerjaan yang ada di dalam pondok dikerjakan oleh Kiai, guru dan para santrinya sendiri.</p>'
        },
        'ukhuwah': {
            title: '4. Ukhuwwah Islamiyyah',
            desc: '<p>Kehidupan di pondok pesantren diliputi suasana persaudaraan yang akrab, sehingga segala suka dan duka dirasakan bersama dalam jalinan ukhuwwah Islamiyyah. Tidak ada dinding pemisah di antara mereka; apapun latarbelakang keluarga, suku, budaya, bahkan bangsa semua larut dalam jalinan ukhuwwah Islamiyyah.</p><p>Ukhuwah ini bukan saja selama mereka di Pondok, tetapi juga mempengaruhi ke arah persatuan umat dalam masyarakat setelah mereka terjun di masyarakat.</p>'
        },
        'kebebasan': {
            title: '5. Kebebasan',
            desc: '<p>Bebas dalam berpikir dan berbuat, bebas dalam menentukan masa depan, bebas dalam memilih jalan hidup, dan bahkan bebas dari berbagai pengaruh negatif dari luar dirinya. Jiwa bebas ini akan menjadikan santri berjiwa besar dan optimis dalam menghadapi segala kesulitan.</p><p>Seringkali ditemukan unsur-unsur negatif dari kebebasan yang tak terkontrol, yaitu apabila kebebasan itu disalahgunakan, sehingga terlalu bebas (liberal) dan berakibat hilangnya arah tujuan dan prinsip. Ada pula yang terlalu bebas (untuk tidak mau dipengaruhi), berpegang teguh kepada tradisi yang dianggapnya baik, sehingga tidak mau mengikuti perkembangan zaman.</p><p>Maka kebebasan ini harus dikembalikan ke aslinya, yaitu bebas di dalam garis-garis yang positif, dengan penuh tanggungjawab; baik di dalam kehidupan pondok pesantren itu sendiri, maupun dalam kehidupan masyarakat. Untuk bisa mendapatkan kebebasan, seorang santri haruslah memegang teguh 4 prinsip sebelumnya agar tidak terjerumus ke dalam kebebasan yang salah.</p>'
        }
    };

    let activeJiwaTl = null;

    window.openJiwa = function(id) {
        const modal = document.getElementById('jiwaModal');
        const animContainer = document.getElementById('jiwaAnimContainer');
        const contentBox = document.getElementById('jiwaContent');
        
        // Reset state
        if(activeJiwaTl) { activeJiwaTl.kill(); activeJiwaTl = null; }
        animContainer.innerHTML = '';
        contentBox.classList.remove('show');
        
        const data = jiwaData[id];
        if(!data) return;

        // Set Text
        document.getElementById('jiwaTitle').innerText = data.title;
        document.getElementById('jiwaDesc').innerHTML = data.desc;

        modal.classList.add('active');
        activeJiwaTl = gsap.timeline();

        const isLight = document.documentElement.getAttribute('data-theme') === 'light';
        const colorMain = isLight ? '#b8860b' : '#d4af37';
        const colorGlow = isLight ? 'rgba(184,134,11,0.5)' : 'rgba(212,175,55,0.8)';
        const colorWhite = isLight ? '#000' : '#fff';

        // --- GOD TIER ANIMATIONS ---

        if (id === 'keikhlasan') {
            // Ripple Drop (Water droplet turning into glowing infinity)
            const drop = document.createElement('div');
            drop.className = 'god-tier-element';
            drop.style.width = '4px'; drop.style.height = '4px';
            drop.style.background = '#fff'; drop.style.borderRadius = '50%';
            animContainer.appendChild(drop);

            activeJiwaTl.fromTo(drop, { y: -300, opacity: 0 }, { y: 0, opacity: 1, duration: 1, ease: "power2.in" });
            
            // Ripples
            for(let i=0; i<3; i++) {
                const ripple = document.createElement('div');
                ripple.className = 'god-tier-element';
                ripple.style.border = `2px solid ${colorMain}`;
                ripple.style.borderRadius = '50%';
                ripple.style.boxShadow = `0 0 20px ${colorGlow}, inset 0 0 10px ${colorGlow}`;
                animContainer.appendChild(ripple);
                activeJiwaTl.fromTo(ripple, 
                    { width: 0, height: 0, opacity: 1 }, 
                    { width: 800 + (i*200), height: 800 + (i*200), opacity: 0, duration: 4, ease: "power2.out", delay: i*0.5 }, "-=0.8"
                );
            }
            activeJiwaTl.to(drop, { scale: 50, background: 'radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(212,175,55,0) 70%)', duration: 2, ease: "expo.out" }, "-=3.5");

        } else if (id === 'kesederhanaan') {
            // Zen Enso Circle drawing itself perfectly
            const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.setAttribute("width", "600"); svg.setAttribute("height", "600");
            svg.setAttribute("viewBox", "0 0 600 600");
            svg.className = 'god-tier-svg';
            
            const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            circle.setAttribute("cx", "300"); circle.setAttribute("cy", "300");
            circle.setAttribute("r", "250");
            circle.setAttribute("fill", "none");
            circle.setAttribute("stroke", colorMain);
            circle.setAttribute("stroke-width", "2");
            circle.style.filter = `drop-shadow(0 0 10px ${colorGlow})`;
            
            svg.appendChild(circle);
            animContainer.appendChild(svg);

            const len = circle.getTotalLength();
            activeJiwaTl.fromTo(circle, 
                { strokeDasharray: len, strokeDashoffset: len },
                { strokeDashoffset: 0, duration: 3, ease: "power4.inOut" }
            );
            activeJiwaTl.to(circle, { strokeWidth: 1, scale: 0.9, opacity: 0.5, transformOrigin: 'center', duration: 2, ease: "power2.inOut" }, "-=1");

        } else if (id === 'kemandirian') {
            // Monolith constructing itself from particles
            const monolith = document.createElement('div');
            monolith.className = 'god-tier-element';
            monolith.style.width = '80px'; monolith.style.height = '400px';
            monolith.style.background = `linear-gradient(to top, transparent, ${colorMain})`;
            monolith.style.boxShadow = `0 0 50px ${colorGlow}`;
            monolith.style.clipPath = 'polygon(50% 0%, 100% 10%, 100% 100%, 0% 100%, 0% 10%)';
            animContainer.appendChild(monolith);

            activeJiwaTl.fromTo(monolith, 
                { scaleY: 0, transformOrigin: "bottom center", opacity: 0, y: 100 },
                { scaleY: 1, opacity: 1, y: 0, duration: 2.5, ease: "elastic.out(1, 0.5)" }
            );

            // Sparks flying up
            for(let i=0; i<20; i++) {
                const spark = document.createElement('div');
                spark.className = 'god-tier-element';
                spark.style.width = '3px'; spark.style.height = '15px';
                spark.style.background = '#fff';
                spark.style.boxShadow = `0 0 10px ${colorWhite}`;
                animContainer.appendChild(spark);
                
                const sx = (Math.random() - 0.5) * 100;
                activeJiwaTl.fromTo(spark,
                    { x: sx, y: 200, opacity: 1, scale: 0 },
                    { x: sx * 2, y: -300, opacity: 0, scale: Math.random()*2, duration: 1.5 + Math.random(), ease: "power2.out", delay: Math.random() * 0.5 },
                    0.5
                );
            }

        } else if (id === 'ukhuwah') {
            // Dynamic Constellation connecting
            const numNodes = 12;
            const nodes = [];
            const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.setAttribute("width", "800"); svg.setAttribute("height", "800");
            svg.className = 'god-tier-svg';
            animContainer.appendChild(svg);

            for(let i=0; i<numNodes; i++) {
                const angle = (i / numNodes) * Math.PI * 2;
                const r = 250;
                const nx = 400 + Math.cos(angle) * r;
                const ny = 400 + Math.sin(angle) * r;

                const node = document.createElement('div');
                node.className = 'god-tier-element';
                node.style.width = '12px'; node.style.height = '12px';
                node.style.background = colorMain;
                node.style.borderRadius = '50%';
                node.style.boxShadow = `0 0 20px ${colorGlow}`;
                animContainer.appendChild(node);
                
                nodes.push({el: node, x: nx, y: ny, ix: 400, iy: 400}); // initial x, y center
                activeJiwaTl.fromTo(node, 
                    { x: 400, y: 400, scale: 0 }, 
                    { x: nx, y: ny, scale: 1, duration: 2, ease: "expo.out" }, 
                    0
                );

                // Draw lines between neighbors
                if (i > 0) {
                    const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                    line.setAttribute("stroke", colorMain);
                    line.setAttribute("stroke-width", "2");
                    line.setAttribute("opacity", "0");
                    svg.appendChild(line);

                    const prev = nodes[i-1];
                    activeJiwaTl.to(line, {
                        attr: { x1: prev.x, y1: prev.y, x2: nx, y2: ny },
                        opacity: 0.6,
                        duration: 1.5,
                        ease: "power2.inOut"
                    }, 1);
                }
            }
            // Connect last to first
            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            line.setAttribute("stroke", colorMain); line.setAttribute("stroke-width", "2"); line.setAttribute("opacity", "0");
            svg.appendChild(line);
            activeJiwaTl.to(line, { attr: { x1: nodes[numNodes-1].x, y1: nodes[numNodes-1].y, x2: nodes[0].x, y2: nodes[0].y }, opacity: 0.6, duration: 1.5, ease: "power2.inOut" }, 1.5);
            
            // Rotate the whole system
            activeJiwaTl.to(animContainer, { rotation: 360, duration: 40, ease: "linear", repeat: -1 }, 0);

        } else if (id === 'kebebasan') {
            // Shattering shell and particles bursting outward in 3D
            const shell = document.createElement('div');
            shell.className = 'god-tier-element';
            shell.style.width = '150px'; shell.style.height = '150px';
            shell.style.border = `4px solid ${colorMain}`;
            shell.style.borderRadius = '50%';
            animContainer.appendChild(shell);

            activeJiwaTl.fromTo(shell, { scale: 0 }, { scale: 1, duration: 1, ease: "back.out(1.5)" });
            activeJiwaTl.to(shell, { scale: 1.2, opacity: 0, borderWidth: 0, duration: 0.5, ease: "power2.in" }, "+=0.5");

            for(let i=0; i<40; i++) {
                const bird = document.createElement('div');
                bird.className = 'god-tier-element';
                bird.style.width = '6px'; bird.style.height = '6px';
                bird.style.background = '#fff';
                bird.style.borderRadius = '50%';
                bird.style.boxShadow = `0 0 15px ${colorGlow}`;
                animContainer.appendChild(bird);

                const angle = Math.random() * Math.PI * 2;
                const distance = Math.random() * 500 + 200;
                const tx = Math.cos(angle) * distance;
                const ty = Math.sin(angle) * distance;
                const tz = (Math.random() - 0.5) * 500;

                activeJiwaTl.fromTo(bird,
                    { x: 0, y: 0, z: 0, scale: 0, opacity: 1 },
                    { x: tx, y: ty, z: tz, scale: Math.random() * 2 + 0.5, opacity: 0, duration: 3 + Math.random(), ease: "power3.out" },
                    1.5
                );
            }
        }

        // Delay showing text
        activeJiwaTl.add(() => {
            contentBox.classList.add('show');
        }, 2.5); 
    };

    window.closeJiwa = function() {
        const modal = document.getElementById('jiwaModal');
        const contentBox = document.getElementById('jiwaContent');
        contentBox.classList.remove('show');
        if(activeJiwaTl) {
            // Fade out the container before killing it
            gsap.to('#jiwaAnimContainer', { opacity: 0, duration: 0.5 });
        }
        setTimeout(() => {
            modal.classList.remove('active');
            document.getElementById('jiwaAnimContainer').innerHTML = '';
            document.getElementById('jiwaAnimContainer').style.opacity = 1;
            // reset rotation if it was changed
            gsap.set('#jiwaAnimContainer', { clearProps: "all" });
            if(activeJiwaTl) { activeJiwaTl.kill(); activeJiwaTl = null; }
        }, 500);
    };

    // =========================================================
    // LOGIK ARSIP DEKLASIFIKASI
    // =========================================================
    const archiveData = {
        'visi': {
            date: '30 MARET 2026', title: 'Deklarasi Visi Sovereign',
            content: `
                <p>Naskah ini mencatat sumpah agung angkatan Expedient mengenai visi dan arah tujuan masa depan.</p>
                <p>Kami berjanji untuk memelihara warisan <span class="redacted" onclick="revealRedacted(this)">KEISLAMAN</span> dan mengikat erat <span class="redacted" onclick="revealRedacted(this)">PERSAUDARAAN</span>.</p>
                <p>Nilai-nilai ini diukir bukan pada batu, melainkan pada karakter setiap individu.</p>
                <p><em>Selesai.</em></p>
            `
        },
        'simpul': {
            date: '15 FEBRUARI 2026', title: 'Simpul Kesucian: Menjaga Nilai Arrisalah',
            content: `
                <p>Manuskrip mengenai pemeliharaan nilai-nilai murni dalam harmoni pasca-kelulusan.</p>
                <p>Di balik kemewahan dunia, pondasi kita tetap bersandar pada <span class="redacted" onclick="revealRedacted(this)">KESEDERHANAAN</span> hati.</p>
                <p>Setiap duta angkatan diharapkan menjadi mercusuar teladan di manapun mereka memijakkan kaki.</p>
                <p><em>Tertanda, Dewan Kehormatan.</em></p>
            `
        }
    };

    window.openArchive = function(key) {
        const modal = document.getElementById('archiveModal');
        const data = archiveData[key];
        if(!data) return;

        document.getElementById('arcDate').innerText = data.date;
        document.getElementById('arcTitle').innerText = data.title;
        document.getElementById('arcBody').innerHTML = data.content;

        modal.classList.add('active');
        document.getElementById('archivePaper').scrollTop = 0;
    };

    window.closeArchive = function() {
        document.getElementById('archiveModal').classList.remove('active');
    };

    window.revealRedacted = function(el) {
        el.classList.add('revealed');
    };

    // =========================================================
    // VVIP MOBILE & SENSOR INTERACTION
    // =========================================================
    
    // 1. Swipe to Seal (Ledger)
    const knob = document.getElementById('swipeKnob');
    const fill = document.getElementById('swipeFill');
    const container = document.getElementById('swipeSealContainer');
    const form = document.getElementById('ledgerForm');
    
    if(knob && container) {
        let isDragging = false;
        let startX = 0;
        let maxDrag = container.offsetWidth - knob.offsetWidth - 10;
        
        window.addEventListener('resize', () => { maxDrag = container.offsetWidth - knob.offsetWidth - 10; });

        const onStart = (e) => {
            isDragging = true;
            startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
            knob.style.transition = 'none';
            fill.style.transition = 'none';
        };

        const onMove = (e) => {
            if(!isDragging) return;
            const currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
            let diff = currentX - startX;
            if(diff < 0) diff = 0;
            if(diff > maxDrag) diff = maxDrag;
            
            knob.style.transform = `translateX(${diff}px)`;
            fill.style.width = (diff + 25) + 'px';
        };

        const onEnd = () => {
            if(!isDragging) return;
            isDragging = false;
            
            const currentTransform = knob.style.transform;
            const diff = parseFloat(currentTransform.replace('translateX(','').replace('px)','')) || 0;
            
            knob.style.transition = '0.3s ease';
            fill.style.transition = '0.3s ease';
            
            if(diff >= maxDrag * 0.95) {
                // Success!
                knob.style.transform = `translateX(${maxDrag}px)`;
                fill.style.width = '100%';
                if (navigator.vibrate) navigator.vibrate([50, 100, 50]);
                document.getElementById('swipeText').innerHTML = "PESAN DISEGEL <i class='fa-solid fa-check'></i>";
                setTimeout(() => form.submit(), 800);
            } else {
                // Reset
                knob.style.transform = `translateX(0px)`;
                fill.style.width = '0px';
            }
        };

        knob.addEventListener('mousedown', onStart);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', onEnd);
        
        knob.addEventListener('touchstart', onStart, {passive: true});
        window.addEventListener('touchmove', onMove, {passive: true});
        window.addEventListener('touchend', onEnd);
    }

    // 2. Press and Hold to Reveal (Mobile)
    const holdTriggers = document.querySelectorAll('.press-hold-trigger');
    const isMobileDevice = window.matchMedia("(max-width: 768px)").matches;
    
    // Add rings to body
    const ring = document.createElement('div');
    ring.className = 'haptic-ring';
    document.body.appendChild(ring);

    holdTriggers.forEach(trigger => {
        if(isMobileDevice) {
            trigger.querySelector('.desktop-hint').style.display = 'none';
            trigger.querySelector('.mobile-hint').style.display = 'inline';
            
            let holdTimer;
            
            const startHold = (e) => {
                const touch = e.touches[0];
                ring.style.left = touch.clientX - 50 + 'px';
                ring.style.top = touch.clientY - 50 + 'px';
                ring.style.width = '100px';
                ring.style.height = '100px';
                
                gsap.set(ring, { opacity: 1, scale: 0.1 });
                gsap.to(ring, { scale: 1.5, opacity: 0, duration: 1.5, ease: "power2.out" });
                
                if (navigator.vibrate) navigator.vibrate(50);
                
                holdTimer = setTimeout(() => {
                    if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                    const key = trigger.getAttribute('data-archive');
                    openArchive(key);
                }, 1000);
            };
            
            const endHold = () => {
                clearTimeout(holdTimer);
                gsap.killTweensOf(ring);
                gsap.to(ring, { opacity: 0, duration: 0.2 });
            };
            
            trigger.addEventListener('touchstart', startHold, {passive: true});
            trigger.addEventListener('touchend', endHold);
            trigger.addEventListener('touchmove', endHold); // Cancel if swiped
        } else {
            // Click for desktop
            trigger.addEventListener('click', () => {
                const key = trigger.getAttribute('data-archive');
                openArchive(key);
            });
        }
    });

    // 3. Gyroscope Parallax for the Stage
    if(window.DeviceOrientationEvent && isMobileDevice) {
        window.addEventListener('deviceorientation', (e) => {
            const tiltX = Math.min(Math.max(e.gamma, -45), 45); // Left to right
            const tiltY = Math.min(Math.max(e.beta - 45, -45), 45); // Front to back
            
            const xNorm = tiltX / 45; // -1 to 1
            const yNorm = tiltY / 45; // -1 to 1
            
            if(typeof isAnimating !== 'undefined' && !isAnimating && typeof applyParallax === 'function') {
                applyParallax(xNorm, yNorm);
            }
        });
    }
</script>
<?= $this->endSection() ?>