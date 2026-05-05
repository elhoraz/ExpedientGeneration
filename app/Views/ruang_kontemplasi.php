<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Ruang Kontemplasi - The Sanctuary
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --bg-deep: #010201;
    }

    /* FULLSCREEN ZEN MODE OVERRIDES */
    body {
        background-color: var(--bg-deep);
        margin: 0; padding: 0;
        overflow: hidden;
        font-family: 'Playfair Display', serif;
        color: #fff;
    }
    
    /* Hide the global navigation if it exists in the template */
    .navbar, footer, .sidebar { display: none !important; }

    .sanctuary-wrapper {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        background: radial-gradient(circle at center, rgba(212,175,55,0.05) 0%, var(--bg-deep) 60%);
    }

    /* PARTICLES */
    .dust-container {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 1;
    }
    .dust {
        position: absolute;
        background: var(--gold-light);
        border-radius: 50%;
        opacity: 0;
        box-shadow: 0 0 10px var(--gold-light);
    }

    /* BREATHING ORB */
    .orb-container {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .breathing-orb {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212,175,55,0.8) 0%, rgba(170,119,28,0.2) 70%, transparent 100%);
        box-shadow: 0 0 50px rgba(212,175,55,0.4), inset 0 0 20px rgba(212,175,55,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Breathing States */
    .orb-inhale {
        transform: scale(2.5);
        box-shadow: 0 0 150px rgba(212,175,55,0.8), inset 0 0 50px rgba(212,175,55,1);
    }
    .orb-hold {
        transform: scale(2.5);
        box-shadow: 0 0 100px rgba(212,175,55,0.6), inset 0 0 50px rgba(212,175,55,0.8);
    }
    .orb-exhale {
        transform: scale(1);
        box-shadow: 0 0 50px rgba(212,175,55,0.4), inset 0 0 20px rgba(212,175,55,0.8);
    }

    .guide-text {
        position: absolute;
        color: #fff;
        font-size: 1rem;
        letter-spacing: 5px;
        text-transform: uppercase;
        opacity: 0.8;
        text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        transition: opacity 1s;
    }

    /* START OVERLAY */
    .start-overlay {
        position: absolute;
        inset: 0;
        background: var(--bg-deep);
        z-index: 50;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: opacity 2s;
    }
    .start-title {
        font-size: 2.5rem;
        color: var(--gold-main);
        margin-bottom: 20px;
        letter-spacing: 5px;
    }
    .start-desc {
        color: #8899a6;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        max-width: 400px;
        text-align: center;
        line-height: 1.6;
        margin-bottom: 40px;
    }
    .btn-start {
        background: transparent;
        color: var(--gold-main);
        border: 1px solid var(--gold-main);
        padding: 15px 40px;
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.5s;
    }
    .btn-start:hover {
        background: rgba(212,175,55,0.1);
        box-shadow: 0 0 20px rgba(212,175,55,0.3);
    }

    /* CONTROLS (Faint) */
    .stealth-controls {
        position: absolute;
        bottom: 30px;
        display: flex;
        gap: 30px;
        opacity: 0.2;
        transition: opacity 0.5s;
        z-index: 20;
    }
    .stealth-controls:hover { opacity: 1; }
    
    .stealth-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: color 0.3s;
    }
    .stealth-btn:hover { color: var(--gold-main); }
    .stealth-btn span {
        font-size: 0.6rem;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="sanctuary-wrapper">
    
    <!-- Dust Particles -->
    <div class="dust-container" id="dustContainer"></div>

    <!-- Intro Overlay -->
    <div class="start-overlay" id="startOverlay">
        <h1 class="start-title">Ruang Kontemplasi</h1>
        <p class="start-desc">Tinggalkan sejenak urusan duniawi. Posisikan diri Anda dengan nyaman, aktifkan suara, dan ikuti ritme keheningan.</p>
        <button class="btn-start" onclick="beginSanctuary()">Mulai Keheningan</button>
    </div>

    <!-- Breathing Orb -->
    <div class="orb-container">
        <div class="breathing-orb" id="orb">
            <div class="guide-text" id="guideText"></div>
        </div>
    </div>

    <!-- Stealth Controls -->
    <div class="stealth-controls">
        <a href="/fitur" class="stealth-btn">
            <i class="fa-solid fa-person-walking-arrow-loop-left"></i>
            <span>Kembali</span>
        </a>
        <button class="stealth-btn" id="btnAudio" onclick="toggleAudio()">
            <i class="fa-solid fa-volume-xmark"></i>
            <span>Audio Mati</span>
        </button>
    </div>

    <!-- Ambient Audio (Mockup using HTML5 Audio) -->
    <!-- In real production, use a valid mp3/wav link -->
    <audio id="ambientAudio" loop>
        <source src="https://cdn.pixabay.com/download/audio/2022/02/07/audio_677eb93158.mp3?filename=desert-wind-and-distant-thunder-1234.mp3" type="audio/mpeg">
    </audio>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
let isBreathing = false;
let audioEnabled = false;
const audio = document.getElementById('ambientAudio');

// Generate Dust
const dustContainer = document.getElementById('dustContainer');
for(let i=0; i<30; i++) {
    let dust = document.createElement('div');
    dust.classList.add('dust');
    let size = Math.random() * 3 + 1;
    dust.style.width = size + 'px';
    dust.style.height = size + 'px';
    dust.style.left = Math.random() * 100 + 'vw';
    dust.style.top = Math.random() * 100 + 'vh';
    dustContainer.appendChild(dust);

    animateDust(dust);
}

function animateDust(dust) {
    gsap.to(dust, {
        y: "-=100",
        x: "+=" + (Math.random() * 100 - 50),
        opacity: Math.random() * 0.5 + 0.1,
        duration: Math.random() * 10 + 10,
        ease: "sine.inOut",
        yoyo: true,
        repeat: -1
    });
}

function beginSanctuary() {
    const overlay = document.getElementById('startOverlay');
    overlay.style.opacity = '0';
    setTimeout(() => {
        overlay.style.display = 'none';
        isBreathing = true;
        breathCycle();
        
        // Auto play audio if allowed by browser, else user must click
        try {
            audio.volume = 0.4;
            audio.play();
            toggleAudioUI(true);
        } catch(e) {
            console.log("Auto-play blocked.");
        }
        
    }, 2000);
}

function breathCycle() {
    if(!isBreathing) return;
    
    const orb = document.getElementById('orb');
    const text = document.getElementById('guideText');

    // Inhale (4s)
    text.style.opacity = 0;
    setTimeout(() => { text.innerText = "Tarik Napas"; text.style.opacity = 1; }, 500);
    orb.className = 'breathing-orb orb-inhale';

    // Hold (7s)
    setTimeout(() => {
        text.style.opacity = 0;
        setTimeout(() => { text.innerText = "Tahan"; text.style.opacity = 1; }, 500);
        orb.className = 'breathing-orb orb-hold';
        orb.style.transitionDuration = '7s';
    }, 4000);

    // Exhale (8s)
    setTimeout(() => {
        text.style.opacity = 0;
        setTimeout(() => { text.innerText = "Hembuskan"; text.style.opacity = 1; }, 500);
        orb.className = 'breathing-orb orb-exhale';
        orb.style.transitionDuration = '8s';
        
        // Restart cycle
        setTimeout(breathCycle, 8000);
    }, 11000);
}

function toggleAudio() {
    if(audio.paused) {
        audio.play();
        toggleAudioUI(true);
    } else {
        audio.pause();
        toggleAudioUI(false);
    }
}

function toggleAudioUI(playing) {
    const btn = document.getElementById('btnAudio');
    if(playing) {
        btn.innerHTML = '<i class="fa-solid fa-volume-high"></i><span>Audio Aktif</span>';
        btn.style.color = 'var(--gold-main)';
    } else {
        btn.innerHTML = '<i class="fa-solid fa-volume-xmark"></i><span>Audio Mati</span>';
        btn.style.color = '#fff';
    }
}
</script>
<?= $this->endSection() ?>
