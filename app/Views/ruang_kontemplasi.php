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
        --aura-color: rgba(212, 175, 55, 0.05); /* Default Gold Aura */
    }

    /* FULLSCREEN ZEN MODE OVERRIDES */
    body {
        background-color: var(--bg-deep);
        margin: 0; padding: 0;
        overflow: hidden;
        font-family: 'Playfair Display', serif;
        color: #fff;
    }
    
    /* Hide the global navigation */
    .navbar, footer, .sidebar, .menu-toggle, .theme-widget, .notif-widget, .chat-widget { display: none !important; }

    .sanctuary-wrapper {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        background: radial-gradient(circle at center, var(--aura-color) 0%, var(--bg-deep) 70%);
        transition: background 3s ease-in-out;
    }

    /* TOP LEFT BACK BUTTON */
    .btn-top-back {
        position: absolute;
        top: 40px;
        left: 20px;
        background: rgba(212, 175, 55, 0.1);
        color: var(--gold-main);
        border: 1px solid rgba(212, 175, 55, 0.3);
        padding: 10px 20px;
        border-radius: 30px;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-decoration: none;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 100;
    }
    .btn-top-back:hover {
        background: var(--gold-main);
        color: #000;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
    }

    /* PARTICLES & WISDOM */
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
    
    .floating-wisdom {
        position: absolute;
        color: rgba(255,255,255,0.4);
        font-family: 'Inter', sans-serif;
        font-style: italic;
        font-size: clamp(1rem, 3vw, 1.5rem);
        text-align: center;
        width: 80%;
        max-width: 600px;
        left: 50%;
        top: 25%;
        transform: translate(-50%, -50%);
        opacity: 0;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(255,255,255,0.2);
        z-index: 2;
        line-height: 1.5;
    }

    /* HYPNOTIC MANDALA ORB */
    .orb-container {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 300px;
        height: 300px;
        margin-top: -50px;
    }

    .mandala-ring {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(212,175,55,0.3);
        box-shadow: inset 0 0 20px rgba(212,175,55,0.1), 0 0 20px rgba(212,175,55,0.1);
        transition: transform 4s cubic-bezier(0.4, 0, 0.2, 1), opacity 4s ease, border-color 2s, box-shadow 2s;
    }
    
    .ring-1 { width: 100px; height: 100px; border-width: 2px; }
    .ring-2 { width: 140px; height: 140px; border-style: dashed; animation: spin 30s linear infinite; }
    .ring-3 { width: 180px; height: 180px; opacity: 0.5; animation: spin-reverse 40s linear infinite; }
    .ring-4 { width: 220px; height: 220px; border: 1px dotted rgba(212,175,55,0.5); opacity: 0.3; animation: spin 50s linear infinite; }
    
    .breathing-core {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212,175,55,0.9) 0%, rgba(170,119,28,0.4) 70%, transparent 100%);
        box-shadow: 0 0 50px rgba(212,175,55,0.6), inset 0 0 20px rgba(212,175,55,0.8);
        transition: transform 4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 5;
    }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes spin-reverse { 100% { transform: rotate(-360deg); } }

    /* Breathing States */
    .orb-inhale .breathing-core { transform: scale(2.5); box-shadow: 0 0 100px rgba(212,175,55,0.8); }
    .orb-inhale .ring-1 { transform: scale(2.5); opacity: 1; border-color: rgba(212,175,55,0.6); }
    .orb-inhale .ring-2 { transform: scale(2.2); opacity: 0.8; }
    .orb-inhale .ring-3 { transform: scale(1.8); opacity: 0.6; }
    .orb-inhale .ring-4 { transform: scale(1.5); opacity: 0.4; }

    .orb-hold .breathing-core { transform: scale(2.5); box-shadow: 0 0 80px rgba(212,175,55,0.5); }
    .orb-hold .ring-1 { transform: scale(2.5); opacity: 0.8; }
    .orb-hold .ring-2 { transform: scale(2.2); opacity: 0.6; }
    
    .orb-exhale .breathing-core { transform: scale(1); box-shadow: 0 0 40px rgba(212,175,55,0.3); }
    .orb-exhale .ring-1 { transform: scale(1); opacity: 0.3; }
    .orb-exhale .ring-2 { transform: scale(1); opacity: 0.2; }
    .orb-exhale .ring-3 { transform: scale(1); opacity: 0.1; }
    .orb-exhale .ring-4 { transform: scale(1); opacity: 0.05; }

    .guide-text {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 0.9rem;
        letter-spacing: 6px;
        text-transform: uppercase;
        opacity: 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        transition: opacity 1s;
        z-index: 10;
        pointer-events: none;
    }

    /* START OVERLAY */
    .start-overlay {
        position: absolute;
        inset: 0;
        background: var(--bg-deep);
        z-index: 150;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: opacity 2s, backdrop-filter 2s;
    }
    .start-title {
        font-size: clamp(2rem, 6vw, 3.5rem);
        color: var(--gold-main);
        margin-bottom: 20px;
        letter-spacing: 6px;
        text-transform: uppercase;
        text-align: center;
    }
    .start-desc {
        color: #8899a6;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        max-width: 500px;
        text-align: center;
        line-height: 1.8;
        margin-bottom: 50px;
        padding: 0 20px;
        letter-spacing: 1px;
    }
    .btn-start {
        background: transparent;
        color: var(--gold-main);
        border: 1px solid var(--gold-main);
        padding: 15px 50px;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        letter-spacing: 5px;
        text-transform: uppercase;
        border-radius: 40px;
        cursor: pointer;
        transition: all 0.5s;
        box-shadow: 0 0 15px rgba(212,175,55,0.1);
    }
    .btn-start:hover {
        background: rgba(212,175,55,0.15);
        box-shadow: 0 0 30px rgba(212,175,55,0.4);
        transform: translateY(-2px);
    }

    /* STEALTH CONTROLS */
    .stealth-controls {
        position: absolute;
        bottom: 50px;
        display: flex;
        gap: 50px;
        opacity: 0.5;
        transition: opacity 0.5s;
        z-index: 20;
    }
    .stealth-controls:hover { opacity: 1; }
    
    .stealth-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: color 0.3s, transform 0.3s;
    }
    .stealth-btn:hover { color: var(--gold-main); transform: translateY(-3px); }
    .stealth-btn span {
        font-size: 0.7rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* AUDIO MENU */
    .audio-menu {
        position: absolute;
        bottom: 120px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: rgba(10, 10, 10, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(212,175,55,0.3);
        padding: 20px;
        border-radius: 20px;
        display: flex;
        gap: 15px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 25;
        box-shadow: 0 10px 40px rgba(0,0,0,0.8);
    }
    .audio-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }
    .audio-track {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.1);
        color: #ccc;
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 0.8rem;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: 0.3s;
        white-space: nowrap;
    }
    .audio-track:hover, .audio-track.active {
        background: rgba(212,175,55,0.2);
        color: var(--gold-main);
        border-color: var(--gold-main);
        box-shadow: 0 0 15px rgba(212,175,55,0.3);
    }

    /* JOURNAL PANEL (Ultra Glassmorphism) */
    .journal-panel {
        position: fixed; top: 0; right: -500px; width: 450px; height: 100vh;
        background: rgba(5, 5, 5, 0.75); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px);
        border-left: 1px solid rgba(212,175,55,0.2); z-index: 1000;
        transition: right 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 50px 40px; display: flex; flex-direction: column; gap: 25px;
        overflow-y: auto; color: #fff; box-shadow: -20px 0 50px rgba(0,0,0,0.9);
        font-family: 'Inter', sans-serif;
    }
    .journal-panel.open { right: 0; }
    
    /* Scrollbar minimalis */
    .journal-panel::-webkit-scrollbar { width: 6px; }
    .journal-panel::-webkit-scrollbar-thumb { background: rgba(212,175,55,0.4); border-radius: 3px; }

    .panel-title { 
        font-family: 'Playfair Display', serif; 
        color: var(--gold-main); 
        font-size: 1.8rem; 
        letter-spacing: 2px; 
        margin-bottom: 5px; 
    }
    .panel-subtitle {
        font-size: 0.8rem;
        color: #888;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .journal-form { position: relative; }
    .journal-form select, .journal-form textarea {
        width: 100%; 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff; 
        padding: 15px; 
        margin-bottom: 20px; 
        border-radius: 12px; 
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        transition: border-color 0.3s, background 0.3s;
    }
    .journal-form textarea { resize: none; min-height: 120px; }
    .journal-form select:focus, .journal-form textarea:focus {
        border-color: var(--gold-main);
        outline: none;
        background: rgba(255,255,255,0.05);
    }
    
    .btn-submit-journal { 
        width: 100%; 
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark)); 
        color: #000; 
        border: none; 
        padding: 15px; 
        font-weight: 700; 
        cursor: pointer; 
        border-radius: 12px; 
        letter-spacing: 3px; 
        text-transform: uppercase; 
        box-shadow: 0 5px 15px rgba(212,175,55,0.3);
        transition: 0.3s;
    }
    .btn-submit-journal:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212,175,55,0.5); }
    
    .journal-card { 
        background: linear-gradient(145deg, rgba(255,255,255,0.05), rgba(255,255,255,0.01)); 
        border: 1px solid rgba(255,255,255,0.08); 
        padding: 25px; 
        border-radius: 15px; 
        margin-bottom: 20px; 
        position: relative; 
        transition: transform 0.3s;
    }
    .journal-card:hover { transform: translateX(-5px); border-color: rgba(212,175,55,0.3); }

    .journal-date { font-size: 0.75rem; color: #888; margin-bottom: 12px; letter-spacing: 1px; display:flex; justify-content:space-between; align-items:center;}
    .journal-content { font-size: 0.95rem; line-height: 1.7; color: #eee; font-style: italic; }
    
    .mood-tag {
        font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px;
        padding: 4px 10px; border-radius: 20px; font-weight: bold;
    }
    
    /* Mood Colors */
    .mood-Netral { background: rgba(255,255,255,0.1); color: #ccc; }
    .mood-Damai { background: rgba(0,255,255,0.1); color: #00ffff; }
    .mood-Gelisah { background: rgba(255,100,100,0.1); color: #ff6666; }
    .mood-Bersyukur { background: rgba(212,175,55,0.2); color: var(--gold-main); }
    .mood-Terbebani { background: rgba(150,100,255,0.15); color: #b388ff; }
    
    /* Close Button for Mobile Panel */
    .btn-close-panel {
        position: absolute;
        top: 20px;
        right: 20px;
        background: transparent;
        border: none;
        color: #888;
        font-size: 1.5rem;
        cursor: pointer;
        display: none;
    }

    @media (max-width: 768px) {
        .journal-panel { width: 100%; right: -100%; padding: 30px 20px; z-index: 1050; }
        .journal-panel.open { right: 0; }
        .btn-close-panel { display: block; }
        
        .stealth-controls { bottom: 30px; gap: 30px; opacity: 1; }
        .audio-menu { width: 90%; flex-wrap: wrap; justify-content: center; bottom: 100px; padding: 15px; }
        .audio-track { font-size: 0.75rem; padding: 8px 15px; }
        .orb-container { transform: scale(0.75); margin-top: -80px; }
        
        .floating-wisdom { top: 15%; width: 90%; font-size: 1.1rem; }
        
        .btn-top-back { top: 20px; left: 15px; font-size: 0.75rem; padding: 8px 15px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="sanctuary-wrapper" id="sanctuaryBg">
    
    <!-- Top Left Global Back Button -->
    <a href="/fitur" class="btn-top-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <!-- Dust & Cosmic Particles -->
    <div class="dust-container" id="dustContainer"></div>
    <div class="floating-wisdom" id="wisdomText"></div>

    <!-- Intro Overlay -->
    <div class="start-overlay" id="startOverlay">
        <h1 class="start-title"><?= cms_text('kontemplasi_title', 'Ruang Kontemplasi') ?></h1>
        <p class="start-desc"><?= cms_text('kontemplasi_desc', 'Tinggalkan sejenak urusan duniawi. Posisikan diri Anda dengan nyaman, aktifkan suara, dan ikuti ritme keheningan.') ?></p>
        <button class="btn-start" onclick="beginSanctuary()"><?= cms_text('kontemplasi_btn_start', 'Mulai Keheningan') ?></button>
    </div>

    <!-- Hypnotic Mandala Orb -->
    <div class="orb-container" id="orb">
        <div class="mandala-ring ring-4"></div>
        <div class="mandala-ring ring-3"></div>
        <div class="mandala-ring ring-2"></div>
        <div class="mandala-ring ring-1"></div>
        <div class="breathing-core"></div>
        <div class="guide-text" id="guideText"></div>
    </div>

    <!-- Audio Selection Menu -->
    <div class="audio-menu" id="audioMenu">
        <button class="audio-track active" onclick="switchTrack('rain', this)">🌧️ Hujan Deras</button>
        <button class="audio-track" onclick="switchTrack('ocean', this)">🌊 Ombak Samudra</button>
        <button class="audio-track" onclick="switchTrack('space', this)">🌌 Ruang Angkasa</button>
        <button class="audio-track" onclick="switchTrack('zen', this)">🍃 Angin Gurun</button>
        <button class="audio-track" onclick="switchTrack('quran', this)">📖 Ar-Rahman</button>
        <button class="audio-track" style="border-color: #ff4444; color: #ff4444;" onclick="switchTrack('none', this)">🔇 Matikan</button>
    </div>

    <!-- Stealth Controls -->
    <div class="stealth-controls">
        <button class="stealth-btn" id="btnAudio" onclick="toggleAudioMenu()">
            <i class="fa-solid fa-music"></i>
            <span><?= cms_text('kontemplasi_btn_audio', 'Atur Suara') ?></span>
        </button>
        <button class="stealth-btn" id="btnToggleJournal">
            <i class="fa-solid fa-feather-pointed"></i>
            <span><?= cms_text('kontemplasi_btn_journal', 'Buka Jurnal') ?></span>
        </button>
    </div>

    <!-- Premium Journal Panel -->
    <div class="journal-panel" id="journalPanel">
        <button class="btn-close-panel" onclick="document.getElementById('journalPanel').classList.remove('open')">
            <i class="fa-solid fa-xmark"></i>
        </button>
        
        <div>
            <div class="panel-title"><?= cms_text('kontemplasi_panel_title', 'The Codex') ?></div>
            <div class="panel-subtitle">Rekam jejak spiritual dan pikiran Anda di sini.</div>
        </div>
        
        <form action="/kontemplasi/store" method="POST" class="journal-form">
            <?= csrf_field() ?>
            <textarea name="content" placeholder="<?= cms_raw('kontemplasi_ph_journal', 'Apa yang sedang Anda renungkan hari ini? Tuliskan dengan jujur...') ?>" required></textarea>
            
            <select name="mood" id="moodSelect" onchange="changeMoodAura()">
                <option value="Netral">Mood: Netral / Tenang</option>
                <option value="Damai">Mood: Damai & Sejuk</option>
                <option value="Bersyukur">Mood: Penuh Rasa Syukur</option>
                <option value="Gelisah">Mood: Gelisah / Cemas</option>
                <option value="Terbebani">Mood: Terbebani / Lelah</option>
            </select>
            
            <label style="display:flex; align-items:center; gap:10px; font-size:0.8rem; color:#888; margin-bottom:20px; cursor:pointer;">
                <input type="checkbox" name="is_private" value="1" checked style="width:16px; height:16px; accent-color:var(--gold-main); cursor:pointer;"> 
                <?= cms_text('kontemplasi_label_private', 'Kunci sebagai Jurnal Privat (Enkripsi)') ?>
            </label>
            
            <button type="submit" class="btn-submit-journal"><?= cms_text('kontemplasi_btn_submit', 'Rekam Jejak') ?></button>
        </form>

        <div class="panel-title" style="margin-top:20px; font-size:1.4rem;"><?= cms_text('kontemplasi_history_title', 'Arsip Masa Lalu') ?></div>
        
        <div style="overflow-y:auto; flex:1; padding-right:10px;">
        <?php if(!empty($journals)): ?>
            <?php foreach($journals as $j): ?>
                <div class="journal-card">
                    <div class="journal-date">
                        <span><?= date('d M Y, H:i', strtotime($j['created_at'])) ?></span>
                        <span class="mood-tag mood-<?= esc($j['mood']) ?>"><?= esc($j['mood']) ?></span>
                    </div>
                    <div class="journal-content">"<?= esc($j['content']) ?>"</div>
                    <?php if($j['is_private']): ?>
                        <div style="font-size:0.65rem; color:#666; margin-top:15px; text-transform:uppercase; letter-spacing:1px;">
                            <i class="fa-solid fa-lock" style="font-size:0.5rem; margin-right:5px;"></i> Secured
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; padding:30px; color:#555; font-size:0.85rem; font-style:italic; background:rgba(255,255,255,0.02); border-radius:10px;">
                <?= cms_text('kontemplasi_history_empty', 'Belum ada arsip renungan yang tersimpan.') ?>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <!-- Ambient Audio System (Real M4A Audio + Quran MP3) -->
    <video id="audio-rain" loop preload="auto" playsinline style="display:none;"><source src="<?= base_url('assets/audio/rain.mp4') ?>" type="video/mp4"></video>
    <video id="audio-ocean" loop preload="auto" playsinline style="display:none;"><source src="<?= base_url('assets/audio/ocean.mp4') ?>" type="video/mp4"></video>
    <video id="audio-space" loop preload="auto" playsinline style="display:none;"><source src="<?= base_url('assets/audio/space.mp4') ?>" type="video/mp4"></video>
    <video id="audio-zen" loop preload="auto" playsinline style="display:none;"><source src="<?= base_url('assets/audio/zen.mp4') ?>" type="video/mp4"></video>
    
    <audio id="audio-quran" loop preload="auto">
        <source src="https://server8.mp3quran.net/afs/055.mp3?v=3" type="audio/mpeg">
    </audio>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script>
let isBreathing = false;
let currentAudio = null;

// Audio Management
let isAudioMenuOpen = false;
let currentTrackKey = null;
let currentTrackAudio = null;

function toggleAudioMenu() {
    const menu = document.getElementById('audioMenu');
    isAudioMenuOpen = !isAudioMenuOpen;
    menu.classList.toggle('show');
}

function switchTrack(trackKey, btnElement) {
    if (trackKey === currentTrackKey) return; // ignore duplicate clicks
    
    // 1. Stop current track slowly
    if (currentTrackAudio) {
        gsap.to(currentTrackAudio, {volume: 0, duration: 1, onComplete: function(audio) {
            audio.pause();
        }, onCompleteParams: [currentTrackAudio]});
    }

    if (trackKey === 'none') {
        currentTrackKey = null;
        currentTrackAudio = null;
        
        document.querySelectorAll('.audio-track').forEach(btn => btn.classList.remove('active'));
        if(btnElement) btnElement.classList.add('active');
        const audioBtnIcon = document.getElementById('btnAudio');
        audioBtnIcon.innerHTML = '<i class="fa-solid fa-volume-xmark"></i><span>Audio Mati</span>';
        audioBtnIcon.style.color = '#fff';
        return;
    }

    currentTrackKey = trackKey;

    // Update UI
    document.querySelectorAll('.audio-track').forEach(btn => btn.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');

    // Update Icon
    const audioBtnIcon = document.getElementById('btnAudio');
    audioBtnIcon.innerHTML = '<i class="fa-solid fa-volume-high"></i><span>Ubah Suara</span>';
    audioBtnIcon.style.color = 'var(--gold-main)';

    // Play new track
    let newAudio = document.getElementById('audio-' + trackKey);
    if (newAudio) {
        newAudio.volume = 0;
        let p = newAudio.play();
        if (p !== undefined) {
            p.then(() => gsap.to(newAudio, {volume: 0.8, duration: 2})).catch(e => console.log(e));
        }
        currentTrackAudio = newAudio;
    }
}

// Wisdom Quotes System
const wisdoms = [
    "Tarik kedamaian, hembuskan kekhawatiran.",
    "Semua akan berlalu. Begitu pun rintangan ini.",
    "Di dalam keheningan, kita menemukan jawaban terdalam.",
    "Fokus pada saat ini. Masa lalu sudah berlalu.",
    "Anda lebih kuat dari rasa takut Anda.",
    "Syukuri napas ini, anugerah terindah hari ini.",
    "Berdamailah dengan diri sendiri.",
    "Lepaskan segala yang tidak bisa Anda kendalikan."
];
const wisdomText = document.getElementById('wisdomText');

function showWisdom() {
    if(!isBreathing) return;
    const quote = wisdoms[Math.floor(Math.random() * wisdoms.length)];
    wisdomText.innerText = quote;
    
    gsap.fromTo(wisdomText, 
        { opacity: 0, y: 30 }, 
        { opacity: 1, y: 0, duration: 3, ease: "power2.out", onComplete: () => {
            gsap.to(wisdomText, { opacity: 0, y: -30, duration: 3, delay: 6, onComplete: () => {
                setTimeout(showWisdom, Math.random() * 12000 + 8000); // Tampil setiap 8-20 detik
            }});
        }}
    );
}

// Generate Cosmic Dust
const dustContainer = document.getElementById('dustContainer');
for(let i=0; i<30; i++) {
    let dust = document.createElement('div');
    dust.classList.add('dust');
    let size = Math.random() * 2 + 1;
    dust.style.width = size + 'px';
    dust.style.height = size + 'px';
    dust.style.left = Math.random() * 100 + 'vw';
    dust.style.top = Math.random() * 100 + 'vh';
    dustContainer.appendChild(dust);
    animateDust(dust);
}

function animateDust(dust) {
    gsap.to(dust, {
        y: "-=150",
        x: "+=" + (Math.random() * 150 - 75),
        opacity: Math.random() * 0.4 + 0.1,
        duration: Math.random() * 15 + 15,
        ease: "sine.inOut",
        yoyo: true,
        repeat: -1
    });
}

function beginSanctuary() {
    // [CRITICAL MOBILE FIX] Unlock HTML5 Audio synchronously inside user click event!
    ['rain', 'ocean', 'space', 'zen', 'quran'].forEach(key => {
        let aud = document.getElementById('audio-' + key);
        if(aud) {
            aud.volume = 0;
            let p = aud.play();
            if(p !== undefined) p.then(() => aud.pause()).catch(e => console.log("Unlock HTML5 Audio:", e));
        }
    });

    const overlay = document.getElementById('startOverlay');
    overlay.style.backdropFilter = "blur(0px)";
    overlay.style.opacity = '0';
    
    setTimeout(() => {
        overlay.style.display = 'none';
        isBreathing = true;
        breathCycle();
        setTimeout(showWisdom, 6000); // Mulai kutipan setelah 6 detik
        
        // Memaksa play track pertama (Hujan)
        switchTrack('rain', document.querySelector('.audio-track'));
    }, 1500);
}

function breathCycle() {
    if(!isBreathing) return;
    
    const orb = document.getElementById('orb');
    const text = document.getElementById('guideText');

    // 1. INHALE (4s)
    text.style.opacity = 0;
    setTimeout(() => { text.innerText = "<?= cms_raw('kontemplasi_breath_in', 'Tarik Napas') ?>"; text.style.opacity = 1; }, 500);
    orb.className = 'orb-container orb-inhale';

    // 2. HOLD (7s)
    setTimeout(() => {
        text.style.opacity = 0;
        setTimeout(() => { text.innerText = "<?= cms_raw('kontemplasi_breath_hold', 'Tahan') ?>"; text.style.opacity = 1; }, 500);
        orb.className = 'orb-container orb-hold';
        
        document.querySelectorAll('.mandala-ring').forEach(r => r.style.borderColor = 'rgba(212,175,55,0.8)');
    }, 4000);

    // 3. EXHALE (8s)
    setTimeout(() => {
        text.style.opacity = 0;
        setTimeout(() => { text.innerText = "<?= cms_raw('kontemplasi_breath_out', 'Hembuskan') ?>"; text.style.opacity = 1; }, 500);
        orb.className = 'orb-container orb-exhale';
        document.querySelectorAll('.mandala-ring').forEach(r => r.style.borderColor = 'rgba(212,175,55,0.3)');
        
        // Restart cycle
        setTimeout(breathCycle, 8000);
    }, 11000);
}

// Dynamic Aura / Mood Logic
function changeMoodAura() {
    const mood = document.getElementById('moodSelect').value;
    
    let color = "rgba(212, 175, 55, 0.05)"; // Default / Netral (Gold)
    if(mood === 'Damai') color = "rgba(0, 255, 255, 0.08)"; // Cyan
    if(mood === 'Bersyukur') color = "rgba(255, 215, 0, 0.1)"; // Bright Gold
    if(mood === 'Gelisah') color = "rgba(255, 100, 100, 0.08)"; // Soft Red
    if(mood === 'Terbebani') color = "rgba(150, 100, 255, 0.08)"; // Purple/Indigo
    
    document.documentElement.style.setProperty('--aura-color', color);
}

// Journal Panel Toggle
const btnToggleJournal = document.getElementById('btnToggleJournal');
const journalPanel = document.getElementById('journalPanel');
btnToggleJournal.addEventListener('click', () => {
    journalPanel.classList.toggle('open');
    if(navigator.vibrate) navigator.vibrate(20);
});

// Flashdata Handling
<?php if(session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
    journalPanel.classList.add('open');
    document.getElementById('startOverlay').style.display = 'none';
    isBreathing = true;
    breathCycle();
    setTimeout(showWisdom, 8000);
    switchTrack('rain', document.querySelector('.audio-track')); // Start audio as well
<?php endif; ?>
</script>
<?= $this->endSection() ?>
