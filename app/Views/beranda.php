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
        height: 100svh; /* Menggunakan svh untuk mobile agar tidak melompat */
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

    /* Mobile: Animasi rotasi halus untuk gambar statis serpihan */
    @media (max-width: 768px) {
        .mecha-stage.is-scattered .shard-wrapper .shard-static {
            animation: shardFloat 6s ease-in-out infinite alternate;
        }
        @keyframes shardFloat {
            0% { transform: scale(1) rotateY(0deg); }
            50% { transform: scale(1.05) rotateY(8deg); }
            100% { transform: scale(1) rotateY(-8deg); }
        }
    }

    .merge-flash { position: absolute; inset: 0; background: var(--text-primary); opacity: 0; z-index: 900; pointer-events: none; mix-blend-mode: overlay; }

    /* ================= 3. UI CONTROLS ================= */
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
        .mecha-stage { height: 100svh; }
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

    .horizontal-scroll-snap { display: flex; gap: 20px; overflow-x: auto; padding-bottom: 30px; scroll-snap-type: x mandatory; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; }
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
    
    /* VVIP MOBILE RESPONSIVE (CENTERED CARDS) */
    @media (max-width: 768px) {
        .archive-modal { align-items: center; }
        .archive-paper { width: 95%; border-radius: 20px; padding: 30px 20px; transform: translateY(50px) scale(0.95); max-height: 85vh; }
        .archive-title { font-size: 1.5rem; margin-bottom: 20px; }
        
        .jiwa-modal { align-items: center; justify-content: center; }
        .jiwa-content { width: 95%; max-height: 85vh; overflow-y: auto; border-radius: 20px; padding: 30px 20px; transform: translateY(50px); margin-bottom: 0; }
        .jiwa-content.show { transform: translateY(0); }
        .jiwa-anim-container { height: 40vh; top: 5%; }
        .jiwa-title { font-size: 2rem; margin-bottom: 15px; }
        .jiwa-desc { font-size: 0.95rem; text-align: left; }
    }

    /* SWIPE TO SEAL ELEMENT (MOBILE) */
    .swipe-seal-container { position: relative; width: 100%; height: 60px; background: rgba(212,175,55,0.05); border: 1px solid var(--glass-border); border-radius: 30px; overflow: hidden; margin-top: 30px; display: none; }
    @media (max-width: 768px) {
        .swipe-seal-container { display: block; }
        #desktopSubmitBtn { display: none; }
    }
    .swipe-text { position: absolute; inset: 0; display: flex; justify-content: center; align-items: center; color: var(--gold-premium); font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase; font-weight: 600; pointer-events: none; z-index: 1; transition: 0.3s; opacity: 0.7; }
    .swipe-knob { position: absolute; top: 5px; left: 5px; width: 50px; height: 50px; background: var(--gold-premium); border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #030504; font-size: 1.2rem; cursor: grab; z-index: 2; box-shadow: 0 0 15px rgba(212,175,55,0.4); transition: transform 0.1s; }
    .swipe-knob:active { cursor: grabbing; transform: scale(0.95); }
    .swipe-fill { position: absolute; top: 0; left: 0; height: 100%; width: 0; background: rgba(212,175,55,0.15); z-index: 0; }

    /* GANTI FONT SIGNATURE */
    .input-signature { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.5rem !important; }
    
    /* MOBILE TOUCH UX FIX */
    .redacted { background: #d4af37; color: #d4af37; display: inline-block; padding: 0 5px; cursor: pointer; transition: 0.3s; border-radius: 2px; }
    .redacted.revealed { background: transparent; color: var(--text-primary); font-weight: bold; border-bottom: 2px solid #d4af37; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>


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
            <article class="news-item reveal-up" onclick="openArchive('visi')" style="cursor:pointer;">
                <span class="news-date">30 MARET 2026</span>
                <h3 class="news-title">Penetapan Visi Angkatan</h3>
                <a href="javascript:void(0)" class="news-link">BACA DOKUMEN <i class="fa-solid fa-book-open"></i></a>
            </article>
            <article class="news-item reveal-up" onclick="openArchive('simpul')" style="cursor:pointer;">
                <span class="news-date">15 FEBRUARI 2026</span>
                <h3 class="news-title">Simpul Kesucian: Menjaga Nilai-Nilai Arrisalah</h3>
                <a href="javascript:void(0)" class="news-link">BACA DOKUMEN <i class="fa-solid fa-book-open"></i></a>
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
            <?php foreach ($kurator as $k): ?>
            <div class="curator-card glass-panel reveal-up">
                <div class="curator-img-wrap"><img src="<?= esc($k['foto']) ?>" alt="<?= esc($k['nama']) ?>"></div>
                <div class="curator-info">
                    <h4><?= esc($k['nama']) ?></h4>
                    <p><?= esc($k['jabatan']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SECTION GAMIFIKASI: LEADERBOARD PRESTISE -->
    <?php if (!empty($leaderboard)): ?>
    <section class="hall-section" style="padding-top: 0;">
        <h2 class="section-title reveal-up" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">Jajaran Kehormatan</h2>
        <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 15px;">
            <?php $rank = 1; foreach ($leaderboard as $l): ?>
            <div class="glass-panel reveal-up" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 30px; border-left: 4px solid <?= $rank == 1 ? '#FFD700' : ($rank == 2 ? '#E5E4E2' : ($rank == 3 ? '#cd7f32' : 'var(--glass-border)')) ?>; transition: 0.3s; cursor: default;" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #d4af37; font-weight: 900; width: 40px;">#<?= $rank ?></div>
                    <?php $foto_profil = $l['foto_profil'] ?? 'default.webp'; ?>
                    <img src="<?= $foto_profil !== 'default.webp' ? '/uploads/profiles/'.$foto_profil : 'https://ui-avatars.com/api/?name='.urlencode($l['nama_panggilan'] ?: $l['nama_lengkap']).'&background=d4af37&color=000' ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(212,175,55,0.4);" alt="Avatar">
                    <div>
                        <div style="font-size: 1.2rem; font-family: 'Playfair Display', serif; color: var(--text-primary);"><?= esc($l['nama_panggilan'] ?: $l['nama_lengkap']) ?></div>
                        <div style="font-size: 0.8rem; font-family: 'Courier New', monospace; color: var(--text-secondary); letter-spacing: 2px;">POIN TERAKUMULASI</div>
                    </div>
                </div>
                <div style="font-size: 1.8rem; color: #d4af37; font-weight: 700; font-family: 'Inter', sans-serif;">
                    <?= number_format($l['prestise_points']) ?>
                </div>
            </div>
            <?php $rank++; endforeach; ?>
        </div>
    </section>
    <?php endif; ?>


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
        
        <?php if (session()->get('logged_in')): ?>
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
        <?php else: ?>
        <div style="text-align:center; padding:40px 20px; background:var(--glass-bg); backdrop-filter:blur(20px); border:1px solid var(--glass-border); border-radius:16px;">
            <i class="fa-solid fa-lock" style="font-size:2rem; color:rgba(212,175,55,0.4); margin-bottom:15px;"></i>
            <p style="color:var(--text-secondary); margin-bottom:20px;">Masuk ke portal untuk menandatangani buku tamu.</p>
            <a href="/login" style="color:#d4af37; text-decoration:none; font-weight:700; letter-spacing:2px; text-transform:uppercase; font-size:0.85rem;"><i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang</a>
        </div>
        <?php endif; ?>
    </section>


    <!-- ================= BIRTHDAY TOAST NOTIFICATION (HANYA MEMBER) ================= -->
    <?php if(session()->get('logged_in') && !empty($birthday_users)): ?>
    <div id="bdayToast" style="position:fixed; bottom:40px; left:50%; transform:translateX(-50%) translateY(150px); width:90%; max-width:400px; background:var(--glass-bg); backdrop-filter:blur(30px); border:1px solid rgba(212,175,55,0.4); border-radius:16px; padding:20px; box-shadow:0 20px 50px rgba(0,0,0,0.5); z-index:99999; display:flex; align-items:center; gap:15px; opacity:0; transition:0.8s cubic-bezier(0.16,1,0.3,1);">
        <div style="width:50px; height:50px; background:rgba(212,175,55,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#d4af37; font-size:1.5rem; flex-shrink:0;">
            <i class="fa-solid fa-cake-candles"></i>
        </div>
        <div style="flex:1;">
            <div style="font-family:'Playfair Display',serif; color:#d4af37; font-weight:700; font-size:1.1rem; margin-bottom:5px;">Notifikasi Ulang Tahun</div>
            <div style="color:var(--text-primary); font-size:0.85rem; line-height:1.4;">
                Hari ini adalah ulang tahun <strong><?= esc($birthday_users[0]['nama_panggilan'] ?: $birthday_users[0]['nama_lengkap']) ?></strong><?= count($birthday_users) > 1 ? ' dan ' . (count($birthday_users)-1) . ' entitas lainnya' : '' ?>. <br>
                <a href="/birthday" style="color:#d4af37; text-decoration:none; font-weight:bold; margin-top:5px; display:inline-block;">Kirim Ucapan <i class="fa-solid fa-arrow-right-long" style="margin-left:5px;"></i></a>
            </div>
        </div>
        <button onclick="closeBdayToast()" style="background:transparent; border:none; color:var(--text-secondary); cursor:pointer; padding:5px;"><i class="fa-solid fa-times"></i></button>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                const toast = document.getElementById('bdayToast');
                if(toast) {
                    toast.style.transform = 'translateX(-50%) translateY(0)';
                    toast.style.opacity = '1';
                }
            }, 3000);
        });
        function closeBdayToast() {
            const toast = document.getElementById('bdayToast');
            toast.style.transform = 'translateX(-50%) translateY(150px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.style.display = 'none', 800);
        }
    </script>
    <?php endif; ?>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script src="/assets/js/beranda.js" defer></script>
<?= $this->endSection() ?>