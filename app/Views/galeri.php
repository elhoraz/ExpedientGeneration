<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Arsip Visual 5D | The Syndicate Yearbook
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Impor Font Tegak Bersambung Super Elegan untuk Signature -->
<link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">

<style>
    /* ================= 1. THE STAGE (AWWARDS LEVEL) ================= */
    .gallery-stage {
        position: relative; width: 100%; height: 100%; overflow: hidden;
        background: transparent; display: flex; justify-content: center; align-items: center;
        perspective: 3500px; perspective-origin: 50% 50%; user-select: none;
        transition: perspective-origin 0.1s ease-out; touch-action: manipulation; 
    }
    #dustCanvas { position: absolute; inset: 0; z-index: 2; pointer-events: none; }
    .ambient-light {
        position: absolute; inset: 0; pointer-events: none; z-index: 3; transition: background 1.5s ease;
        background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(212,175,55,0.15) 0%, transparent 60%);
    }
    .gallery-stage.dimensi-putri .ambient-light { background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(224, 191, 184, 0.2) 0%, transparent 60%); }
    .gallery-stage.epilogue-mode .ambient-light { background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(255, 180, 50, 0.3) 0%, transparent 80%); }

    /* MUSEUM IDLE MODE (SCREENSAVER) */
    .gallery-stage.idle-mode .gallery-hud,
    .gallery-stage.idle-mode .dimension-shift-btn,
    .gallery-stage.idle-mode .ethereal-text,
    .gallery-stage.idle-mode .whisper-btn {
        opacity: 0 !important; pointer-events: none; transition: opacity 3s ease;
    }
    .gallery-stage.idle-mode .ambient-light {
        background: radial-gradient(circle at 50% 50%, rgba(212,175,55,0.05) 0%, transparent 40%) !important; transition: background 4s ease;
    }

    /* ETHEREAL TYPOGRAPHY */
    .ethereal-text {
        position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) translateZ(-400px);
        font-family: 'Playfair Display', serif; font-size: clamp(4rem, 10vw, 12rem); color: rgba(212, 175, 55, 0.1);
        text-transform: uppercase; letter-spacing: 30px; pointer-events: auto; cursor: pointer; white-space: nowrap; z-index: 1; filter: blur(4px); transition: all 2s ease;
    }
    .ethereal-text:hover { filter: blur(2px); color: rgba(212, 175, 55, 0.2); }
    .gallery-stage.dimensi-putri .ethereal-text { color: rgba(224, 191, 184, 0.1); }
    .gallery-stage.epilogue-mode .ethereal-text { color: rgba(255, 200, 50, 0.4); filter: blur(1px) drop-shadow(0 0 20px rgba(255,180,50,0.5)); letter-spacing: 40px; transform: translate(-50%, -50%) translateZ(-200px) scale(1.05); }

    /* CORE & BUKU 3D REALISTIS */
    .dimension-core { position: relative; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; transform-style: preserve-3d; transition: transform 1.5s cubic-bezier(0.25, 1, 0.5, 1); z-index: 10; }
    .book-scene { position: absolute; width: min(90vw, calc(75vh * 3)); aspect-ratio: 3 / 1; transform-style: preserve-3d; transition: transform 1s cubic-bezier(0.645, 0.045, 0.355, 1); }
    
    /* BAYANGAN BUKU DI ATAS MEJA (REALISTIC DROP SHADOW) */
    .book-scene::before {
        content: ''; position: absolute; inset: -5% -10% -20% -10%;
        background: radial-gradient(ellipse at center, rgba(0,0,0,0.8) 0%, transparent 60%);
        transform: translateZ(-50px); pointer-events: none; z-index: -1;
    }

    #bookPutra { transform: translateZ(100px) rotateX(10deg); }
    #bookPutri { transform: rotateY(180deg) translateZ(100px) rotateX(-10deg); pointer-events: none; }

    /* LEMBARAN KERTAS */
    .sheet { position: absolute; right: 0; width: 50%; height: 100%; transform-style: preserve-3d; transform-origin: left center; cursor: grab; }
    .sheet:active { cursor: grabbing; }
    .book-scene.index-mode .sheet { box-shadow: 0 10px 30px rgba(0,0,0,0.8); cursor: pointer; }
    
    /* ========================================================
       SISTEM KETEBALAN BUKU (3D SPINE & PAPER EDGES)
       Setiap halaman menyumbang 2.5px ketebalan.
       ======================================================== */
    /* Sisi Kanan (Tumpukan Kertas Warna Emas) */
    .sheet::before {
        content: ''; position: absolute; right: 0; top: 1%; bottom: 1%; width: 2.5px;
        background: linear-gradient(to bottom, #b49129, #f3e5ab, #b49129);
        transform-origin: right center; transform: rotateY(90deg); z-index: 10;
    }
    
    /* Sisi Kiri (Tulang Buku) */
    .sheet::after {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2.5px;
        background: #080808; border-left: 1px solid rgba(255,255,255,0.05);
        transform-origin: left center; transform: rotateY(-90deg); z-index: 10;
    }

    /* Sampul Depan & Belakang Dibuat Lebih Tebal */
    .sheet:first-child::before, .sheet:last-child::before { width: 4px; background: #030504; top: 0; bottom: 0; }
    .sheet:first-child::after, .sheet:last-child::after { width: 4px; background: #030504; }

    .face { position: absolute; inset: 0; backface-visibility: hidden; background-color: #030504; box-shadow: inset 0 0 10px rgba(0,0,0,0.5); border: 1px solid rgba(212, 175, 55, 0.2); }
    .face.front { transform: rotateY(0deg); border-radius: 2px 8px 8px 2px; }
    .face.back { transform: rotateY(180deg); border-radius: 8px 2px 2px 8px; }
    .face img { width: 100%; height: 100%; object-fit: cover; pointer-events: none; }
    
    /* Gradient Lekukan Tengah Buku (Deep Crease) */
    .face.front::after { content: ''; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.1) 6%, transparent 15%); }
    .face.back::after { content: ''; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(to left, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.1) 6%, transparent 15%); }

    /* HUD & NEW FEATURES */
    .dimension-shift-btn { position: absolute; top: 3vh; left: 50%; transform: translateX(-50%); background: transparent; border: 1px solid #d4af37; color: #d4af37; padding: 10px 30px; border-radius: 30px; font-family: 'Courier New', monospace; letter-spacing: 3px; font-weight: bold; cursor: pointer; z-index: 100; transition: 0.4s; box-shadow: 0 0 15px rgba(212,175,55,0.2); font-size: clamp(0.7rem, 1.5vw, 1rem); }
    .dimension-shift-btn:hover { background: #d4af37; color: #000; transform: translateX(-50%) scale(1.05); }
    .gallery-stage.dimensi-putri .dimension-shift-btn { border-color: #e0bfb8; color: #e0bfb8; box-shadow: 0 0 15px rgba(224, 191, 184, 0.2); }
    .gallery-stage.dimensi-putri .dimension-shift-btn:hover { background: #e0bfb8; color: #000; }
    .gallery-hud { position: absolute; bottom: 4vh; left: 50%; transform: translateX(-50%); display: flex; align-items: center; gap: clamp(8px, 1.5vw, 20px); z-index: 100; background: var(--glass-bg, rgba(0,0,0,0.5)); backdrop-filter: var(--glass-blur, blur(10px)); padding: 10px 25px; border-radius: 50px; border: 1px solid var(--glass-border, rgba(212,175,55,0.3)); }
    .gallery-stage.dimensi-putri .gallery-hud { border-color: rgba(224, 191, 184, 0.3); }
    .btn-nav, .btn-icon { background: transparent; border: none; color: #d4af37; cursor: pointer; transition: 0.3s; }
    .btn-nav { font-size: clamp(1.2rem, 2vw, 1.8rem); }
    .btn-icon { font-size: clamp(1rem, 1.5vw, 1.3rem); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .gallery-stage.dimensi-putri .btn-nav, .gallery-stage.dimensi-putri .btn-icon { color: #e0bfb8; }
    .btn-nav:hover, .btn-icon:hover { transform: scale(1.2) translateY(-2px); text-shadow: 0 0 15px rgba(212,175,55,0.8); }
    .btn-nav:disabled { opacity: 0.2; cursor: not-allowed; transform: none; text-shadow: none; }
    .btn-icon.is-playing { background: rgba(212,175,55,0.2); border: 1px solid #d4af37; box-shadow: 0 0 15px rgba(212,175,55,0.5); }
    .gallery-stage.dimensi-putri .btn-icon.is-playing { background: rgba(224, 191, 184, 0.2); border: 1px solid #e0bfb8; box-shadow: 0 0 15px rgba(224, 191, 184, 0.5); }
    .btn-icon.is-pinned { color: #fff; background: #d4af37; box-shadow: 0 0 20px #d4af37; }
    #btnGoToPin { color: #fff; background: rgba(212, 175, 55, 0.5); border: 1px solid #d4af37; box-shadow: 0 0 20px rgba(212,175,55,0.8); }
    .page-indicator { color: var(--text-secondary, #ccc); font-family: 'Courier New', monospace; font-size: clamp(0.7rem, 1vw, 0.9rem); letter-spacing: 2px; font-weight: bold; min-width: 120px; text-align: center; transition: 0.3s; }

    /* WHISPER BUTTON */
    .whisper-btn { position: absolute; top: 40%; right: 8%; width: 60px; height: 60px; border-radius: 50%; background: rgba(212,175,55,0.1); border: 1px solid #d4af37; color: #d4af37; font-size: 1.8rem; cursor: pointer; box-shadow: 0 0 20px rgba(212,175,55,0.3); z-index: 90; display: none; opacity: 0; transition: all 0.5s ease; }
    .whisper-btn.is-visible { display: flex; align-items: center; justify-content: center; opacity: 1; animation: pulseWhisper 2s infinite; }
    .whisper-btn:hover { background: #d4af37; color: #000; transform: scale(1.1); }
    .gallery-stage.dimensi-putri .whisper-btn { border-color: #e0bfb8; color: #e0bfb8; box-shadow: 0 0 20px rgba(224,191,184,0.3); }
    @keyframes pulseWhisper { 0% { box-shadow: 0 0 10px rgba(212,175,55,0.3); } 50% { box-shadow: 0 0 40px rgba(212,175,55,0.8); transform: scale(1.05); } 100% { box-shadow: 0 0 10px rgba(212,175,55,0.3); } }

    /* HOLOGRAPHIC LIGHTBOX & GOLDEN SIGNATURE "SUCCESSOR" */
    .lightbox-overlay { position: fixed; inset: 0; z-index: 999999; background: rgba(3, 5, 4, 0.4); backdrop-filter: blur(0px); display: flex; justify-content: center; align-items: center; opacity: 0; cursor: zoom-out; }
    .lightbox-img-wrapper { position: relative; }
    .lightbox-img { max-width: 90vw; max-height: 90vh; border-radius: 8px; border: 1px solid rgba(212, 175, 55, 0.5); box-shadow: 0 30px 60px rgba(0,0,0,0.8), 0 0 50px rgba(212,175,55,0.2); }
    
    .signature-overlay { position: absolute; bottom: 5%; right: 5%; width: clamp(150px, 25vw, 300px); pointer-events: none; opacity: 0; filter: drop-shadow(0 0 8px rgba(212,175,55,0.8)); }
    .signature-text { font-family: 'Alex Brush', cursive; font-size: 80px; fill: transparent; stroke: #d4af37; stroke-width: 1.5; stroke-dasharray: 800; stroke-dashoffset: 800; }
    @keyframes drawSignatureAnim { 0% { stroke-dashoffset: 800; fill: transparent; stroke: #d4af37; } 70% { stroke-dashoffset: 0; fill: transparent; stroke: #d4af37; } 100% { stroke-dashoffset: 0; fill: rgba(212,175,55,1); stroke: transparent; } }

    /* PORTRAIT LOCK */
    .portrait-lock { display: none; position: fixed; inset: 0; z-index: 99999; background: #030504; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; }
    .portrait-lock i { font-size: clamp(3rem, 10vw, 5rem); color: #d4af37; margin-bottom: 20px; animation: tiltPhone 2s infinite; }
    .portrait-lock h2 { color: #fff; font-family: 'Playfair Display', serif; font-size: clamp(1.2rem, 5vw, 2rem); margin-bottom: 10px; }
    .portrait-lock p { color: #ccc; font-size: clamp(0.8rem, 3vw, 1rem); line-height: 1.5; }
    @keyframes tiltPhone { 0%, 100% { transform: rotate(0deg); } 50% { transform: rotate(-90deg); color: #fff; } }
    @media (orientation: portrait) { .portrait-lock { display: flex !important; } .gallery-stage { display: none !important; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Audio File Lokal -->
<audio id="bgMusic" loop preload="auto">
    <source src="<?= base_url('assets/audio/memori.mp3') ?>" type="audio/mpeg">
</audio>

<!-- Audio Pesan Suara Khusus -->
<audio id="whisperAudio" preload="auto">
    <source src="<?= base_url('assets/audio/pesan_angkatan.mp3') ?>" type="audio/mpeg">
</audio>

<div class="portrait-lock">
    <i class="fa-solid fa-mobile-screen"></i>
    <h2>AKSES TERKUNCI</h2>
    <p>Ruang Kenangan membutuhkan mode Landscape.<br>Silakan putar perangkat Anda.</p>
</div>

<div class="gallery-stage" id="galleryStage">
    <div class="ethereal-text" id="etherealText" title="Double Click for Epilogue">THE SYNDICATE</div>
    <canvas id="dustCanvas"></canvas>
    <div class="ambient-light" id="ambientLight"></div>
    <button class="dimension-shift-btn hover-trigger" id="btnShift"><i class="fa-solid fa-rotate"></i> SHIFT TO OMEGA (PUTRI)</button>

    <div class="dimension-core" id="dimCore">
        <div class="book-scene" id="bookPutra">
            <?php $pathPutra = base_url('assets/foto_putra/'); $idxPutra = 0; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutra++ ?>"><div class="face front cover-material"><img src="<?= $pathPutra ?>Cover Depan.webp" loading="lazy"></div><div class="face back"><img src="<?= $pathPutra ?>Cover Dalem Depan.webp" loading="lazy"></div></div>
            <?php for($i = 1; $i <= 75; $i++): ?>
                <div class="sheet cursor-bind" data-sheet="<?= $idxPutra++ ?>"><div class="face front"><img src="<?= $pathPutra ?>Hal <?= ($i * 2) - 1 ?>.webp" loading="lazy"></div><div class="face back"><img src="<?= $pathPutra ?>Hal <?= ($i * 2) ?>.webp" loading="lazy"></div></div>
            <?php endfor; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutra++ ?>"><div class="face front"><img src="<?= $pathPutra ?>Cover Dalem Belakang.webp" loading="lazy"></div><div class="face back cover-material"><img src="<?= $pathPutra ?>Cover Belakang.webp" loading="lazy"></div></div>
        </div>

        <div class="book-scene" id="bookPutri">
            <?php $pathPutri = base_url('assets/foto_putri/'); $idxPutri = 0; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutri++ ?>"><div class="face front cover-material"><img src="<?= $pathPutri ?>Cover Depan.webp" loading="lazy"></div><div class="face back"><img src="<?= $pathPutri ?>Cover Dalem Depan.webp" loading="lazy"></div></div>
            <?php for($i = 1; $i <= 41; $i++): ?>
                <div class="sheet cursor-bind" data-sheet="<?= $idxPutri++ ?>"><div class="face front"><img src="<?= $pathPutri ?>Hal <?= ($i * 2) - 1 ?>.webp" loading="lazy"></div><div class="face back"><img src="<?= $pathPutri ?>Hal <?= ($i * 2) ?>.webp" loading="lazy"></div></div>
            <?php endfor; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutri++ ?>"><div class="face front"><img src="<?= $pathPutri ?>Cover Dalem Belakang.webp" loading="lazy"></div><div class="face back cover-material"><img src="<?= $pathPutri ?>Cover Belakang.webp" loading="lazy"></div></div>
        </div>
    </div>

    <!-- TOMBOL WHISPER -->
    <button class="whisper-btn hover-trigger" id="btnWhisper" title="Dengarkan Pesan Memori"><i class="fa-solid fa-microphone-lines"></i></button>

    <div class="gallery-hud">
        <button class="btn-icon hover-trigger" id="btnAudio" title="Nyalakan Musik Kenangan"><i class="fa-solid fa-music"></i></button>
        <button class="btn-icon hover-trigger" id="btnAutoPlay" title="Cinematic Auto-Play"><i class="fa-solid fa-play"></i></button>
        <button class="btn-icon hover-trigger" id="btnIndex" title="Constellation Grid"><i class="fa-solid fa-border-all"></i></button>
        
        <button class="btn-nav hover-trigger" id="btnPrev"><i class="fa-solid fa-arrow-left"></i></button>
        <div class="page-indicator" id="pageIndicator">COVER DEPAN</div>
        <button class="btn-nav hover-trigger" id="btnNext"><i class="fa-solid fa-arrow-right"></i></button>
        
        <button class="btn-icon hover-trigger" id="btnCloseBook" title="Tutup Buku"><i class="fa-solid fa-book"></i></button>
        <button class="btn-icon hover-trigger" id="btnPin" title="Simpan Halaman Ini"><i class="fa-regular fa-bookmark"></i></button>
        <button class="btn-icon hover-trigger" id="btnGoToPin" title="Teleportasi ke Memori" style="display: none;"><i class="fa-solid fa-map-location-dot"></i></button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.config({ force3D: true });

        // DEKLARASI GLOBAL VARIABLE
        const stage = document.getElementById('galleryStage');
        const dimCore = document.getElementById('dimCore');
        const indicator = document.getElementById('pageIndicator');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnAutoPlay = document.getElementById('btnAutoPlay');
        const btnWhisper = document.getElementById('btnWhisper');
        const whisperAudio = document.getElementById('whisperAudio');
        const bgMusic = document.getElementById('bgMusic');
        const btnAudio = document.getElementById('btnAudio');
        
        let activeDim = 'putra';
        let isShifting = false;
        let isMusicPlaying = false;
        let autoPlayTimer;
        let isAutoPlaying = false;

        const hapticThrill = () => { if (navigator.vibrate) navigator.vibrate(15); };
        const hapticBoom = () => { if (navigator.vibrate) navigator.vibrate([30, 50, 30]); };

        // =========================================================
        // AUDIO ENGINE
        // =========================================================
        let audioCtx;
        const initAudio = () => {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
        };

        const playPaperFlip = () => {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            const filter = audioCtx.createBiquadFilter();

            osc.type = 'triangle';
            osc.frequency.setValueAtTime(80, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(600, audioCtx.currentTime + 0.1);
            filter.type = 'bandpass'; filter.frequency.value = 1500;
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.08, audioCtx.currentTime + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);

            osc.connect(filter); filter.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.25);
            hapticThrill();
        };

        const playDimensionShift = () => {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(200, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(20, audioCtx.currentTime + 1.5);
            gain.gain.setValueAtTime(0.6, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1.5);
            
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 1.5);
            hapticBoom();
        };

        const stopAutoPlay = () => {
            if(!isAutoPlaying) return;
            isAutoPlaying = false;
            btnAutoPlay.classList.remove('is-playing');
            btnAutoPlay.innerHTML = '<i class="fa-solid fa-play"></i>';
            clearInterval(autoPlayTimer);
        };

        const updatePinUI = () => {
            const savedPin = JSON.parse(localStorage.getItem('expedient_pin'));
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(savedPin) {
                btnGoToPin.style.display = 'flex';
                if(savedPin.dim === activeDim && savedPin.page === activeEngine.currentSheet) {
                    btnPin.classList.add('is-pinned'); btnPin.innerHTML = '<i class="fa-solid fa-bookmark"></i>';
                } else {
                    btnPin.classList.remove('is-pinned'); btnPin.innerHTML = '<i class="fa-regular fa-bookmark"></i>';
                }
            } else {
                btnGoToPin.style.display = 'none'; btnPin.classList.remove('is-pinned'); btnPin.innerHTML = '<i class="fa-regular fa-bookmark"></i>';
            }
        };

        // =========================================================
        // IDLE MODE (MUSEUM SCREENSAVER)
        // =========================================================
        let idleTimer; let isIdle = false;
        const resetIdleTimer = () => {
            if (isIdle) {
                isIdle = false; stage.classList.remove('idle-mode');
                gsap.killTweensOf(dimCore, "rotationY");
                gsap.to(dimCore, { rotationY: (activeDim === 'putra' ? 0 : -180), duration: 1, ease: "power2.out" });
            }
            clearTimeout(idleTimer);
            idleTimer = setTimeout(() => {
                if (isShifting || bookPutra.isAnimating || bookPutri.isAnimating || bookPutra.isIndexMode || bookPutri.isIndexMode || isAutoPlaying) return;
                isIdle = true; stage.classList.add('idle-mode');
                gsap.to(dimCore, { rotationY: "+=360", duration: 60, ease: "none", repeat: -1 });
            }, 15000); 
        };
        ['mousemove', 'touchstart', 'keydown', 'click'].forEach(evt => window.addEventListener(evt, resetIdleTimer));
        resetIdleTimer();

        // =========================================================
        // DUST RENDERER
        // =========================================================
        const canvas = document.getElementById('dustCanvas'); const ctx = canvas.getContext('2d');
        let particles = []; let warpSpeed = false; let isEpilogueMode = false;
        const resizeCanvas = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
        window.addEventListener('resize', resizeCanvas); resizeCanvas();
        const particleCount = window.innerWidth <= 768 ? 20 : 50;
        for(let i=0; i<particleCount; i++) particles.push({ x: Math.random() * canvas.width, y: Math.random() * canvas.height, r: Math.random() * 2.5, vx: (Math.random() - 0.5) * 0.3, vy: (Math.random() - 0.5) * 0.3, alpha: Math.random() });
        const renderDust = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.x += warpSpeed ? p.vx * 30 : p.vx; p.y += warpSpeed ? p.vy * 30 : (isEpilogueMode ? p.vy * 0.5 + 0.2 : p.vy); 
                if(p.x < 0) p.x = canvas.width; if(p.x > canvas.width) p.x = 0; if(p.y < 0) p.y = canvas.height; if(p.y > canvas.height) p.y = 0;
                ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = isEpilogueMode ? `rgba(255, 215, 0, ${p.alpha})` : `rgba(212,175,55,${p.alpha})`; ctx.fill();
            }); requestAnimationFrame(renderDust);
        }; renderDust();

        // =========================================================
        // AUDIO LISTENERS
        // =========================================================
        btnAudio.addEventListener('click', () => {
            if(!isMusicPlaying) {
                bgMusic.volume = 0.5; let playPromise = bgMusic.play();
                if (playPromise !== undefined) playPromise.then(_ => { isMusicPlaying = true; btnAudio.classList.add('is-playing'); btnAudio.innerHTML = '<i class="fa-solid fa-volume-high"></i>'; }).catch(e => console.error(e));
            } else {
                bgMusic.pause(); isMusicPlaying = false; btnAudio.classList.remove('is-playing'); btnAudio.innerHTML = '<i class="fa-solid fa-music"></i>';
            }
        });

        btnWhisper.addEventListener('click', (e) => {
            e.stopPropagation(); 
            if (!whisperAudio.paused) {
                whisperAudio.pause(); whisperAudio.currentTime = 0; btnWhisper.innerHTML = '<i class="fa-solid fa-microphone-lines"></i>';
                if(isMusicPlaying) gsap.to(bgMusic, { volume: 0.5, duration: 1 });
            } else {
                if(isMusicPlaying) gsap.to(bgMusic, { volume: 0.1, duration: 1 });
                whisperAudio.play().catch(e => console.log(e)); btnWhisper.innerHTML = '<i class="fa-solid fa-stop"></i>';
                whisperAudio.onended = () => { btnWhisper.innerHTML = '<i class="fa-solid fa-microphone-lines"></i>'; if(isMusicPlaying) gsap.to(bgMusic, { volume: 0.5, duration: 1 }); };
            }
        });

        // =========================================================
        // BUKU ENGINE (GSAP ARC PHYSICS & 3D THICKNESS)
        // =========================================================
        class BookEngine {
            constructor(elementId, totalSheetsCount) {
                this.book = document.getElementById(elementId); this.sheets = this.book.querySelectorAll('.sheet');
                this.totalSheets = totalSheetsCount; this.currentSheet = 0; this.isAnimating = false; this.isIndexMode = false;
                this.Z_SPACE = 2.5; // KETEBALAN TIAP HALAMAN (PX)
                
                this.initDepthAndZ(); this.updateVisibility();
            }

            updateVisibility() {
                if(this.isIndexMode) { this.sheets.forEach(sheet => { sheet.style.display = 'block'; const imgs = sheet.querySelectorAll('img'); imgs.forEach(img => { if(img.hasAttribute('loading')) img.removeAttribute('loading'); }); }); return; }
                this.sheets.forEach((sheet, index) => {
                    if (Math.abs(index - this.currentSheet) <= 3) sheet.style.display = 'block'; else sheet.style.display = 'none';
                    if(index >= this.currentSheet && index <= this.currentSheet + 2) { const imgs = sheet.querySelectorAll('img'); imgs.forEach(img => { if(img.hasAttribute('loading')) img.removeAttribute('loading'); }); }
                });
            }

            initDepthAndZ() {
                if(this.isIndexMode) return;
                this.sheets.forEach((sheet, index) => {
                    const targetRotY = index < this.currentSheet ? -180 : 0;
                    const targetZ = index < this.currentSheet ? -index * this.Z_SPACE : (this.totalSheets - index) * this.Z_SPACE;
                    gsap.set(sheet, { rotationY: targetRotY, z: targetZ });
                    sheet.style.zIndex = index < this.currentSheet ? index : this.totalSheets - index;
                });
            }

            updateDepthOnly(animatingIdx = -1) {
                if(this.isIndexMode) return;
                this.sheets.forEach((sheet, index) => {
                    if (index === animatingIdx) return;
                    const targetRotY = index < this.currentSheet ? -180 : 0;
                    const targetZ = index < this.currentSheet ? -index * this.Z_SPACE : (this.totalSheets - index) * this.Z_SPACE;
                    gsap.to(sheet, { rotationY: targetRotY, z: targetZ, duration: 0.8, ease: "power2.out" });
                });
            }

            updateZIndexOnly() { this.sheets.forEach((sheet, index) => { sheet.style.zIndex = (index < this.currentSheet) ? index : this.totalSheets - index; }); }

            centerBook(indicatorEl, btnPrev, btnNext) {
                if(this.isIndexMode) return;
                if (this.currentSheet === 0) { gsap.to(this.book, { xPercent: -25, duration: 1, ease: "power2.out" }); indicatorEl.innerText = "COVER DEPAN"; } 
                else if (this.currentSheet === this.totalSheets) { gsap.to(this.book, { xPercent: 25, duration: 1, ease: "power2.out" }); indicatorEl.innerText = "COVER BELAKANG"; } 
                else {
                    gsap.to(this.book, { xPercent: 0, duration: 1, ease: "power2.out" });
                    let halKiri = (this.currentSheet - 1) * 2; let halKanan = halKiri + 1; let maxHal = (this.totalSheets - 2) * 2;
                    if(this.currentSheet === 1) indicatorEl.innerText = "HAL 1"; else if(this.currentSheet === this.totalSheets - 1) indicatorEl.innerText = `HAL ${maxHal}`; else indicatorEl.innerText = `HAL ${halKiri} - ${halKanan}`;
                }
                btnPrev.disabled = (this.currentSheet === 0); btnNext.disabled = (this.currentSheet === this.totalSheets);
                updatePinUI();
                
                if (this.currentSheet === 0) btnWhisper.classList.add('is-visible');
                else { btnWhisper.classList.remove('is-visible'); whisperAudio.pause(); whisperAudio.currentTime = 0; btnWhisper.innerHTML = '<i class="fa-solid fa-microphone-lines"></i>'; if(isMusicPlaying) gsap.to(bgMusic, { volume: 0.5, duration: 1 }); }
            }

            // ARC PHYSICS SAAT MEMBALIK KE DEPAN
            flipNext(indicatorEl, btnPrev, btnNext) {
                if (this.isAnimating || this.currentSheet >= this.totalSheets || this.isIndexMode) return;
                this.isAnimating = true; playPaperFlip();
                
                const animatingIdx = this.currentSheet;
                const sheet = this.sheets[animatingIdx];
                sheet.classList.add('flipped'); 
                this.currentSheet++;
                
                const targetZ = -animatingIdx * this.Z_SPACE;
                
                gsap.to(sheet, {
                    keyframes: [
                        { rotationY: -90, z: 150, scale: 1.05, duration: 0.4, ease: "sine.in" },
                        { rotationY: -180, z: targetZ, scale: 1, duration: 0.6, ease: "power2.out" }
                    ],
                    onComplete: () => {
                        this.updateZIndexOnly();
                        this.updateVisibility();
                        this.isAnimating = false;
                    }
                });
                
                this.centerBook(indicatorEl, btnPrev, btnNext);
                this.updateDepthOnly(animatingIdx);
            }

            // ARC PHYSICS SAAT MEMBALIK KE BELAKANG
            flipPrev(indicatorEl, btnPrev, btnNext) {
                if (this.isAnimating || this.currentSheet <= 0 || this.isIndexMode) return;
                this.isAnimating = true; playPaperFlip(); 
                
                this.currentSheet--;
                const animatingIdx = this.currentSheet;
                const sheet = this.sheets[animatingIdx];
                sheet.classList.remove('flipped'); 
                
                const targetZ = (this.totalSheets - animatingIdx) * this.Z_SPACE;
                
                gsap.to(sheet, {
                    keyframes: [
                        { rotationY: -90, z: 150, scale: 1.05, duration: 0.4, ease: "sine.in" },
                        { rotationY: 0, z: targetZ, scale: 1, duration: 0.6, ease: "power2.out" }
                    ],
                    onComplete: () => {
                        this.updateZIndexOnly();
                        this.updateVisibility();
                        this.isAnimating = false;
                    }
                });
                
                this.centerBook(indicatorEl, btnPrev, btnNext);
                this.updateDepthOnly(animatingIdx);
            }

            closeBook(indicatorEl, btnPrev, btnNext) {
                if (this.isAnimating || this.isIndexMode || this.currentSheet === 0) return;
                this.isAnimating = true; playPaperFlip(); this.sheets.forEach(sheet => sheet.style.display = 'block'); this.currentSheet = 0;
                this.sheets.forEach((sheet, index) => {
                    sheet.classList.remove('flipped'); const targetZ = (this.totalSheets - index) * this.Z_SPACE;
                    gsap.to(sheet, { rotationY: 0, z: targetZ, duration: 1.2, ease: "power3.inOut" });
                });
                this.centerBook(indicatorEl, btnPrev, btnNext); setTimeout(() => { this.updateZIndexOnly(); this.updateVisibility(); this.isAnimating = false; }, 1300);
            }

            toggleIndexMode(indicatorEl, btnPrev, btnNext) {
                if(this.isAnimating) return; this.isIndexMode = !this.isIndexMode;
                if(this.isIndexMode) {
                    this.updateVisibility(); this.book.classList.add('index-mode'); indicatorEl.innerText = "INDEX MODE"; gsap.to(document.getElementById('etherealText'), { opacity: 0, duration: 0.5 }); btnWhisper.classList.remove('is-visible');
                    const isMobile = window.innerWidth <= 768; const aspect = window.innerWidth / window.innerHeight;
                    let cols = Math.ceil(Math.sqrt(this.totalSheets * aspect)); if (isMobile) cols = Math.max(4, Math.floor(cols * 0.8));
                    const totalRows = Math.ceil(this.totalSheets / cols); const gapX = isMobile ? 100 : 160; const gapY = isMobile ? 130 : 200; const scale = isMobile ? 0.35 : 0.45;
                    const maxGridDim = Math.max(cols * gapX, totalRows * gapY); let pushBack = isMobile ? -(maxGridDim * 2.2) : -(maxGridDim * 1.0); if(pushBack > -1500) pushBack = -1500;
                    gsap.to(this.book, { xPercent: 0, duration: 1.5, ease: "power3.inOut" }); gsap.to(dimCore, { z: pushBack, duration: 2, ease: "expo.inOut" });
                    const startX = -(cols - 1) * gapX / 2; const startY = -(totalRows - 1) * gapY / 2;
                    this.sheets.forEach((sheet, i) => { const row = Math.floor(i / cols); const col = i % cols; gsap.to(sheet, { x: startX + col * gapX, y: startY + row * gapY, z: (Math.random() - 0.5) * 300, rotationX: (Math.random() - 0.5) * 15, rotationY: 0, rotationZ: (Math.random() - 0.5) * 10, scale: scale, duration: 1.5 + Math.random() * 0.5, ease: "expo.inOut", overwrite: "auto" }); });
                } else {
                    this.book.classList.remove('index-mode'); gsap.to(dimCore, { z: 0, duration: 1.5, ease: "power3.inOut" });
                    this.sheets.forEach((sheet, index) => {
                        const targetRotY = index < this.currentSheet ? -180 : 0; const targetZ = index < this.currentSheet ? -index * this.Z_SPACE : (this.totalSheets - index) * this.Z_SPACE;
                        if(index < this.currentSheet) sheet.classList.add('flipped'); else sheet.classList.remove('flipped');
                        gsap.to(sheet, { x: 0, y: 0, z: targetZ, rotationX: 0, rotationY: targetRotY, rotationZ: 0, scale: 1, duration: 1.2, ease: "power3.inOut" });
                    });
                    setTimeout(() => { this.updateZIndexOnly(); this.centerBook(indicatorEl, btnPrev, btnNext); this.updateVisibility(); gsap.to(document.getElementById('etherealText'), { opacity: 1, duration: 0.5 }); }, 1200);
                }
            }
            goToPage(targetIdx, indicatorEl, btnPrev, btnNext) { this.currentSheet = targetIdx; this.toggleIndexMode(indicatorEl, btnPrev, btnNext); }
        }

        const bookPutra = new BookEngine('bookPutra', 77); 
        const bookPutri = new BookEngine('bookPutri', 43); 

        // =========================================================
        // HOLOGRAPHIC LIGHTBOX & GOLDEN SIGNATURE
        // =========================================================
        const openLightbox = (e, bookObj) => {
            if(isShifting || bookObj.isAnimating || bookObj.isIndexMode) return;
            const sheet = e.target.closest('.sheet'); if(!sheet) return;
            const isFlipped = sheet.classList.contains('flipped'); const imgTarget = sheet.querySelector(isFlipped ? '.face.back img' : '.face.front img');
            if(!imgTarget || !imgTarget.src) return;
            
            resetIdleTimer(); stopAutoPlay(); 
            
            const overlay = document.createElement('div'); overlay.className = 'lightbox-overlay';
            const wrapper = document.createElement('div'); wrapper.className = 'lightbox-img-wrapper';
            const clone = document.createElement('img'); clone.src = imgTarget.src; clone.className = 'lightbox-img';
            
            const sigContainer = document.createElement('div'); sigContainer.className = 'signature-overlay';
            sigContainer.innerHTML = `
                <svg viewBox="0 0 300 120" xmlns="http://www.w3.org/2000/svg">
                    <text x="150" y="80" text-anchor="middle" class="signature-text">Successor</text>
                </svg>
            `;

            wrapper.appendChild(clone); wrapper.appendChild(sigContainer); overlay.appendChild(wrapper); document.body.appendChild(overlay);
            
            gsap.to(overlay, { opacity: 1, backdropFilter: "blur(25px)", duration: 0.5 });
            gsap.fromTo(wrapper, { scale: 0.8, y: 50 }, { scale: 1, y: 0, duration: 0.6, ease: "expo.out" });
            
            setTimeout(() => {
                sigContainer.style.opacity = '1';
                const path = sigContainer.querySelector('.signature-text');
                path.style.animation = 'drawSignatureAnim 2.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards';
            }, 600);
            
            overlay.addEventListener('click', () => {
                gsap.to(wrapper, { scale: 0.9, y: -20, duration: 0.4, ease: "power2.in" });
                gsap.to(overlay, { opacity: 0, backdropFilter: "blur(0px)", duration: 0.4, onComplete: () => overlay.remove() });
            });
        };

        // =========================================================
        // CONTROLS & AUTO-PLAY
        // =========================================================
        btnAutoPlay.addEventListener('click', () => {
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(activeEngine.isIndexMode) return; 
            if(isAutoPlaying) { stopAutoPlay(); } else {
                isAutoPlaying = true; btnAutoPlay.classList.add('is-playing'); btnAutoPlay.innerHTML = '<i class="fa-solid fa-pause"></i>';
                autoPlayTimer = setInterval(() => { if(activeEngine.currentSheet < activeEngine.totalSheets) activeEngine.flipNext(indicator, btnPrev, btnNext); else stopAutoPlay(); }, 4000);
            }
        });

        // =========================================================
        // PIN (BOOKMARK) & EPILOGUE
        // =========================================================
        const btnPin = document.getElementById('btnPin');
        const btnGoToPin = document.getElementById('btnGoToPin');
        btnPin.addEventListener('click', () => {
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri; const currentPin = JSON.parse(localStorage.getItem('expedient_pin'));
            if(currentPin && currentPin.dim === activeDim && currentPin.page === activeEngine.currentSheet) { localStorage.removeItem('expedient_pin'); } 
            else { localStorage.setItem('expedient_pin', JSON.stringify({ dim: activeDim, page: activeEngine.currentSheet })); gsap.fromTo(btnPin, {scale: 1.5}, {scale: 1, duration: 0.5, ease: "elastic.out(1, 0.3)"}); }
            updatePinUI();
        });

        btnGoToPin.addEventListener('click', () => {
            const savedPin = JSON.parse(localStorage.getItem('expedient_pin'));
            if(!savedPin || isShifting || bookPutra.isAnimating || bookPutri.isAnimating) return;
            const ethereal = document.getElementById('etherealText');
            ethereal.innerText = "MEMORI DIPULIHKAN"; setTimeout(() => { ethereal.innerText = "THE SYNDICATE"; }, 3000);
            if(savedPin.dim !== activeDim) {
                document.getElementById('btnShift').click();
                setTimeout(() => { let targetEngine = (savedPin.dim === 'putra') ? bookPutra : bookPutri; targetEngine.currentSheet = savedPin.page; targetEngine.initDepthAndZ(); targetEngine.updateVisibility(); targetEngine.centerBook(indicator, btnPrev, btnNext); }, 2100);
            } else {
                let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri; activeEngine.currentSheet = savedPin.page; activeEngine.initDepthAndZ(); activeEngine.updateVisibility(); activeEngine.centerBook(indicator, btnPrev, btnNext);
            }
        });

        const ethereal = document.getElementById('etherealText');
        ethereal.addEventListener('dblclick', () => {
            if(!isEpilogueMode) {
                isEpilogueMode = true; stage.classList.add('epilogue-mode');
                gsap.to(ethereal, { opacity: 0, scale: 0.9, duration: 1, onComplete: () => { ethereal.innerText = "KENANGAN ABADI"; gsap.to(ethereal, { opacity: 1, scale: 1, duration: 2, ease: "power2.out" }); }});
                if(isMusicPlaying) gsap.to(bgMusic, { volume: 1, duration: 2 }); hapticBoom();
            } else {
                isEpilogueMode = false; stage.classList.remove('epilogue-mode');
                gsap.to(ethereal, { opacity: 0, scale: 1.1, duration: 1, onComplete: () => { ethereal.innerText = "THE SYNDICATE"; gsap.to(ethereal, { opacity: 1, scale: 1, duration: 2, ease: "power2.out" }); }});
                if(isMusicPlaying) gsap.to(bgMusic, { volume: 0.5, duration: 2 });
            }
        });

        // =========================================================
        // INIT & OTHER BUTTONS
        // =========================================================
        bookPutra.centerBook(indicator, btnPrev, btnNext);

        const btnIndex = document.getElementById('btnIndex');
        btnIndex.addEventListener('click', () => {
            if(isShifting) return; stopAutoPlay();
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(!activeEngine.isIndexMode) btnIndex.classList.add('is-playing'); else btnIndex.classList.remove('is-playing');
            activeEngine.toggleIndexMode(indicator, btnPrev, btnNext);
        });

        const btnCloseBook = document.getElementById('btnCloseBook');
        btnCloseBook.addEventListener('click', () => {
            if(isShifting) return; stopAutoPlay();
            if(activeDim === 'putra') bookPutra.closeBook(indicator, btnPrev, btnNext); else bookPutri.closeBook(indicator, btnPrev, btnNext);
        });

        const btnShift = document.getElementById('btnShift');
        btnShift.addEventListener('click', () => {
            if(isShifting) return; stopAutoPlay();
            let currentEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(currentEngine.isIndexMode) btnIndex.click(); 
            isShifting = true; warpSpeed = true; playDimensionShift();
            if(activeDim === 'putra') {
                stage.classList.add('dimensi-putri'); btnShift.innerHTML = '<i class="fa-solid fa-rotate"></i> SHIFT TO ALPHA (PUTRA)';
                gsap.to(dimCore, { rotationX: 0, rotationY: -180, duration: 2, ease: "power3.inOut" });
                document.getElementById('bookPutra').style.pointerEvents = 'none'; setTimeout(() => { document.getElementById('bookPutri').style.pointerEvents = 'auto'; }, 1000);
                activeDim = 'putri'; setTimeout(() => { bookPutri.centerBook(indicator, btnPrev, btnNext); isShifting = false; warpSpeed = false; }, 2000);
            } else {
                stage.classList.remove('dimensi-putri'); btnShift.innerHTML = '<i class="fa-solid fa-rotate"></i> SHIFT TO OMEGA (PUTRI)';
                gsap.to(dimCore, { rotationX: 0, rotationY: 0, duration: 2, ease: "power3.inOut" });
                document.getElementById('bookPutri').style.pointerEvents = 'none'; setTimeout(() => { document.getElementById('bookPutra').style.pointerEvents = 'auto'; }, 1000);
                activeDim = 'putra'; setTimeout(() => { bookPutra.centerBook(indicator, btnPrev, btnNext); isShifting = false; warpSpeed = false; }, 2000);
            }
        });

        btnNext.addEventListener('click', () => { if(isShifting) return; stopAutoPlay(); if(activeDim === 'putra') bookPutra.flipNext(indicator, btnPrev, btnNext); else bookPutri.flipNext(indicator, btnPrev, btnNext); });
        btnPrev.addEventListener('click', () => { if(isShifting) return; stopAutoPlay(); if(activeDim === 'putra') bookPutra.flipPrev(indicator, btnPrev, btnNext); else bookPutri.flipPrev(indicator, btnPrev, btnNext); });

        let clickTimer = null; let isSwiping = false; 
        const handleBookTap = (e, bookObj, bookEl) => {
            if(isShifting || isSwiping) { isSwiping = false; return; }
            if(bookObj.isIndexMode) { const sheet = e.target.closest('.sheet'); if(sheet) { const sheetIdx = parseInt(sheet.getAttribute('data-sheet')); btnIndex.classList.remove('is-playing'); bookObj.goToPage(sheetIdx, indicator, btnPrev, btnNext); } return; }
            if(e.detail === 2) { clearTimeout(clickTimer); openLightbox(e, bookObj); } 
            else if(e.detail === 1) { clickTimer = setTimeout(() => { const rect = bookEl.getBoundingClientRect(); if(bookObj.currentSheet === 0) { bookObj.flipNext(indicator, btnPrev, btnNext); return; } if(bookObj.currentSheet === bookObj.totalSheets) { bookObj.flipPrev(indicator, btnPrev, btnNext); return; } if (e.clientX < (rect.left + rect.width / 2)) bookObj.flipPrev(indicator, btnPrev, btnNext); else bookObj.flipNext(indicator, btnPrev, btnNext); }, 250); }
        };

        document.getElementById('bookPutra').addEventListener('click', (e) => handleBookTap(e, bookPutra, document.getElementById('bookPutra')));
        document.getElementById('bookPutri').addEventListener('click', (e) => handleBookTap(e, bookPutri, document.getElementById('bookPutri')));

        let touchStartX = 0; let touchEndX = 0;
        stage.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; isSwiping = false; }, {passive: true});
        stage.addEventListener('touchend', e => {
            if(isShifting) return; touchEndX = e.changedTouches[0].screenX; const distance = touchStartX - touchEndX; let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(activeEngine.isIndexMode) return; 
            if (Math.abs(distance) > 50) { isSwiping = true; stopAutoPlay(); if (distance > 50) activeEngine.flipNext(indicator, btnPrev, btnNext); if (distance < -50) activeEngine.flipPrev(indicator, btnPrev, btnNext); }
        }, {passive: true});

        if (window.DeviceOrientationEvent && /Mobile|Android|iOS|iPhone|iPad/i.test(navigator.userAgent)) {
            let gyroX = 50, gyroY = 50, targetGyroX = 50, targetGyroY = 50;
            window.addEventListener('deviceorientation', (e) => { let tiltX = Math.min(Math.max(e.gamma, -20), 20); let tiltY = Math.min(Math.max(e.beta - 45, -20), 20); targetGyroX = 50 + (tiltX / 20) * 15; targetGyroY = 50 + (tiltY / 20) * 15; });
            const updateGyro = () => { gyroX += (targetGyroX - gyroX) * 0.05; gyroY += (targetGyroY - gyroY) * 0.05; stage.style.perspectiveOrigin = `${gyroX}% ${gyroY}%`; requestAnimationFrame(updateGyro); }; updateGyro();
        } else {
            window.addEventListener('mousemove', (e) => {
                const x = (e.clientX / window.innerWidth) * 100; const y = (e.clientY / window.innerHeight) * 100; document.documentElement.style.setProperty('--mx', `${x}%`); document.documentElement.style.setProperty('--my', `${y}%`);
                if(!isShifting && !isIdle) { let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri; if(activeEngine.isIndexMode) return; const tiltX = (window.innerHeight / 2 - e.clientY) / 60; const tiltY = (e.clientX - window.innerWidth / 2) / 60; const baseY = activeDim === 'putra' ? 0 : -180; gsap.to(dimCore, { rotationX: tiltX, rotationY: baseY + tiltY, duration: 0.8, ease: "power2.out" }); }
            });
        }
    });
</script>
<?= $this->endSection() ?>