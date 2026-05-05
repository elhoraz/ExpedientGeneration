<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Majlis Syura Eksklusif - VVIP Audio Room
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --glass-bg: rgba(5, 10, 8, 0.6);
        --neon-green: #00ff88;
        --text-muted: #8899a6;
    }

    body {
        background-color: #010201;
        background-image: 
            radial-gradient(circle at 50% 50%, rgba(212,175,55,0.05) 0%, transparent 70%),
            linear-gradient(180deg, #020403 0%, #010201 100%);
        overflow: hidden; /* Prevent scrolling for app-like feel */
        font-family: 'Inter', sans-serif;
    }

    .majlis-wrapper {
        position: relative;
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* BACKGROUND PARTICLES & WAVES */
    .bg-wave {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 600px;
        height: 600px;
        border-radius: 50%;
        border: 1px solid rgba(212,175,55,0.05);
        box-shadow: 0 0 100px rgba(212,175,55,0.02) inset;
        animation: pulseWave 8s infinite linear;
        pointer-events: none;
        z-index: 0;
    }
    .bg-wave:nth-child(2) { width: 900px; height: 900px; animation-duration: 12s; animation-direction: reverse; border: 1px dashed rgba(212,175,55,0.03); }
    .bg-wave:nth-child(3) { width: 1200px; height: 1200px; animation-duration: 15s; border: 1px solid rgba(212,175,55,0.02); }

    @keyframes pulseWave {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* HEADER & BACK BUTTON */
    .majlis-header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 30px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 50;
    }

    .btn-back {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.9rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        padding: 10px 20px;
        border-radius: 30px;
        border: 1px solid transparent;
        background: rgba(212,175,55,0.05);
        backdrop-filter: blur(10px);
    }
    .btn-back:hover {
        background: rgba(212,175,55,0.1);
        border-color: rgba(212,175,55,0.3);
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(212,175,55,0.1);
    }

    .room-info {
        text-align: right;
    }
    .room-title {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.5rem;
        margin: 0 0 5px 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }
    .room-status {
        color: var(--neon-green);
        font-size: 0.75rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        background-color: var(--neon-green);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--neon-green);
        animation: blink 2s infinite;
    }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

    /* CENTER STAGE (AUDIO VISUALIZER & SPEAKER) */
    .center-stage {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: -50px;
    }

    .speaker-orb {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop') center/cover;
        position: relative;
        box-shadow: 0 0 50px rgba(0,0,0,0.8), inset 0 0 20px rgba(0,0,0,0.5);
        border: 2px solid var(--gold-main);
        z-index: 5;
    }

    /* Golden Audio Rings around speaker */
    .audio-ring {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        border: 1px solid var(--gold-main);
        opacity: 0;
        pointer-events: none;
    }

    .speaker-info {
        margin-top: 30px;
        text-align: center;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(10px);
        padding: 15px 30px;
        border-radius: 20px;
        border: 1px solid rgba(212,175,55,0.2);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .speaker-name {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: 1px;
    }
    .speaker-role {
        color: var(--gold-main);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 5px 0 0 0;
    }

    /* LISTENERS GRID (ORBITING OR SCATTERED) */
    .listeners-container {
        position: absolute;
        bottom: 120px;
        width: 100%;
        max-width: 800px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
        padding: 0 20px;
        z-index: 10;
    }

    .listener-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: transform 0.3s;
        cursor: pointer;
    }
    .listener-node:hover {
        transform: translateY(-5px);
    }
    .listener-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #1a1a1a;
        background-size: cover;
        background-position: center;
        border: 1px solid rgba(255,255,255,0.1);
        filter: grayscale(80%);
        transition: all 0.3s;
    }
    .listener-node:hover .listener-avatar {
        filter: grayscale(0%);
        border-color: var(--gold-main);
        box-shadow: 0 0 15px rgba(212,175,55,0.3);
    }
    .listener-name {
        color: var(--text-muted);
        font-size: 0.7rem;
        letter-spacing: 1px;
    }
    .mic-status {
        position: absolute;
        bottom: 20px;
        right: 0;
        width: 18px;
        height: 18px;
        background: #111;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.5rem;
        color: #ff3366;
        border: 1px solid #ff3366;
    }

    /* CONTROL DOCK (BOTTOM) */
    .control-dock {
        position: absolute;
        bottom: 40px;
        display: flex;
        gap: 20px;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(20px);
        padding: 15px 30px;
        border-radius: 40px;
        border: 1px solid rgba(212,175,55,0.2);
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        z-index: 50;
    }

    .ctrl-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.05);
        color: #fff;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .ctrl-btn:hover {
        background: rgba(212,175,55,0.2);
        color: var(--gold-main);
        transform: scale(1.1);
    }
    .ctrl-btn.active {
        background: var(--gold-main);
        color: #000;
        box-shadow: 0 0 20px var(--gold-main);
    }
    .ctrl-btn.danger {
        color: #ff3366;
        background: rgba(255,51,102,0.1);
    }
    .ctrl-btn.danger:hover {
        background: #ff3366;
        color: #fff;
        box-shadow: 0 0 20px #ff3366;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .majlis-header { padding: 20px; flex-direction: column; gap: 15px; align-items: flex-start; }
        .room-info { text-align: left; }
        .room-status { justify-content: flex-start; }
        .speaker-orb { width: 140px; height: 140px; }
        .listeners-container { bottom: 130px; max-height: 250px; overflow-y: auto; align-items: flex-start; }
        .control-dock { bottom: 30px; padding: 10px 20px; gap: 15px; }
        .ctrl-btn { width: 45px; height: 45px; font-size: 1rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="majlis-wrapper">
    <!-- Background Waves -->
    <div class="bg-wave"></div>
    <div class="bg-wave"></div>
    <div class="bg-wave"></div>

    <!-- Header -->
    <header class="majlis-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> Kembali ke Vault
        </a>
        <div class="room-info">
            <h1 class="room-title">Majlis Syura Utama</h1>
            <div class="room-status">
                <span class="status-dot"></span> Diskusi Sedang Berlangsung
            </div>
        </div>
    </header>

    <!-- Center Stage (Active Speaker) -->
    <div class="center-stage">
        <div class="speaker-orb" id="activeSpeaker">
            <!-- JS will inject rippling rings here -->
        </div>
        <div class="speaker-info">
            <h2 class="speaker-name">Al-Ustadz Fulan Bin Fulan</h2>
            <p class="speaker-role">Pemateri Kajian Eksekutif</p>
        </div>
    </div>

    <!-- Listeners Grid -->
    <div class="listeners-container" id="listenersGrid">
        <!-- Rendered via JS -->
    </div>

    <!-- Bottom Controls -->
    <div class="control-dock">
        <button class="ctrl-btn" title="Angkat Tangan (Request Speak)" id="btnHand">
            <i class="fa-solid fa-hand"></i>
        </button>
        <button class="ctrl-btn danger" title="Mute Microphone" id="btnMic">
            <i class="fa-solid fa-microphone-slash"></i>
        </button>
        <button class="ctrl-btn" title="Bagikan File/Ayat" id="btnShare">
            <i class="fa-solid fa-book-quran"></i>
        </button>
        <button class="ctrl-btn danger" style="margin-left: 20px;" title="Keluar Majlis" onclick="window.location.href='/fitur'">
            <i class="fa-solid fa-phone-slash"></i>
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // 1. Initial Intro Animation
    const tl = gsap.timeline();
    tl.from(".majlis-header", { y: -50, opacity: 0, duration: 1, ease: "power3.out" })
      .from(".center-stage", { scale: 0.8, opacity: 0, duration: 1.5, ease: "elastic.out(1, 0.5)" }, "-=0.5")
      .from(".control-dock", { y: 100, opacity: 0, duration: 1, ease: "back.out(1.5)" }, "-=1");

    // 2. Audio Visualizer Effect (Golden Ripples)
    const speakerOrb = document.getElementById('activeSpeaker');
    
    function createRipple() {
        const ring = document.createElement('div');
        ring.classList.add('audio-ring');
        speakerOrb.appendChild(ring);

        const sizeStart = 180;
        const sizeEnd = 180 + (Math.random() * 150 + 50); // Random end size between 230 and 380

        gsap.fromTo(ring, 
            { width: sizeStart, height: sizeStart, opacity: 0.8 },
            { 
                width: sizeEnd, height: sizeEnd, opacity: 0, 
                duration: Math.random() * 1.5 + 1.5, 
                ease: "power2.out",
                onComplete: () => ring.remove()
            }
        );
    }

    // Trigger ripple every few milliseconds to simulate talking
    let talkingInterval = setInterval(createRipple, 600);

    // 3. Populate Listeners
    const listenersData = [
        { name: "Ahmad R.", img: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=150&auto=format&fit=crop" },
        { name: "Ibrahim", img: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=150&auto=format&fit=crop" },
        { name: "Yusuf K.", img: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=150&auto=format&fit=crop" },
        { name: "Tariq", img: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=150&auto=format&fit=crop" },
        { name: "Hasan", img: "https://images.unsplash.com/photo-1530268729831-4b0b9e170218?q=80&w=150&auto=format&fit=crop" },
        { name: "Ridwan", img: "https://images.unsplash.com/photo-1504257432389-52343af06ae3?q=80&w=150&auto=format&fit=crop" },
        { name: "Zaid M.", img: "https://images.unsplash.com/photo-1519345182560-3f2917c472ef?q=80&w=150&auto=format&fit=crop" }
    ];

    const grid = document.getElementById('listenersGrid');
    listenersData.forEach((user, index) => {
        const node = document.createElement('div');
        node.classList.add('listener-node');
        node.innerHTML = `
            <div style="position:relative;">
                <div class="listener-avatar" style="background-image: url('${user.img}');"></div>
                <div class="mic-status"><i class="fa-solid fa-microphone-slash"></i></div>
            </div>
            <span class="listener-name">${user.name}</span>
        `;
        grid.appendChild(node);
    });

    gsap.from(".listener-node", {
        y: 30, opacity: 0, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 1
    });

    // 4. Interactions
    document.getElementById('btnMic').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if(this.classList.contains('danger')) {
            this.classList.remove('danger');
            this.classList.add('active');
            icon.classList.replace('fa-microphone-slash', 'fa-microphone');
            this.style.color = "#000";
        } else {
            this.classList.add('danger');
            this.classList.remove('active');
            icon.classList.replace('fa-microphone', 'fa-microphone-slash');
            this.style.color = "#fff";
        }
    });

    document.getElementById('btnHand').addEventListener('click', function() {
        this.classList.toggle('active');
        if(this.classList.contains('active')) {
            gsap.to(this, { y: -10, yoyo: true, repeat: 3, duration: 0.2 });
        }
    });

});
</script>
<?= $this->endSection() ?>
