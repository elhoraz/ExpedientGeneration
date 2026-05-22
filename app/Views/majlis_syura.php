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
        overflow: hidden; 
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
        background-color: #1a1a1a;
        background-size: cover;
        background-position: center;
        position: relative;
        box-shadow: 0 0 50px rgba(0,0,0,0.8), inset 0 0 20px rgba(0,0,0,0.5);
        border: 2px solid var(--gold-main);
        z-index: 5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        font-size: 3rem;
    }

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

    .majlis-panel {
        position: fixed; top: 0; right: -450px; width: 450px; height: 100vh;
        background: rgba(10, 15, 12, 0.95); backdrop-filter: blur(20px);
        border-left: 1px solid rgba(212,175,55,0.3); z-index: 1000;
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 40px 30px; display: flex; flex-direction: column; gap: 20px;
        overflow-y: auto; color: #fff; box-shadow: -20px 0 50px rgba(0,0,0,0.8);
    }
    .majlis-panel.open { right: 0; }
    
    .requests-panel {
        position: fixed; top: 0; left: -450px; width: 450px; height: 100vh;
        background: rgba(10, 15, 12, 0.95); backdrop-filter: blur(20px);
        border-right: 1px solid rgba(212,175,55,0.3); z-index: 1000;
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 40px 30px; display: flex; flex-direction: column; gap: 20px;
        overflow-y: auto; color: #fff; box-shadow: 20px 0 50px rgba(0,0,0,0.8);
    }
    .requests-panel.open { left: 0; }

    .panel-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 1.5rem; letter-spacing: 2px; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 10px; margin-bottom: 10px; }
    .majlis-form input, .majlis-form textarea {
        width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(212,175,55,0.3);
        color: #fff; padding: 12px; margin-bottom: 15px; border-radius: 5px; font-family: 'Inter', sans-serif;
    }
    .btn-submit-majlis { width: 100%; background: var(--gold-main); color: #000; border: none; padding: 12px; font-weight: bold; cursor: pointer; border-radius: 5px; letter-spacing: 2px; text-transform: uppercase; }
    
    .topic-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; margin-bottom: 15px; }
    .topic-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 1.2rem; margin: 0 0 10px 0; }
    .topic-desc { font-size: 0.85rem; color: #ccc; line-height: 1.5; margin-bottom: 15px; }
    .topic-meta { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 15px; display: flex; justify-content: space-between; }
    
    .vote-btns { display: flex; gap: 10px; }
    .btn-vote { flex: 1; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-vote.agree { background: rgba(0,255,136,0.2); color: #00ff88; border: 1px solid #00ff88; }
    .btn-vote.agree:hover { background: rgba(0,255,136,0.4); }
    .btn-vote.disagree { background: rgba(255,51,102,0.2); color: #ff3366; border: 1px solid #ff3366; }
    .btn-vote.disagree:hover { background: rgba(255,51,102,0.4); }
    
    .vote-stats { display: flex; justify-content: space-between; margin-top: 15px; font-size: 0.8rem; font-weight: bold; }
    .vote-stats .agree-count { color: #00ff88; }
    .vote-stats .disagree-count { color: #ff3366; }

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
        position: relative;
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
    .badge-count {
        position: absolute;
        top: -5px; right: -5px;
        background: #ff3366; color: white;
        font-size: 0.6rem; font-weight: bold;
        width: 18px; height: 18px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        display: none;
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
        .majlis-panel { width: 100%; right: -100%; }
        .requests-panel { width: 100%; left: -100%; }
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
                <span class="status-dot"></span> <span id="onlineCount">1</span> Kolega Hadir
            </div>
        </div>
    </header>

    <!-- Center Stage (Active Speaker) -->
    <div class="center-stage">
        <div class="speaker-orb" id="activeSpeaker">
            <i class="fa-solid fa-microphone-slash"></i>
        </div>
        <div class="speaker-info">
            <h2 class="speaker-name" id="speakerName">Ruangan Terbuka</h2>
            <p class="speaker-role" id="speakerRole">Tidak ada pembicara saat ini</p>
        </div>
        <?php if($user_role === 'admin'): ?>
        <button id="btnStopSpeaker" onclick="stopSpeaker()" style="margin-top:15px; padding:5px 15px; background:rgba(255,51,102,0.2); border:1px solid #ff3366; color:#ff3366; border-radius:15px; cursor:pointer; font-size:0.7rem; font-weight:bold; letter-spacing:1px; text-transform:uppercase; display:none;">
            Hentikan Pembicara
        </button>
        <?php endif; ?>
    </div>

    <!-- Listeners Grid -->
    <div class="listeners-container" id="listenersGrid">
        <!-- Rendered via JS Pusher Presence -->
    </div>

    <!-- Bottom Controls -->
    <div class="control-dock">
        <?php if($user_role === 'admin'): ?>
        <button class="ctrl-btn" title="Daftar Permintaan Bicara" id="btnAdminRequests">
            <i class="fa-solid fa-clipboard-list"></i>
            <span class="badge-count" id="requestsBadge">0</span>
        </button>
        <?php endif; ?>
        <button class="ctrl-btn" title="Angkat Tangan (Request Speak)" id="btnHand" onclick="requestToSpeak()">
            <i class="fa-solid fa-hand"></i>
        </button>
        <button class="ctrl-btn danger" title="Mute Microphone" id="btnMic">
            <i class="fa-solid fa-microphone-slash"></i>
        </button>
        <button class="ctrl-btn" title="Mosi & Voting" id="btnToggleMajlis">
            <i class="fa-solid fa-gavel"></i>
        </button>
        <button class="ctrl-btn danger" style="margin-left: 20px;" title="Keluar Majlis" onclick="window.location.href='/fitur'">
            <i class="fa-solid fa-phone-slash"></i>
        </button>
    </div>
</div>

<!-- PANEL PERMINTAAN BICARA (ADMIN ONLY) -->
<?php if($user_role === 'admin'): ?>
<div class="requests-panel" id="requestsPanel">
    <div class="panel-title">Antrean Pembicara</div>
    <div id="requestsList">
        <div style="text-align:center; padding:20px; color:#555; font-size:0.8rem;">Belum ada permintaan.</div>
    </div>
</div>
<?php endif; ?>

<!-- PANEL MOSI & VOTING -->
<div class="majlis-panel" id="majlisPanel">
    <div class="panel-title">Ajukan Mosi Baru</div>
    <form id="formAddTopic" class="majlis-form">
        <input type="text" name="title" id="topicTitle" placeholder="Judul Mosi (Singkat & Jelas)" required>
        <textarea name="description" id="topicDesc" rows="3" placeholder="Deskripsi atau landasan masalah..." required></textarea>
        <button type="submit" class="btn-submit-majlis" id="btnSubmitTopic">Ajukan ke Forum</button>
    </form>

    <div class="panel-title" style="margin-top:20px;">Daftar Agenda (Voting)</div>
    <div id="topicsContainer">
        <!-- Rendered via JS -->
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script src="/vendor/pusher/pusher.min.js"></script>
<script>
// CURRENT USER INFO
const currentUserId = <?= esc($user_id) ?>;
const currentUserRole = "<?= esc($user_role) ?>";

document.addEventListener("DOMContentLoaded", () => {
    // 1. Initial Intro Animation
    const tl = gsap.timeline();
    tl.from(".majlis-header", { y: -50, opacity: 0, duration: 1, ease: "power3.out" })
      .from(".center-stage", { scale: 0.8, opacity: 0, duration: 1.5, ease: "elastic.out(1, 0.5)" }, "-=0.5")
      .from(".control-dock", { y: 100, opacity: 0, duration: 1, ease: "back.out(1.5)" }, "-=1");

    // Ripple effect for active speaker
    const speakerOrb = document.getElementById('activeSpeaker');
    let talkingInterval = null;
    function startRipples() {
        if(talkingInterval) return;
        talkingInterval = setInterval(() => {
            const ring = document.createElement('div');
            ring.classList.add('audio-ring');
            speakerOrb.appendChild(ring);
            const sizeStart = 180;
            const sizeEnd = 180 + (Math.random() * 150 + 50);
            gsap.fromTo(ring, 
                { width: sizeStart, height: sizeStart, opacity: 0.8 },
                { 
                    width: sizeEnd, height: sizeEnd, opacity: 0, 
                    duration: Math.random() * 1.5 + 1.5, 
                    ease: "power2.out",
                    onComplete: () => ring.remove()
                }
            );
        }, 600);
    }
    function stopRipples() {
        if(talkingInterval) clearInterval(talkingInterval);
        talkingInterval = null;
        document.querySelectorAll('.audio-ring').forEach(r => r.remove());
    }

    // Interactions
    const btnMic = document.getElementById('btnMic');
    btnMic.addEventListener('click', function() {
        const icon = this.querySelector('i');
        if(this.classList.contains('danger')) {
            this.classList.remove('danger');
            this.classList.add('active');
            icon.classList.replace('fa-microphone-slash', 'fa-microphone');
            this.style.color = "#000";
            if(window.currentActiveSpeakerId == currentUserId) startRipples();
        } else {
            this.classList.add('danger');
            this.classList.remove('active');
            icon.classList.replace('fa-microphone', 'fa-microphone-slash');
            this.style.color = "#fff";
            stopRipples();
        }
    });

    const btnToggleMajlis = document.getElementById('btnToggleMajlis');
    const majlisPanel = document.getElementById('majlisPanel');
    btnToggleMajlis.addEventListener('click', () => {
        majlisPanel.classList.toggle('open');
        if(document.getElementById('requestsPanel')) document.getElementById('requestsPanel').classList.remove('open');
        if(navigator.vibrate) navigator.vibrate(20);
    });

    const btnAdminRequests = document.getElementById('btnAdminRequests');
    if (btnAdminRequests) {
        btnAdminRequests.addEventListener('click', () => {
            document.getElementById('requestsPanel').classList.toggle('open');
            majlisPanel.classList.remove('open');
        });
    }

    // --- PUSHER & REAL-TIME LOGIC ---
    let presenceChannel;
    let onlineMembers = new Map();
    const listenersGrid = document.getElementById('listenersGrid');

    if (window.ExpedientConfig && window.ExpedientConfig.userId !== null) {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfHash = csrfMeta ? csrfMeta.getAttribute('content') : '';

        const config = window.ExpedientConfig.pusher;
        const pusherConfig = { 
            cluster: config.cluster, 
            forceTLS: true,
            authEndpoint: '/pusher/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfHash
                }
            }
        };
        
        if (config.host) {
            pusherConfig.wsHost = config.host;
            pusherConfig.wsPort = config.port;
            pusherConfig.wssPort = config.port;
            pusherConfig.forceTLS = config.forceTLS;
            pusherConfig.enabledTransports = ['ws', 'wss'];
        }
        
        const pusher = new Pusher(config.key, pusherConfig);
        presenceChannel = pusher.subscribe('presence-majlis');

        presenceChannel.bind('pusher:subscription_succeeded', (members) => {
            document.getElementById('onlineCount').innerText = members.count;
            members.each(member => onlineMembers.set(member.id, member.info));
            renderListeners();
        });

        presenceChannel.bind('pusher:member_added', (member) => {
            onlineMembers.set(member.id, member.info);
            document.getElementById('onlineCount').innerText = onlineMembers.size;
            renderListeners();
        });

        presenceChannel.bind('pusher:member_removed', (member) => {
            onlineMembers.delete(member.id);
            document.getElementById('onlineCount').innerText = onlineMembers.size;
            renderListeners();
        });

        // App events
        presenceChannel.bind('majlis-update', () => {
            fetchState(); // re-fetch topics
        });

        presenceChannel.bind('hand-raised', (data) => {
            renderRequests(data.requests);
            if(data.requests.length > 0 && navigator.vibrate) navigator.vibrate([50, 50, 50]);
        });

        presenceChannel.bind('speaker-changed', (data) => {
            updateActiveSpeakerUI(data.speaker);
            if(data.requests) renderRequests(data.requests);
        });
    }

    function renderListeners() {
        listenersGrid.innerHTML = '';
        onlineMembers.forEach((info, id) => {
            // Don't render active speaker in the grid
            if (window.currentActiveSpeakerId == id) return;

            const node = document.createElement('div');
            node.classList.add('listener-node');
            node.innerHTML = `
                <div style="position:relative;">
                    <div class="listener-avatar" style="background-image: url('${info.avatar}');"></div>
                    <div class="mic-status"><i class="fa-solid fa-microphone-slash"></i></div>
                </div>
                <span class="listener-name">${info.name}</span>
            `;
            listenersGrid.appendChild(node);
        });
    }

    function updateActiveSpeakerUI(speaker) {
        if (speaker) {
            window.currentActiveSpeakerId = speaker.user_id;
            document.getElementById('speakerName').innerText = speaker.name;
            document.getElementById('speakerRole').innerText = speaker.role === 'admin' ? 'Pimpinan Sidang' : 'Peserta Majlis';
            document.getElementById('activeSpeaker').style.backgroundImage = `url('${speaker.avatar}')`;
            document.getElementById('activeSpeaker').innerHTML = ''; // remove mic slash
            startRipples();
            
            if(currentUserRole === 'admin') {
                document.getElementById('btnStopSpeaker').style.display = 'inline-block';
            }
        } else {
            window.currentActiveSpeakerId = null;
            document.getElementById('speakerName').innerText = "Ruangan Terbuka";
            document.getElementById('speakerRole').innerText = "Tidak ada pembicara saat ini";
            document.getElementById('activeSpeaker').style.backgroundImage = 'none';
            document.getElementById('activeSpeaker').innerHTML = '<i class="fa-solid fa-microphone-slash"></i>';
            stopRipples();
            if(document.getElementById('btnStopSpeaker')) {
                document.getElementById('btnStopSpeaker').style.display = 'none';
            }
        }
        renderListeners(); // to remove/add from grid
    }

    window.requestToSpeak = async function() {
        const btn = document.getElementById('btnHand');
        btn.classList.add('active');
        gsap.to(btn, { y: -10, yoyo: true, repeat: 3, duration: 0.2 });

        try {
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', getCsrfToken());
            const res = await fetch('/majlis/raise_hand', { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
            const data = await res.json();
            if(data.status === 'success') {
                showAegisToast("Informasi", data.message);
            }
        } catch(e) {}
    }

    window.approveSpeaker = async function(userId) {
        try {
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('<?= csrf_token() ?>', getCsrfToken());
            const res = await fetch('/majlis/approve_speaker', { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
            const data = await res.json();
        } catch(e) {}
    }

    window.stopSpeaker = async function() {
        try {
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', getCsrfToken());
            const res = await fetch('/majlis/stop_speaker', { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
            const data = await res.json();
        } catch(e) {}
    }

    function renderRequests(requests) {
        const badge = document.getElementById('requestsBadge');
        const list = document.getElementById('requestsList');
        if(!badge || !list) return;

        if (requests && requests.length > 0) {
            badge.style.display = 'flex';
            badge.innerText = requests.length;
            list.innerHTML = requests.map(r => `
                <div class="topic-card" style="display:flex; align-items:center; justify-content:space-between; padding:10px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="${r.avatar}" style="width:40px; height:40px; border-radius:50%;">
                        <div>
                            <div style="font-weight:bold; font-size:0.9rem;">${r.name}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted);">${r.role}</div>
                        </div>
                    </div>
                    <button class="btn-vote agree" style="flex:0; padding:5px 15px;" onclick="approveSpeaker(${r.user_id})">Verifikasi</button>
                </div>
            `).join('');
        } else {
            badge.style.display = 'none';
            list.innerHTML = '<div style="text-align:center; padding:20px; color:#555; font-size:0.8rem;">Belum ada permintaan.</div>';
        }
    }

    // --- FORMS & TOPICS LOGIC ---
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    async function fetchState() {
        try {
            const res = await fetch('/majlis/state');
            const data = await res.json();
            if (data.status === 'success') {
                renderTopics(data.topics);
                updateActiveSpeakerUI(data.active_speaker);
                if(currentUserRole === 'admin') renderRequests(data.requests);
            }
        } catch(e) {}
    }

    function renderTopics(topics) {
        const container = document.getElementById('topicsContainer');
        if (topics.length === 0) {
            container.innerHTML = '<div style="text-align:center; padding:20px; color:#555; font-size:0.8rem;">Belum ada Mosi yang diajukan.</div>';
            return;
        }

        container.innerHTML = topics.map(t => {
            let html = `
                <div class="topic-card">
                    <h3 class="topic-title">${t.title}</h3>
                    <div class="topic-meta">
                        <span>Oleh: ${t.nama_panggilan}</span>
                        <span style="color: ${t.status === 'Open' ? 'var(--neon-green)' : '#ff3366'}">${t.status}</span>
                    </div>
                    <div class="topic-desc">${t.description}</div>
                    
                    <div class="vote-stats">
                        <span class="agree-count"><i class="fa-solid fa-check"></i> Setuju: ${t.votes_setuju}</span>
                        <span class="disagree-count"><i class="fa-solid fa-xmark"></i> Tidak: ${t.votes_tidak_setuju}</span>
                    </div>
            `;

            if (t.status === 'Open') {
                if (t.has_voted) {
                    html += `<div style="margin-top:15px; text-align:center; color:var(--gold-main); font-size:0.8rem; font-weight:bold; border:1px dashed var(--gold-main); padding:8px;">Suara Anda telah direkam.</div>`;
                } else {
                    html += `
                        <div class="vote-btns" style="margin-top:15px;">
                            <button type="button" class="btn-vote agree" onclick="submitVote(${t.id}, 'Setuju')"><i class="fa-solid fa-check"></i> Setuju</button>
                            <button type="button" class="btn-vote disagree" onclick="submitVote(${t.id}, 'Tidak Setuju')"><i class="fa-solid fa-xmark"></i> Tolak</button>
                        </div>
                    `;
                }
                
                if(t.created_by == currentUserId || currentUserRole === 'admin') {
                    html += `
                        <button type="button" onclick="closeTopic(${t.id})" style="width:100%; padding:8px; background:rgba(255,51,102,0.15); border:1px solid rgba(255,51,102,0.3); color:#ff3366; border-radius:5px; cursor:pointer; font-size:0.75rem; font-weight:bold; letter-spacing:1px; text-transform:uppercase; margin-top:10px;">
                            <i class="fa-solid fa-lock"></i> Tutup Voting
                        </button>
                    `;
                }
            } else {
                const winner = parseInt(t.votes_setuju) >= parseInt(t.votes_tidak_setuju) ? 'DISETUJUI' : 'DITOLAK';
                const winColor = parseInt(t.votes_setuju) >= parseInt(t.votes_tidak_setuju) ? '#00ff88' : '#ff3366';
                html += `
                    <div style="margin-top:15px; text-align:center; padding:12px; border-radius:8px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08);">
                        <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:2px; margin-bottom:8px;">Hasil Akhir</div>
                        <div style="display:flex; justify-content:center; gap:30px;">
                            <span style="color:#00ff88; font-size:1.2rem; font-weight:bold;"><i class="fa-solid fa-check"></i> ${t.votes_setuju}</span>
                            <span style="color:#ff3366; font-size:1.2rem; font-weight:bold;"><i class="fa-solid fa-xmark"></i> ${t.votes_tidak_setuju}</span>
                        </div>
                        <div style="margin-top:8px; font-size:0.75rem; font-weight:bold; color:${winColor}; letter-spacing:2px;">${winner}</div>
                    </div>
                `;
            }

            html += `</div>`;
            return html;
        }).join('');
    }

    document.getElementById('formAddTopic').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitTopic');
        btn.disabled = true;
        btn.innerText = "Mengajukan...";

        const formData = new FormData(this);
        formData.append('<?= csrf_token() ?>', getCsrfToken());

        try {
            const res = await fetch('/majlis/store', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (data.status === 'success') {
                this.reset();
                showAegisToast("Mosi Berhasil", data.message);
                fetchState(); // Re-fetch immediately
            } else {
                showAegisToast("Error", data.message);
            }
        } catch (err) {
            console.error(err);
        }
        
        btn.disabled = false;
        btn.innerText = "Ajukan ke Forum";
    });

    window.submitVote = async function(topicId, choice) {
        const formData = new FormData();
        formData.append('choice', choice);
        formData.append('<?= csrf_token() ?>', getCsrfToken());

        try {
            const res = await fetch('/majlis/vote/' + topicId, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (data.status === 'success') {
                showAegisToast("Suara Direkam", data.message);
                fetchState();
            } else {
                showAegisToast("Gagal", data.message);
            }
        } catch(e) {}
    }

    window.closeTopic = async function(topicId) {
        if(!confirm('Tutup sesi voting ini? Tindakan tidak dapat dibatalkan.')) return;
        
        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', getCsrfToken());

        try {
            const res = await fetch('/majlis/close/' + topicId, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (data.status === 'success') {
                showAegisToast("Sesi Ditutup", data.message);
                fetchState();
            }
        } catch(e) {}
    }

    function showAegisToast(title, message) {
        const aegisToast = document.getElementById('aegisToast');
        const radarName = document.getElementById('radarName');
        if(aegisToast && radarName) {
            document.querySelector('.aegis-title').innerText = title;
            radarName.innerText = message; 
            aegisToast.classList.add('show');
            if (navigator.vibrate) navigator.vibrate([50, 50]);
            setTimeout(() => aegisToast.classList.remove('show'), 6000);
        } else {
            alert(title + ": " + message);
        }
    }

    // Initial Fetch
    fetchState();
});
</script>
<?= $this->endSection() ?>
