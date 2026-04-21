<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Arsip Visual 5D | The Syndicate Yearbook
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ================= 1. THE STAGE (AWWARDS LEVEL) ================= */
    .gallery-stage {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: transparent; 
        display: flex;
        justify-content: center;
        align-items: center;
        perspective: 3500px; 
        perspective-origin: 50% 50%; 
        user-select: none;
        transition: perspective-origin 0.1s ease-out;
        touch-action: manipulation; 
    }

    #dustCanvas {
        position: absolute;
        inset: 0;
        z-index: 2;
        pointer-events: none;
    }

    .ambient-light {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(212,175,55,0.15) 0%, transparent 60%);
        pointer-events: none;
        z-index: 3;
        transition: background-color 1.5s ease;
    }
    
    .gallery-stage.dimensi-putri .ambient-light {
        background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(224, 191, 184, 0.2) 0%, transparent 60%);
    }

    :root[data-theme="light"] .ambient-light {
        background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(212,175,55,0.3) 0%, transparent 70%);
    }

    /* ================= NEW: ETHEREAL TYPOGRAPHY ================= */
    .ethereal-text {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%) translateZ(-400px);
        font-family: 'Playfair Display', serif;
        font-size: clamp(4rem, 10vw, 12rem);
        color: rgba(212, 175, 55, 0.1);
        text-transform: uppercase;
        letter-spacing: 30px;
        pointer-events: none;
        white-space: nowrap;
        z-index: 1; 
        filter: blur(4px);
        transition: color 1.5s ease;
    }

    .gallery-stage.dimensi-putri .ethereal-text { color: rgba(224, 191, 184, 0.1); }
    :root[data-theme="light"] .ethereal-text { color: rgba(212, 175, 55, 0.2); filter: blur(2px); }

    /* ================= CORE & BUKU ================= */
    .dimension-core {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        transform-style: preserve-3d;
        transition: transform 1.5s cubic-bezier(0.25, 1, 0.5, 1);
        z-index: 10;
        will-change: transform;
    }

    .book-scene {
        position: absolute;
        width: min(90vw, calc(75vh * 3));
        aspect-ratio: 3 / 1; 
        transform-style: preserve-3d;
        transition: transform 1s cubic-bezier(0.645, 0.045, 0.355, 1);
        will-change: transform;
        -webkit-box-reflect: below 2px linear-gradient(transparent, transparent 60%, rgba(255,255,255,0.3));
    }

    :root[data-theme="light"] .book-scene {
        -webkit-box-reflect: below 2px linear-gradient(transparent, transparent 60%, rgba(0,0,0,0.15));
    }

    #bookPutra { transform: translateZ(100px) rotateX(10deg); }
    #bookPutri { transform: rotateY(180deg) translateZ(100px) rotateX(-10deg); pointer-events: none; }

    /* ================= LEMBARAN KERTAS ================= */
    .sheet {
        position: absolute;
        right: 0;
        width: 50%;
        height: 100%;
        transform-style: preserve-3d;
        transform-origin: left center; 
        transition: transform 1.2s cubic-bezier(0.645, 0.045, 0.355, 1);
        cursor: grab;
        will-change: transform;
    }
    .sheet:active { cursor: grabbing; }
    .sheet.flipped { transform: rotateY(-180deg); }

    .book-scene.index-mode .sheet { box-shadow: 0 10px 30px rgba(0,0,0,0.8); cursor: pointer; }

    .face {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        background-color: #030504; 
        box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    :root[data-theme="light"] .face { background-color: #f8f8f8; box-shadow: inset 0 0 8px rgba(0,0,0,0.1); border: 1px solid rgba(212, 175, 55, 0.4); }

    .face.front { transform: rotateY(0deg); border-radius: 2px 8px 8px 2px; }
    .face.back { transform: rotateY(180deg); border-radius: 8px 2px 2px 8px; }
    .face img { width: 100%; height: 100%; object-fit: cover; pointer-events: none; }

    .face.front::after { content: ''; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(to right, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.1) 6%, transparent 15%); }
    .face.back::after { content: ''; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(to left, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.1) 6%, transparent 15%); }
    
    :root[data-theme="light"] .face.front::after { background: linear-gradient(to right, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.05) 6%, transparent 15%); }
    :root[data-theme="light"] .face.back::after { background: linear-gradient(to left, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.05) 6%, transparent 15%); }

    #bookPutra::after { content: ''; position: absolute; top: 1%; bottom: 1%; left: 50%; width: 3px; background: #d4af37; box-shadow: 0 0 10px rgba(212,175,55,0.5); z-index: 0; }
    #bookPutri::after { content: ''; position: absolute; top: 1%; bottom: 1%; left: 50%; width: 3px; background: #e0bfb8; box-shadow: 0 0 10px rgba(224,191,184,0.5); z-index: 0; }

    /* ================= HUD & NEW FEATURES ================= */
    .dimension-shift-btn {
        position: absolute; top: 3vh; left: 50%; transform: translateX(-50%);
        background: transparent; border: 1px solid #d4af37; color: #d4af37;
        padding: 10px 30px; border-radius: 30px; font-family: 'Courier New', monospace;
        letter-spacing: 3px; font-weight: bold; cursor: pointer; z-index: 100;
        transition: 0.4s; box-shadow: 0 0 15px rgba(212,175,55,0.2); font-size: clamp(0.7rem, 1.5vw, 1rem);
    }
    
    :root[data-theme="light"] .dimension-shift-btn { background: rgba(255,255,255,0.8); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .gallery-stage.dimensi-putri .dimension-shift-btn { border-color: #e0bfb8; color: #e0bfb8; box-shadow: 0 0 15px rgba(224, 191, 184, 0.2); }
    :root[data-theme="light"] .gallery-stage.dimensi-putri .dimension-shift-btn { color: #b08d85; border-color: #b08d85; }

    .dimension-shift-btn:hover { background: #d4af37; color: #000; transform: translateX(-50%) scale(1.05); }
    .gallery-stage.dimensi-putri .dimension-shift-btn:hover { background: #e0bfb8; color: #000; }

    .gallery-hud {
        position: absolute; bottom: 4vh; left: 50%; transform: translateX(-50%);
        display: flex; align-items: center; gap: clamp(10px, 2vw, 25px); z-index: 100;
        background: var(--glass-bg); backdrop-filter: var(--glass-blur);
        padding: 10px 25px; border-radius: 50px; border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
    }

    .gallery-stage.dimensi-putri .gallery-hud { border-color: rgba(224, 191, 184, 0.3); }

    .btn-nav, .btn-icon { background: transparent; border: none; color: #d4af37; cursor: pointer; transition: 0.3s; }
    .btn-nav { font-size: clamp(1.2rem, 2vw, 1.8rem); }
    .btn-icon { font-size: clamp(1rem, 1.5vw, 1.3rem); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    
    .gallery-stage.dimensi-putri .btn-nav, .gallery-stage.dimensi-putri .btn-icon { color: #e0bfb8; }
    :root[data-theme="light"] .gallery-stage.dimensi-putri .btn-nav, :root[data-theme="light"] .gallery-stage.dimensi-putri .btn-icon { color: #b08d85; }
    
    .btn-nav:hover, .btn-icon:hover { transform: scale(1.2) translateY(-2px); text-shadow: 0 0 15px rgba(212,175,55,0.8); }
    .btn-nav:disabled { opacity: 0.2; cursor: not-allowed; transform: none; text-shadow: none; }
    
    .btn-icon.is-playing { background: rgba(212,175,55,0.2); border: 1px solid #d4af37; box-shadow: 0 0 15px rgba(212,175,55,0.5); }
    .gallery-stage.dimensi-putri .btn-icon.is-playing { background: rgba(224, 191, 184, 0.2); border: 1px solid #e0bfb8; box-shadow: 0 0 15px rgba(224, 191, 184, 0.5); }

    .page-indicator { color: var(--text-secondary); font-family: 'Courier New', monospace; font-size: clamp(0.7rem, 1vw, 0.9rem); letter-spacing: 2px; font-weight: bold; min-width: 120px; text-align: center; transition: 0.3s; }

    /* ================= HOLOGRAPHIC LIGHTBOX ================= */
    .lightbox-overlay {
        position: fixed; inset: 0; z-index: 999999;
        background: rgba(3, 5, 4, 0.3); backdrop-filter: blur(0px);
        display: flex; justify-content: center; align-items: center;
        opacity: 0; cursor: zoom-out;
    }
    :root[data-theme="light"] .lightbox-overlay { background: rgba(255, 255, 255, 0.3); }
    
    .lightbox-img {
        max-width: 90vw; max-height: 90vh;
        border-radius: 8px; border: 1px solid rgba(212, 175, 55, 0.5);
        box-shadow: 0 30px 60px rgba(0,0,0,0.8), 0 0 50px rgba(212,175,55,0.2);
    }
    :root[data-theme="light"] .lightbox-img { box-shadow: 0 30px 60px rgba(0,0,0,0.2), 0 0 50px rgba(212,175,55,0.4); }

    /* ================= PORTRAIT LOCK ================= */
    .portrait-lock { display: none; position: fixed; inset: 0; z-index: 99999; background: var(--bg-main); flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; }
    .portrait-lock i { font-size: clamp(3rem, 10vw, 5rem); color: #d4af37; margin-bottom: 20px; animation: tiltPhone 2s infinite; }
    .portrait-lock h2 { color: var(--text-primary); font-family: 'Playfair Display', serif; font-size: clamp(1.2rem, 5vw, 2rem); margin-bottom: 10px; }
    .portrait-lock p { color: var(--text-secondary); font-size: clamp(0.8rem, 3vw, 1rem); line-height: 1.5; }
    
    @keyframes tiltPhone { 0%, 100% { transform: rotate(0deg); } 50% { transform: rotate(-90deg); color: var(--text-primary); } }
    @media (orientation: portrait) { .portrait-lock { display: flex; } .gallery-stage { display: none; } }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="portrait-lock">
    <i class="fa-solid fa-mobile-screen"></i>
    <h2>AKSES TERKUNCI</h2>
    <p>Yearbook 5D membutuhkan mode Landscape.<br>Silakan putar perangkat Anda.</p>
</div>

<div class="gallery-stage" id="galleryStage">
    
    <div class="ethereal-text" id="etherealText">THE INITIATION</div>

    <canvas id="dustCanvas"></canvas>
    <div class="ambient-light" id="ambientLight"></div>
    
    <button class="dimension-shift-btn hover-trigger" id="btnShift"><i class="fa-solid fa-rotate"></i> SHIFT TO OMEGA (PUTRI)</button>

    <div class="dimension-core" id="dimCore">
        
        <div class="book-scene" id="bookPutra">
            <?php $pathPutra = base_url('assets/foto_putra/'); $idxPutra = 0; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutra++ ?>">
                <div class="face front cover-material"><img src="<?= $pathPutra ?>Cover Depan.webp" loading="lazy"></div>
                <div class="face back"><img src="<?= $pathPutra ?>Cover Dalem Depan.webp" loading="lazy"></div>
            </div>
            <?php for($i = 1; $i <= 75; $i++): ?>
                <div class="sheet cursor-bind" data-sheet="<?= $idxPutra++ ?>">
                    <div class="face front"><img src="<?= $pathPutra ?>Hal <?= ($i * 2) - 1 ?>.webp" loading="lazy"></div>
                    <div class="face back"><img src="<?= $pathPutra ?>Hal <?= ($i * 2) ?>.webp" loading="lazy"></div>
                </div>
            <?php endfor; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutra++ ?>">
                <div class="face front"><img src="<?= $pathPutra ?>Cover Dalem Belakang.webp" loading="lazy"></div>
                <div class="face back cover-material"><img src="<?= $pathPutra ?>Cover Belakang.webp" loading="lazy"></div>
            </div>
        </div>

        <div class="book-scene" id="bookPutri">
            <?php $pathPutri = base_url('assets/foto_putri/'); $idxPutri = 0; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutri++ ?>">
                <div class="face front cover-material"><img src="<?= $pathPutri ?>Cover Depan.webp" loading="lazy"></div>
                <div class="face back"><img src="<?= $pathPutri ?>Cover Dalem Depan.webp" loading="lazy"></div>
            </div>
            <?php for($i = 1; $i <= 41; $i++): ?>
                <div class="sheet cursor-bind" data-sheet="<?= $idxPutri++ ?>">
                    <div class="face front"><img src="<?= $pathPutri ?>Hal <?= ($i * 2) - 1 ?>.webp" loading="lazy"></div>
                    <div class="face back"><img src="<?= $pathPutri ?>Hal <?= ($i * 2) ?>.webp" loading="lazy"></div>
                </div>
            <?php endfor; ?>
            <div class="sheet cursor-bind" data-sheet="<?= $idxPutri++ ?>">
                <div class="face front"><img src="<?= $pathPutri ?>Cover Dalem Belakang.webp" loading="lazy"></div>
                <div class="face back cover-material"><img src="<?= $pathPutri ?>Cover Belakang.webp" loading="lazy"></div>
            </div>
        </div>

    </div>

    <div class="gallery-hud">
        <button class="btn-icon hover-trigger" id="btnAutoPlay" title="Cinematic Auto-Play"><i class="fa-solid fa-play"></i></button>
        <button class="btn-icon hover-trigger" id="btnIndex" title="Constellation Grid"><i class="fa-solid fa-border-all"></i></button>
        <button class="btn-nav hover-trigger" id="btnPrev"><i class="fa-solid fa-arrow-left"></i></button>
        <div class="page-indicator" id="pageIndicator">COVER DEPAN</div>
        <button class="btn-nav hover-trigger" id="btnNext"><i class="fa-solid fa-arrow-right"></i></button>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.config({ force3D: true });

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
            
            filter.type = 'bandpass';
            filter.frequency.value = 1500;
            
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

        const playGridExplosion = () => {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(800, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(50, audioCtx.currentTime + 1);
            gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1);
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 1);
            hapticBoom();
        }

        // =========================================================
        // HOLOGRAPHIC DUST 
        // =========================================================
        const canvas = document.getElementById('dustCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        let warpSpeed = false;

        const resizeCanvas = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
        window.addEventListener('resize', resizeCanvas); resizeCanvas();

        for(let i=0; i<40; i++) { 
            particles.push({
                x: Math.random() * canvas.width, y: Math.random() * canvas.height,
                r: Math.random() * 2, vx: (Math.random() - 0.5) * 0.5, vy: (Math.random() - 0.5) * 0.5,
                alpha: Math.random()
            });
        }

        const renderDust = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';

            particles.forEach(p => {
                p.x += warpSpeed ? p.vx * 30 : p.vx;
                p.y += warpSpeed ? p.vy * 30 : p.vy;
                
                if(p.x < 0) p.x = canvas.width; if(p.x > canvas.width) p.x = 0;
                if(p.y < 0) p.y = canvas.height; if(p.y > canvas.height) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = isLight ? `rgba(212,175,55,${p.alpha})` : `rgba(255,255,255,${p.alpha})`;
                ctx.fill();
            });
            requestAnimationFrame(renderDust);
        };
        renderDust();

        // =========================================================
        // BUKU ENGINE KETEBALAN & ROTASI (DENGAN INDEX MODE)
        // =========================================================
        class BookEngine {
            constructor(elementId, totalSheetsCount) {
                this.book = document.getElementById(elementId);
                this.sheets = this.book.querySelectorAll('.sheet');
                this.totalSheets = totalSheetsCount;
                this.currentSheet = 0;
                this.isAnimating = false;
                this.isIndexMode = false;
                this.initDepthAndZ();
            }

            initDepthAndZ() {
                if(this.isIndexMode) return;
                this.sheets.forEach((sheet, index) => {
                    if (index < this.currentSheet) {
                        sheet.style.transform = `rotateY(-180deg) translateZ(${-index * 1.5}px)`;
                        sheet.style.zIndex = index;
                    } else {
                        sheet.style.transform = `rotateY(0deg) translateZ(${(this.totalSheets - index) * 1.5}px)`;
                        sheet.style.zIndex = this.totalSheets - index;
                    }
                });
            }

            updateDepthOnly() {
                if(this.isIndexMode) return;
                this.sheets.forEach((sheet, index) => {
                    if (index < this.currentSheet) {
                        sheet.style.transform = `rotateY(-180deg) translateZ(${-index * 1.5}px)`;
                    } else {
                        sheet.style.transform = `rotateY(0deg) translateZ(${(this.totalSheets - index) * 1.5}px)`;
                    }
                });
            }

            updateZIndexOnly() {
                this.sheets.forEach((sheet, index) => {
                    sheet.style.zIndex = (index < this.currentSheet) ? index : this.totalSheets - index;
                });
            }

            centerBook(indicatorEl, btnPrev, btnNext) {
                if(this.isIndexMode) return;
                if (this.currentSheet === 0) {
                    gsap.to(this.book, { xPercent: -25, duration: 1, ease: "power2.out" });
                    indicatorEl.innerText = "COVER DEPAN";
                } else if (this.currentSheet === this.totalSheets) {
                    gsap.to(this.book, { xPercent: 25, duration: 1, ease: "power2.out" });
                    indicatorEl.innerText = "COVER BELAKANG";
                } else {
                    gsap.to(this.book, { xPercent: 0, duration: 1, ease: "power2.out" });
                    let halKiri = (this.currentSheet - 1) * 2;
                    let halKanan = halKiri + 1;
                    let maxHal = (this.totalSheets - 2) * 2;
                    if(this.currentSheet === 1) indicatorEl.innerText = "HAL 1";
                    else if(this.currentSheet === this.totalSheets - 1) indicatorEl.innerText = `HAL ${maxHal}`;
                    else indicatorEl.innerText = `HAL ${halKiri} - ${halKanan}`;
                }
                btnPrev.disabled = (this.currentSheet === 0);
                btnNext.disabled = (this.currentSheet === this.totalSheets);

                // IDEA 2: ETHEREAL TYPOGRAPHY UPDATE
                const ethereal = document.getElementById('etherealText');
                const phrases = ["THE INITIATION", "AWAKENING", "MOMENTUM", "VANGUARD", "ECHOES", "UNBREAKABLE", "THE LEGACY"];
                const pct = this.currentSheet / this.totalSheets;
                const phraseIdx = Math.min(Math.floor(pct * phrases.length), phrases.length - 1);
                
                if(ethereal.innerText !== phrases[phraseIdx]) {
                    gsap.to(ethereal, { opacity: 0, scale: 0.9, duration: 0.5, onComplete: () => {
                        ethereal.innerText = phrases[phraseIdx];
                        gsap.to(ethereal, { opacity: 1, scale: 1, duration: 1, ease: "power2.out" });
                    }});
                }
            }

            flipNext(indicatorEl, btnPrev, btnNext) {
                if (this.isAnimating || this.currentSheet >= this.totalSheets || this.isIndexMode) return;
                this.isAnimating = true; playPaperFlip();
                this.sheets[this.currentSheet].classList.add('flipped');
                this.currentSheet++;
                this.centerBook(indicatorEl, btnPrev, btnNext);
                this.updateDepthOnly();
                setTimeout(() => this.updateZIndexOnly(), 400);
                setTimeout(() => this.isAnimating = false, 1200);
            }

            flipPrev(indicatorEl, btnPrev, btnNext) {
                if (this.isAnimating || this.currentSheet <= 0 || this.isIndexMode) return;
                this.isAnimating = true; playPaperFlip();
                this.currentSheet--;
                this.sheets[this.currentSheet].classList.remove('flipped');
                this.centerBook(indicatorEl, btnPrev, btnNext);
                this.updateDepthOnly();
                setTimeout(() => this.updateZIndexOnly(), 400);
                setTimeout(() => this.isAnimating = false, 1200);
            }

            // IDEA 1: THE CONSTELLATION GRID (BUG FIXED - DYNAMIC AUTO FRAMING)
            toggleIndexMode(indicatorEl, btnPrev, btnNext) {
                if(this.isAnimating) return;
                this.isIndexMode = !this.isIndexMode;
                const ethereal = document.getElementById('etherealText');
                
                if(this.isIndexMode) {
                    playGridExplosion();
                    this.book.classList.add('index-mode');
                    indicatorEl.innerText = "INDEX MODE";
                    gsap.to(ethereal, { opacity: 0, duration: 0.5 }); 
                    
                    const isMobile = window.innerWidth <= 768;
                    const aspect = window.innerWidth / window.innerHeight;
                    
                    // ALGORITMA DYNAMIC GRID (Anti-Keluar Layar)
                    let cols = Math.ceil(Math.sqrt(this.totalSheets * aspect));
                    if (isMobile) cols = Math.max(4, Math.floor(cols * 0.8)); 
                    
                    const totalRows = Math.ceil(this.totalSheets / cols);
                    
                    const gapX = isMobile ? 100 : 160;
                    const gapY = isMobile ? 130 : 200;
                    const scale = isMobile ? 0.35 : 0.45;
                    
                    const gridW = cols * gapX;
                    const gridH = totalRows * gapY;
                    
                    // Kamera Z didorong dinamis berdasarkan ukuran grid terlebar
                    const maxGridDim = Math.max(gridW, gridH);
                    let pushBack = isMobile ? -(maxGridDim * 2.2) : -(maxGridDim * 1.0);
                    
                    // Safety Bounds agar tidak terlalu dekat
                    if(pushBack > -1500) pushBack = -1500;
                    
                    gsap.to(this.book, { xPercent: 0, duration: 1.5, ease: "power3.inOut" });
                    gsap.to(dimCore, { z: pushBack, duration: 2, ease: "expo.inOut" });
                    
                    const startX = -(cols - 1) * gapX / 2;
                    const startY = -(totalRows - 1) * gapY / 2;
                    
                    this.sheets.forEach((sheet, i) => {
                        const row = Math.floor(i / cols);
                        const col = i % cols;
                        
                        gsap.to(sheet, {
                            x: startX + col * gapX,
                            y: startY + row * gapY,
                            z: (Math.random() - 0.5) * 300, 
                            rotationX: (Math.random() - 0.5) * 15,
                            rotationY: 0, 
                            rotationZ: (Math.random() - 0.5) * 10,
                            scale: scale,
                            duration: 1.5 + Math.random() * 0.5,
                            ease: "expo.inOut",
                            overwrite: "auto"
                        });
                    });
                } else {
                    playDimensionShift(); 
                    this.book.classList.remove('index-mode');
                    gsap.to(dimCore, { z: 0, duration: 1.5, ease: "power3.inOut" });
                    
                    this.sheets.forEach((sheet, index) => {
                        const targetRotY = index < this.currentSheet ? -180 : 0;
                        const targetZ = index < this.currentSheet ? -index * 1.5 : (this.totalSheets - index) * 1.5;
                        
                        if(index < this.currentSheet) sheet.classList.add('flipped');
                        else sheet.classList.remove('flipped');

                        gsap.to(sheet, {
                            x: 0, y: 0, z: targetZ,
                            rotationX: 0, rotationY: targetRotY, rotationZ: 0,
                            scale: 1,
                            duration: 1.2,
                            ease: "power3.inOut",
                            onComplete: () => {
                                sheet.style.transform = `rotateY(${targetRotY}deg) translateZ(${targetZ}px)`;
                            }
                        });
                    });
                    
                    setTimeout(() => {
                        this.updateZIndexOnly();
                        this.centerBook(indicatorEl, btnPrev, btnNext);
                    }, 1200);
                }
            }

            goToPage(targetIdx, indicatorEl, btnPrev, btnNext) {
                this.currentSheet = targetIdx;
                this.toggleIndexMode(indicatorEl, btnPrev, btnNext);
            }
        }

        const bookPutra = new BookEngine('bookPutra', 77); 
        const bookPutri = new BookEngine('bookPutri', 43); 

        let activeDim = 'putra';
        let isShifting = false;
        const dimCore = document.getElementById('dimCore');
        const stage = document.getElementById('galleryStage');
        const indicator = document.getElementById('pageIndicator');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');

        bookPutra.centerBook(indicator, btnPrev, btnNext);

        // =========================================================
        // FEATURE: THE CONSTELLATION GRID (BUTTON TRIGGER)
        // =========================================================
        const btnIndex = document.getElementById('btnIndex');
        btnIndex.addEventListener('click', () => {
            if(isShifting) return;
            stopAutoPlay();
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            
            if(!activeEngine.isIndexMode) {
                btnIndex.classList.add('is-playing');
            } else {
                btnIndex.classList.remove('is-playing');
            }
            activeEngine.toggleIndexMode(indicator, btnPrev, btnNext);
        });

        // =========================================================
        // FEATURE: CINEMATIC AUTO-PLAY 
        // =========================================================
        let autoPlayTimer;
        let isAutoPlaying = false;
        const btnAutoPlay = document.getElementById('btnAutoPlay');

        const stopAutoPlay = () => {
            if(!isAutoPlaying) return;
            isAutoPlaying = false;
            btnAutoPlay.classList.remove('is-playing');
            btnAutoPlay.innerHTML = '<i class="fa-solid fa-play"></i>';
            clearInterval(autoPlayTimer);
        };

        btnAutoPlay.addEventListener('click', () => {
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(activeEngine.isIndexMode) return; 

            if(isAutoPlaying) {
                stopAutoPlay();
            } else {
                isAutoPlaying = true;
                btnAutoPlay.classList.add('is-playing');
                btnAutoPlay.innerHTML = '<i class="fa-solid fa-pause"></i>';
                
                autoPlayTimer = setInterval(() => {
                    if(activeEngine.currentSheet < activeEngine.totalSheets) {
                        activeEngine.flipNext(indicator, btnPrev, btnNext);
                    } else {
                        stopAutoPlay();
                    }
                }, 4000); 
            }
        });

        // =========================================================
        // THE DIMENSION SHIFT 
        // =========================================================
        const btnShift = document.getElementById('btnShift');
        btnShift.addEventListener('click', () => {
            if(isShifting) return;
            stopAutoPlay(); 
            
            let currentEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            if(currentEngine.isIndexMode) btnIndex.click(); 

            isShifting = true;
            warpSpeed = true; 
            playDimensionShift(); 

            if(activeDim === 'putra') {
                stage.classList.add('dimensi-putri');
                btnShift.innerHTML = '<i class="fa-solid fa-rotate"></i> SHIFT TO ALPHA (PUTRA)';
                gsap.to(dimCore, { rotationX: 0, rotationY: -180, duration: 2, ease: "power3.inOut" });
                
                document.getElementById('bookPutra').style.pointerEvents = 'none';
                setTimeout(() => { document.getElementById('bookPutri').style.pointerEvents = 'auto'; }, 1000);

                activeDim = 'putri';
                setTimeout(() => { bookPutri.centerBook(indicator, btnPrev, btnNext); isShifting = false; warpSpeed = false; }, 2000);
            } else {
                stage.classList.remove('dimensi-putri');
                btnShift.innerHTML = '<i class="fa-solid fa-rotate"></i> SHIFT TO OMEGA (PUTRI)';
                gsap.to(dimCore, { rotationX: 0, rotationY: 0, duration: 2, ease: "power3.inOut" });
                
                document.getElementById('bookPutri').style.pointerEvents = 'none';
                setTimeout(() => { document.getElementById('bookPutra').style.pointerEvents = 'auto'; }, 1000);

                activeDim = 'putra';
                setTimeout(() => { bookPutra.centerBook(indicator, btnPrev, btnNext); isShifting = false; warpSpeed = false; }, 2000);
            }
        });

        // =========================================================
        // FEATURE: HOLOGRAPHIC LIGHTBOX (EKSTRAKSI MEMORI)
        // =========================================================
        const openLightbox = (e, bookObj) => {
            if(isShifting || bookObj.isAnimating || bookObj.isIndexMode) return;
            
            const sheet = e.target.closest('.sheet');
            if(!sheet) return;

            const isFlipped = sheet.classList.contains('flipped');
            const imgTarget = sheet.querySelector(isFlipped ? '.face.back img' : '.face.front img');
            
            if(!imgTarget || !imgTarget.src) return;

            stopAutoPlay(); 

            const overlay = document.createElement('div');
            overlay.className = 'lightbox-overlay';
            
            const clone = document.createElement('img');
            clone.src = imgTarget.src;
            clone.className = 'lightbox-img';
            
            overlay.appendChild(clone);
            document.body.appendChild(overlay);

            gsap.to(overlay, { opacity: 1, backdropFilter: "blur(25px)", duration: 0.5 });
            gsap.fromTo(clone, { scale: 0.8, y: 50 }, { scale: 1, y: 0, duration: 0.6, ease: "expo.out" });

            overlay.addEventListener('click', () => {
                gsap.to(clone, { scale: 0.9, y: -20, duration: 0.4, ease: "power2.in" });
                gsap.to(overlay, { opacity: 0, backdropFilter: "blur(0px)", duration: 0.4, onComplete: () => overlay.remove() });
            });
        };

        // =========================================================
        // INTERAKSI UMUM & DOUBLE CLICK
        // =========================================================
        btnNext.addEventListener('click', () => {
            if(isShifting) return; stopAutoPlay();
            if(activeDim === 'putra') bookPutra.flipNext(indicator, btnPrev, btnNext); else bookPutri.flipNext(indicator, btnPrev, btnNext);
        });

        btnPrev.addEventListener('click', () => {
            if(isShifting) return; stopAutoPlay();
            if(activeDim === 'putra') bookPutra.flipPrev(indicator, btnPrev, btnNext); else bookPutri.flipPrev(indicator, btnPrev, btnNext);
        });

        let clickTimer = null;
        let isSwiping = false; 

        const handleBookTap = (e, bookObj, bookEl) => {
            if(isShifting || isSwiping) { isSwiping = false; return; }
            
            // Logika Klik pada Index Mode (Lompat ke halaman)
            if(bookObj.isIndexMode) {
                const sheet = e.target.closest('.sheet');
                if(sheet) {
                    const sheetIdx = parseInt(sheet.getAttribute('data-sheet'));
                    btnIndex.classList.remove('is-playing');
                    bookObj.goToPage(sheetIdx, indicator, btnPrev, btnNext);
                }
                return;
            }

            if(e.detail === 2) {
                clearTimeout(clickTimer);
                openLightbox(e, bookObj);
            } else if(e.detail === 1) {
                clickTimer = setTimeout(() => {
                    stopAutoPlay();
                    const rect = bookEl.getBoundingClientRect();
                    if(bookObj.currentSheet === 0) { bookObj.flipNext(indicator, btnPrev, btnNext); return; }
                    if(bookObj.currentSheet === bookObj.totalSheets) { bookObj.flipPrev(indicator, btnPrev, btnNext); return; }
                    if (e.clientX < (rect.left + rect.width / 2)) bookObj.flipPrev(indicator, btnPrev, btnNext);
                    else bookObj.flipNext(indicator, btnPrev, btnNext);
                }, 250); 
            }
        };

        document.getElementById('bookPutra').addEventListener('click', (e) => handleBookTap(e, bookPutra, document.getElementById('bookPutra')));
        document.getElementById('bookPutri').addEventListener('click', (e) => handleBookTap(e, bookPutri, document.getElementById('bookPutri')));

        // SWIPE MOBILE
        let touchStartX = 0; let touchEndX = 0;
        stage.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; isSwiping = false; }, {passive: true});
        stage.addEventListener('touchend', e => {
            if(isShifting) return;
            touchEndX = e.changedTouches[0].screenX;
            const distance = touchStartX - touchEndX;
            let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
            
            if(activeEngine.isIndexMode) return; 

            if (Math.abs(distance) > 50) {
                isSwiping = true; stopAutoPlay();
                if (distance > 50) activeEngine.flipNext(indicator, btnPrev, btnNext);    
                if (distance < -50) activeEngine.flipPrev(indicator, btnPrev, btnNext);     
            }
        }, {passive: true});

        // =========================================================
        // IDEA 4: MAGNETIC PAGE CORNER (KERTAS HIDUP PC)
        // =========================================================
        const applyMagneticCorner = (e, bookObj, bookEl) => {
            if(bookObj.isIndexMode || bookObj.isAnimating || isShifting) return;
            const rect = bookEl.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const w = rect.width;
            
            if(x > w * 0.85 && bookObj.currentSheet < bookObj.totalSheets) {
                gsap.to(bookObj.sheets[bookObj.currentSheet], { rotationY: -12, duration: 0.3, overwrite: "auto" });
            } else if (x < w * 0.15 && bookObj.currentSheet > 0) {
                gsap.to(bookObj.sheets[bookObj.currentSheet - 1], { rotationY: -168, duration: 0.3, overwrite: "auto" });
            } else {
                if(bookObj.currentSheet < bookObj.totalSheets) gsap.to(bookObj.sheets[bookObj.currentSheet], { rotationY: 0, duration: 0.3, overwrite: "auto" });
                if(bookObj.currentSheet > 0) gsap.to(bookObj.sheets[bookObj.currentSheet - 1], { rotationY: -180, duration: 0.3, overwrite: "auto" });
            }
        };

        const resetMagneticCorner = (bookObj) => {
            if(bookObj.isIndexMode || bookObj.isAnimating) return;
            if(bookObj.currentSheet < bookObj.totalSheets) gsap.to(bookObj.sheets[bookObj.currentSheet], { rotationY: 0, duration: 0.3, overwrite: "auto" });
            if(bookObj.currentSheet > 0) gsap.to(bookObj.sheets[bookObj.currentSheet - 1], { rotationY: -180, duration: 0.3, overwrite: "auto" });
        };

        if(!(/Mobile|Android|iOS|iPhone|iPad/i.test(navigator.userAgent))) {
            document.getElementById('bookPutra').addEventListener('mousemove', (e) => applyMagneticCorner(e, bookPutra, document.getElementById('bookPutra')));
            document.getElementById('bookPutra').addEventListener('mouseleave', () => resetMagneticCorner(bookPutra));
            document.getElementById('bookPutri').addEventListener('mousemove', (e) => applyMagneticCorner(e, bookPutri, document.getElementById('bookPutri')));
            document.getElementById('bookPutri').addEventListener('mouseleave', () => resetMagneticCorner(bookPutri));
        }

        // =========================================================
        // GYROSCOPE VR (MOBILE) & MOUSE PARALLAX (PC)
        // =========================================================
        if (window.DeviceOrientationEvent && /Mobile|Android|iOS|iPhone|iPad/i.test(navigator.userAgent)) {
            let gyroX = 50, gyroY = 50, targetGyroX = 50, targetGyroY = 50;
            window.addEventListener('deviceorientation', (e) => {
                let tiltX = Math.min(Math.max(e.gamma, -45), 45); 
                let tiltY = Math.min(Math.max(e.beta - 45, -45), 45); 
                targetGyroX = 50 + (tiltX / 45) * 35; 
                targetGyroY = 50 + (tiltY / 45) * 35;
            });
            const updateGyro = () => {
                gyroX += (targetGyroX - gyroX) * 0.05; 
                gyroY += (targetGyroY - gyroY) * 0.05;
                stage.style.perspectiveOrigin = `${gyroX}% ${gyroY}%`;
                requestAnimationFrame(updateGyro);
            };
            updateGyro();
        } else {
            window.addEventListener('mousemove', (e) => {
                const x = (e.clientX / window.innerWidth) * 100;
                const y = (e.clientY / window.innerHeight) * 100;
                document.documentElement.style.setProperty('--mx', `${x}%`);
                document.documentElement.style.setProperty('--my', `${y}%`);

                if(!isShifting) {
                    let activeEngine = (activeDim === 'putra') ? bookPutra : bookPutri;
                    if(activeEngine.isIndexMode) return; 

                    const tiltX = (window.innerHeight / 2 - e.clientY) / 60; 
                    const tiltY = (e.clientX - window.innerWidth / 2) / 60; 
                    const baseY = activeDim === 'putra' ? 0 : -180; 

                    gsap.to(dimCore, { rotationX: tiltX, rotationY: baseY + tiltY, duration: 0.8, ease: "power2.out" });
                }
            });
        }

    });
</script>
<?= $this->endSection() ?>