<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Protokol Multazam - VVIP Event Ticketing
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --bg-dark: #050505;
        --ticket-bg: #111;
    }

    body {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at 50% 0%, rgba(212,175,55,0.15) 0%, transparent 60%);
        font-family: 'Inter', sans-serif;
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .multazam-wrapper {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        z-index: 10;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* HEADER */
    .multazam-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 60px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
        transition: all 0.4s;
        padding: 10px 20px;
        border-radius: 30px;
        border: 1px solid rgba(212,175,55,0.2);
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(10px);
    }
    .btn-back:hover {
        background: rgba(212,175,55,0.1);
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(212,175,55,0.1);
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--gold-main);
        margin: 0;
        text-align: right;
    }
    .page-subtitle {
        color: #8899a6;
        font-size: 0.9rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        text-align: right;
        margin-top: 5px;
    }

    /* THE TICKET (3D Holographic Pass) */
    .ticket-container {
        perspective: 1500px;
        margin-bottom: 50px;
    }

    .vip-ticket {
        width: 100%;
        max-width: 350px;
        height: 600px;
        background: var(--ticket-bg);
        border-radius: 20px;
        position: relative;
        transform-style: preserve-3d;
        box-shadow: 0 30px 60px rgba(0,0,0,0.8), 0 0 20px rgba(212,175,55,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Holographic Foil Overlay */
    .vip-ticket::after {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: linear-gradient(
            135deg, 
            transparent 0%, 
            rgba(255,255,255,0.1) 45%, 
            rgba(212,175,55,0.4) 50%, 
            rgba(255,255,255,0.1) 55%, 
            transparent 100%
        );
        transform: translateZ(1px);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.5s;
        mix-blend-mode: color-dodge;
    }
    .vip-ticket:hover::after {
        opacity: 1;
        animation: holoFoil 3s infinite linear;
    }

    @keyframes holoFoil {
        0% { transform: translateY(-50%) translateX(-50%) rotate(0deg); }
        100% { transform: translateY(0%) translateX(0%) rotate(360deg); }
    }

    /* Ticket Top (Visual) */
    .ticket-top {
        height: 250px;
        background-image: url('https://images.unsplash.com/photo-1519671482749-fd098f392a56?q=80&w=2670&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        position: relative;
        border-bottom: 2px dashed rgba(212,175,55,0.5);
    }
    .ticket-top::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(to top, var(--ticket-bg) 0%, transparent 100%);
    }

    .ticket-badge {
        position: absolute;
        top: 20px; right: 20px;
        background: var(--gold-main);
        color: #000;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 2px;
        text-transform: uppercase;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }

    /* Ticket Cutouts */
    .cutout {
        position: absolute;
        bottom: -15px;
        width: 30px; height: 30px;
        background: var(--bg-dark);
        border-radius: 50%;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.5);
    }
    .cutout-left { left: -15px; }
    .cutout-right { right: -15px; }

    /* Ticket Body */
    .ticket-body {
        flex: 1;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transform: translateZ(30px); /* 3D pop effect */
    }

    .event-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        color: var(--gold-main);
        margin: 0 0 10px 0;
        line-height: 1.2;
    }
    .event-desc {
        color: #aaa;
        font-size: 0.85rem;
        margin-bottom: 20px;
    }

    .ticket-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    .detail-label {
        color: #666;
        font-size: 0.65rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .detail-value {
        color: #fff;
        font-size: 0.9rem;
        font-weight: bold;
    }

    /* QR Code Section */
    .qr-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .qr-code {
        width: 80px; height: 80px;
        background: #fff;
        padding: 5px;
        border-radius: 8px;
        background-image: url('https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg');
        background-size: cover;
    }
    .seat-info {
        text-align: right;
    }
    .seat-number {
        font-size: 2rem;
        font-family: 'Playfair Display', serif;
        color: var(--gold-main);
    }

    /* ACTION BUTTONS */
    .action-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    .btn-wallet {
        background: #fff;
        color: #000;
        border: none;
        padding: 15px 30px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex; align-items: center; gap: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .btn-wallet:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
    }
    
    .btn-secondary {
        background: transparent;
        color: var(--gold-main);
        border: 1px solid var(--gold-main);
        padding: 15px 30px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex; align-items: center; gap: 10px;
        transition: background 0.3s;
    }
    .btn-secondary:hover {
        background: rgba(212,175,55,0.1);
    }

    /* PRAYER PANEL (DINDING MULTAZAM) */
    .prayer-panel {
        position: fixed; top: 0; right: -450px; width: 100%; max-width: 450px; height: 100vh;
        background: rgba(5, 5, 5, 0.95); backdrop-filter: blur(20px);
        border-left: 1px solid rgba(212,175,55,0.3); z-index: 1000;
        transition: right 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 40px 30px; display: flex; flex-direction: column; gap: 20px;
        overflow-y: auto; color: #fff; box-shadow: -20px 0 50px rgba(0,0,0,0.8);
    }
    .prayer-panel.open { right: 0; }
    
    .btn-close-panel {
        position: absolute;
        top: 20px;
        right: 20px;
        background: transparent;
        border: none;
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 10;
        transition: color 0.3s;
    }
    .btn-close-panel:hover {
        color: var(--gold-main);
    }
    
    .panel-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 1.5rem; letter-spacing: 2px; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 10px; margin-bottom: 10px; padding-right: 30px; }
    .prayer-form textarea {
        width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(212,175,55,0.3);
        color: #fff; padding: 12px; margin-bottom: 15px; border-radius: 5px; font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.1rem;
    }
    .btn-submit-prayer { width: 100%; background: var(--gold-main); color: #000; border: none; padding: 12px; font-weight: bold; cursor: pointer; border-radius: 5px; letter-spacing: 2px; text-transform: uppercase; }
    
    .prayer-card { background: rgba(255,255,255,0.02); border: 1px dashed rgba(212,175,55,0.3); padding: 20px; border-radius: 8px; margin-bottom: 15px; position: relative; }
    .prayer-card:last-child { margin-bottom: 50px; }
    .prayer-date { font-size: 0.75rem; color: var(--gold-main); margin-bottom: 10px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
    .prayer-content { font-size: 1rem; line-height: 1.6; color: #ddd; font-family: 'Playfair Display', serif; font-style: italic; }
    .prayer-status { position: absolute; bottom: 15px; right: 15px; font-size: 0.65rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }

    @media (max-width: 768px) {
        .multazam-header { flex-direction: column; gap: 15px; align-items: flex-start; margin-bottom: 30px; }
        .btn-back { align-self: flex-start; }
        .page-title { text-align: left; font-size: 2rem; }
        .page-subtitle { text-align: left; }
        .vip-ticket { max-width: 320px; height: 550px; margin: 0 auto; }
        .action-buttons { flex-direction: column; width: 100%; max-width: 320px; }
        .btn-wallet, .btn-secondary { width: 100%; justify-content: center; }
        .prayer-panel { width: 100%; right: -100%; padding: 30px 20px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="multazam-wrapper">
    <header class="multazam-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> Vault
        </a>
        <div class="header-titles">
            <h1 class="page-title">Protokol Multazam</h1>
            <div class="page-subtitle">Sistem Tiket VVIP Eksklusif</div>
        </div>
    </header>

    <!-- 3D Ticket -->
    <div class="ticket-container">
        <div class="vip-ticket js-tilt-ticket">
            <div class="ticket-top">
                <div class="ticket-badge">VVIP PASS</div>
                <div class="cutout cutout-left"></div>
                <div class="cutout cutout-right"></div>
            </div>
            <div class="ticket-body">
                <div>
                    <h2 class="event-title">Malam Silaturahmi Akbar & Gala Dinner</h2>
                    <p class="event-desc">Pertemuan tertutup khusus anggota alumni terverifikasi.</p>
                    
                    <div class="ticket-details">
                        <div class="detail-item">
                            <span class="detail-label">Tanggal</span>
                            <span class="detail-value">24 Nov 2026</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Waktu</span>
                            <span class="detail-value">19:00 WIB</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">The Ritz-Carlton, Jkt</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Dresscode</span>
                            <span class="detail-value">Black Tie / Formal</span>
                        </div>
                    </div>
                </div>
                
                <div class="qr-section">
                    <div class="qr-code"></div>
                    <div class="seat-info">
                        <span class="detail-label">Meja VIP</span>
                        <div class="seat-number">T-12</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn-wallet" onclick="addToWallet(this)">
            <i class="fa-brands fa-apple"></i> Add to Apple Wallet
        </button>
        <button class="btn-secondary" id="btnTogglePrayer">
            <i class="fa-solid fa-hands-praying"></i> Dinding Multazam
        </button>
    </div>

</div>

<!-- PANEL DINDING MULTAZAM (PRAYERS) -->
<div class="prayer-panel" id="prayerPanel">
    <button class="btn-close-panel" id="btnClosePrayer"><i class="fa-solid fa-xmark"></i></button>
    <div class="panel-title">Panjatkan Doa</div>
    <form action="/multazam/store" method="POST" class="prayer-form">
        <?= csrf_field() ?>
        <textarea name="prayer_text" rows="4" placeholder="Tuliskan harapan, doa, atau munajat Anda..." required></textarea>
        <button type="submit" class="btn-submit-prayer">Panjatkan</button>
    </form>

    <div class="panel-title" style="margin-top:20px;">Dinding Harapan</div>
    <?php if(!empty($prayers)): ?>
        <?php foreach($prayers as $p): ?>
            <div class="prayer-card">
                <div class="prayer-date"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                <div class="prayer-content">"<?= esc($p['prayer_text']) ?>"</div>
                <div class="prayer-status"><i class="fa-solid fa-check-double" style="color:var(--gold-main);"></i> <?= esc($p['status']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align:center; padding:20px; color:#555; font-size:0.8rem; font-style:italic;">Belum ada munajat yang dipanjatkan.</div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro Animations
    gsap.from(".vip-ticket", { y: 100, opacity: 0, rotationX: 20, duration: 1.5, ease: "back.out(1.2)" });
    gsap.from(".action-buttons button", { y: 30, opacity: 0, duration: 0.8, stagger: 0.2, delay: 0.5, ease: "power2.out" });

    // 3D Tilt Effect
    const ticket = document.querySelector('.js-tilt-ticket');
    ticket.addEventListener('mousemove', (e) => {
        const rect = ticket.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = ((y - centerY) / centerY) * -15;
        const rotateY = ((x - centerX) / centerX) * 15;

        ticket.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        ticket.style.transition = "none";
    });

    ticket.addEventListener('mouseleave', () => {
        ticket.style.transition = "transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)";
        ticket.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
    });
});

function addToWallet(btn) {
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    btn.style.pointerEvents = 'none';
    
    setTimeout(() => {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersimpan di Wallet';
        btn.style.background = 'var(--neon-green)';
        btn.style.color = '#000';
    }, 1500);
}

// Panel Toggle
const btnTogglePrayer = document.getElementById('btnTogglePrayer');
const btnClosePrayer = document.getElementById('btnClosePrayer');
const prayerPanel = document.getElementById('prayerPanel');

if(btnTogglePrayer) {
    btnTogglePrayer.addEventListener('click', () => {
        prayerPanel.classList.toggle('open');
        if(navigator.vibrate) navigator.vibrate(20);
    });
}

if(btnClosePrayer) {
    btnClosePrayer.addEventListener('click', () => {
        prayerPanel.classList.remove('open');
    });
}

// Flashdata
<?php if(session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
    prayerPanel.classList.add('open');
<?php endif; ?>

</script>
<?= $this->endSection() ?>
