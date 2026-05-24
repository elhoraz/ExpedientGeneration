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

    /* JOURNAL PANEL */
    .journal-panel {
        position: fixed; top: 0; right: -450px; width: 400px; height: 100vh;
        background: rgba(10, 15, 12, 0.95); backdrop-filter: blur(20px);
        border-left: 1px solid rgba(212,175,55,0.3); z-index: 1000;
        transition: right 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 40px 30px; display: flex; flex-direction: column; gap: 20px;
        overflow-y: auto; color: #fff; box-shadow: -20px 0 50px rgba(0,0,0,0.8);
        font-family: 'Inter', sans-serif;
    }
    .journal-panel.open { right: 0; }
    
    .panel-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 1.5rem; letter-spacing: 2px; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 10px; margin-bottom: 10px; }
    .journal-form input, .journal-form select, .journal-form textarea {
        width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(212,175,55,0.3);
        color: #fff; padding: 12px; margin-bottom: 15px; border-radius: 5px; font-family: 'Inter', sans-serif;
    }
    .btn-submit-journal { width: 100%; background: var(--gold-main); color: #000; border: none; padding: 12px; font-weight: bold; cursor: pointer; border-radius: 5px; letter-spacing: 2px; text-transform: uppercase; }
    
    .journal-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; margin-bottom: 15px; position: relative; }
    .journal-date { font-size: 0.75rem; color: var(--gold-main); margin-bottom: 10px; font-weight: bold; letter-spacing: 1px; }
    .journal-content { font-size: 0.9rem; line-height: 1.6; color: #ddd; font-style: italic; }
    .journal-mood { position: absolute; top: 15px; right: 15px; background: rgba(212,175,55,0.2); color: var(--gold-main); padding: 3px 8px; border-radius: 10px; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 1px; }
    
    @media (max-width: 768px) {
        .journal-panel { width: 100%; right: -100%; }
        .stealth-controls { bottom: 20px; gap: 20px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="sanctuary-wrapper">
    
    <!-- Dust Particles -->
    <div class="dust-container" id="dustContainer"></div>

    <!-- Intro Overlay -->
    <div class="start-overlay" id="startOverlay">
        <h1 class="start-title"><?= cms_text('kontemplasi_title', 'Ruang Kontemplasi') ?></h1>
        <p class="start-desc"><?= cms_text('kontemplasi_desc', 'Tinggalkan sejenak urusan duniawi. Posisikan diri Anda dengan nyaman, aktifkan suara, dan ikuti ritme keheningan.') ?></p>
        <button class="btn-start" onclick="beginSanctuary()"><?= cms_text('kontemplasi_btn_start', 'Mulai Keheningan') ?></button>
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
            <span><?= cms_text('kontemplasi_btn_back', 'Kembali') ?></span>
        </a>
        <button class="stealth-btn" id="btnAudio" onclick="toggleAudio()">
            <i class="fa-solid fa-volume-xmark"></i>
            <span><?= cms_text('kontemplasi_btn_audio', 'Audio Mati') ?></span>
        </button>
        <button class="stealth-btn" id="btnToggleJournal">
            <i class="fa-solid fa-feather-pointed"></i>
            <span><?= cms_text('kontemplasi_btn_journal', 'Jurnal') ?></span>
        </button>
    </div>

    <!-- Journal Panel -->
    <div class="journal-panel" id="journalPanel">
        <div class="panel-title"><?= cms_text('kontemplasi_panel_title', 'Tulis Kontemplasi') ?></div>
        <form action="/kontemplasi/store" method="POST" class="journal-form">
            <?= csrf_field() ?>
            <textarea name="content" rows="4" placeholder="<?= cms_raw('kontemplasi_ph_journal', 'Apa yang Anda renungkan hari ini? Tuliskan isi pikiran Anda dengan jujur...') ?>" required></textarea>
            
            <div style="display:flex; gap:10px; margin-bottom:15px;">
                <select name="mood" style="margin-bottom:0; flex:1;">
                    <option value="Netral"><?= cms_text('kontemplasi_mood_netral', 'Mood: Netral') ?></option>
                    <option value="Damai"><?= cms_text('kontemplasi_mood_damai', 'Mood: Damai') ?></option>
                    <option value="Gelisah"><?= cms_text('kontemplasi_mood_gelisah', 'Mood: Gelisah') ?></option>
                    <option value="Bersyukur"><?= cms_text('kontemplasi_mood_syukur', 'Mood: Bersyukur') ?></option>
                    <option value="Terbebani"><?= cms_text('kontemplasi_mood_beban', 'Mood: Terbebani') ?></option>
                </select>
            </div>
            
            <label style="display:flex; align-items:center; gap:10px; font-size:0.8rem; color:#888; margin-bottom:15px; cursor:pointer;">
                <input type="checkbox" name="is_private" value="1" checked style="width:auto; margin:0;"> <?= cms_text('kontemplasi_label_private', 'Kunci sebagai Jurnal Privat') ?>
            </label>
            
            <button type="submit" class="btn-submit-journal"><?= cms_text('kontemplasi_btn_submit', 'Rekam Jejak') ?></button>
        </form>

        <div class="panel-title" style="margin-top:20px;"><?= cms_text('kontemplasi_history_title', 'Catatan Refleksi Anda') ?></div>
        <?php if(!empty($journals)): ?>
            <?php foreach($journals as $j): ?>
                <div class="journal-card">
                    <div class="journal-mood"><?= esc($j['mood']) ?></div>
                    <div class="journal-date"><?= date('d M Y - H:i', strtotime($j['created_at'])) ?></div>
                    <div class="journal-content">"<?= esc($j['content']) ?>"</div>
                    <?php if($j['is_private']): ?>
                        <div style="font-size:0.6rem; color:#888; margin-top:10px;"><i class="fa-solid fa-lock" style="font-size:0.5rem;"></i> <?= cms_text('kontemplasi_private_notice', 'Hanya Anda yang dapat melihat ini') ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; padding:20px; color:#555; font-size:0.8rem; font-style:italic;"><?= cms_text('kontemplasi_history_empty', 'Belum ada jejak refleksi yang direkam.') ?></div>
        <?php endif; ?>
    </div>

    <!-- Ambient Audio (Mockup using HTML5 Audio) -->
    <!-- In real production, use a valid mp3/wav link -->
    <audio id="ambientAudio" loop>
        <source src="https://cdn.pixabay.com/download/audio/2022/02/07/audio_677eb93158.mp3?filename=desert-wind-and-distant-thunder-1234.mp3" type="audio/mpeg">
    </audio>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
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
    setTimeout(() => { text.innerText = "<?= cms_raw('kontemplasi_breath_in', 'Tarik Napas') ?>"; text.style.opacity = 1; }, 500);
    orb.className = 'breathing-orb orb-inhale';

    // Hold (7s)
    setTimeout(() => {
        text.style.opacity = 0;
        setTimeout(() => { text.innerText = "<?= cms_raw('kontemplasi_breath_hold', 'Tahan') ?>"; text.style.opacity = 1; }, 500);
        orb.className = 'breathing-orb orb-hold';
        orb.style.transitionDuration = '7s';
    }, 4000);

    // Exhale (8s)
    setTimeout(() => {
        text.style.opacity = 0;
        setTimeout(() => { text.innerText = "<?= cms_raw('kontemplasi_breath_out', 'Hembuskan') ?>"; text.style.opacity = 1; }, 500);
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
        btn.innerHTML = '<i class="fa-solid fa-volume-high"></i><span><?= cms_raw('kontemplasi_audio_on', 'Audio Aktif') ?></span>';
        btn.style.color = 'var(--gold-main)';
    } else {
        btn.innerHTML = '<i class="fa-solid fa-volume-xmark"></i><span><?= cms_raw('kontemplasi_audio_off', 'Audio Mati') ?></span>';
        btn.style.color = '#fff';
    }
}

// Journal Panel Toggle
const btnToggleJournal = document.getElementById('btnToggleJournal');
const journalPanel = document.getElementById('journalPanel');
btnToggleJournal.addEventListener('click', () => {
    journalPanel.classList.toggle('open');
    if(navigator.vibrate) navigator.vibrate(20);
});

// Flashdata
<?php if(session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
    journalPanel.classList.add('open');
    // Hide start overlay directly to view panel if there's flashdata (post submit)
    document.getElementById('startOverlay').style.display = 'none';
    isBreathing = true;
    breathCycle();
<?php endif; ?>
</script>
<?= $this->endSection() ?>
