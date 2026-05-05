<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Tarbiyah Nexus - Mentorship & B2B Halal Ecosystem
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --wax-red: #8b0000;
        --bg-dark: #020403;
        --text-muted: #8899a6;
    }

    body {
        background-color: var(--bg-dark);
        background-image: 
            radial-gradient(circle at 100% 0%, rgba(212,175,55,0.08) 0%, transparent 50%),
            radial-gradient(circle at 0% 100%, rgba(212,175,55,0.05) 0%, transparent 50%);
        font-family: 'Inter', sans-serif;
        color: #fff;
        min-height: 100vh;
    }

    .nexus-wrapper {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        z-index: 10;
        min-height: 100vh;
    }

    /* HEADER */
    .nexus-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
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

    .header-titles {
        text-align: right;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        color: var(--gold-main);
        margin: 0;
        text-shadow: 0 5px 20px rgba(0,0,0,0.8);
    }
    .page-subtitle {
        font-size: 0.9rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-top: 10px;
    }

    /* TABS */
    .nexus-tabs {
        display: flex;
        gap: 20px;
        margin-bottom: 40px;
        border-bottom: 1px solid rgba(212,175,55,0.2);
        padding-bottom: 15px;
    }
    .tab-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1.1rem;
        font-family: 'Playfair Display', serif;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        padding: 10px 20px;
    }
    .tab-btn:hover { color: #fff; }
    .tab-btn.active { color: var(--gold-main); }
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -16px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--gold-main);
        box-shadow: 0 0 10px var(--gold-main);
    }

    /* GRID & CARDS */
    .nexus-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    .nexus-card {
        background: rgba(10, 15, 12, 0.6);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 15px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: transform 0.4s, box-shadow 0.4s;
    }
    .nexus-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        border-color: rgba(212,175,55,0.4);
    }

    /* Subtle Gold Thread Background inside Card */
    .nexus-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
        pointer-events: none;
    }

    .card-badge {
        position: absolute;
        top: 20px; right: 20px;
        font-size: 0.65rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--bg-dark);
        background: var(--gold-main);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: bold;
    }
    .card-badge.b2b {
        background: transparent;
        color: var(--gold-main);
        border: 1px solid var(--gold-main);
    }

    .card-avatar {
        width: 70px; height: 70px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        border: 2px solid var(--gold-main);
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        margin: 0 0 5px 0;
        color: #fff;
    }
    .card-subtitle {
        color: var(--gold-main);
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .card-desc {
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    /* WAX SEAL BUTTON (God-Tier Interaction) */
    .btn-wax {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        width: 100%;
        background: rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 12px 20px;
        color: #fff;
        font-size: 0.8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.4s;
    }
    
    .wax-seal {
        width: 30px;
        height: 30px;
        background: radial-gradient(circle, var(--wax-red) 40%, #5a0000 100%);
        border-radius: 50%;
        position: relative;
        box-shadow: inset 0 2px 5px rgba(255,255,255,0.2), 0 2px 5px rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.7);
        transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    .wax-seal::after {
        content: '';
        position: absolute;
        width: 36px; height: 36px;
        border-radius: 50%;
        border: 1px dashed rgba(139,0,0,0.5);
    }

    .btn-wax:hover {
        background: rgba(212,175,55,0.1);
        border-color: var(--gold-main);
        color: var(--gold-main);
    }
    .btn-wax:hover .wax-seal {
        transform: scale(1.2) rotate(15deg);
        box-shadow: inset 0 2px 5px rgba(255,255,255,0.4), 0 5px 15px rgba(139,0,0,0.6);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .nexus-header { flex-direction: column-reverse; gap: 30px; align-items: flex-end; }
        .page-title { font-size: 2.2rem; }
        .nexus-tabs { overflow-x: auto; white-space: nowrap; padding-bottom: 10px; }
        .tab-btn { font-size: 1rem; padding: 10px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="nexus-wrapper">
    <header class="nexus-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> Kembali ke Vault
        </a>
        <div class="header-titles">
            <h1 class="page-title">Tarbiyah Nexus</h1>
            <p class="page-subtitle">Pusat Bimbingan & Ekosistem Halal B2B</p>
        </div>
    </header>

    <div class="nexus-tabs">
        <button class="tab-btn active" onclick="switchTab('mentorship')">Jaringan Mentorship</button>
        <button class="tab-btn" onclick="switchTab('b2b')">Sovereign B2B & Tender</button>
    </div>

    <!-- MENTORSHIP TAB -->
    <div class="nexus-grid" id="mentorship-tab">
        <!-- Card 1 -->
        <div class="nexus-card">
            <div class="card-badge">Mentor Senior</div>
            <div class="card-avatar" style="background-image: url('https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=200&auto=format&fit=crop');"></div>
            <h3 class="card-title">Dr. Muhammad Ilham</h3>
            <div class="card-subtitle">CEO & Founder, Zenith Corp</div>
            <p class="card-desc">Siap membimbing 3 orang untuk program intensif kepemimpinan korporat islami dan manajemen risiko.</p>
            <button class="btn-wax" onclick="sendRequest(this)">
                <div class="wax-seal"><i class="fa-solid fa-feather-pointed"></i></div>
                <span>Ajukan Bimbingan</span>
            </button>
        </div>

        <!-- Card 2 -->
        <div class="nexus-card">
            <div class="card-badge">Mentor Eksekutif</div>
            <div class="card-avatar" style="background-image: url('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop');"></div>
            <h3 class="card-title">Aisyah Rahman, M.Sc</h3>
            <div class="card-subtitle">Direktur FinTech Syariah</div>
            <p class="card-desc">Fokus pada strategi startup, legalitas syariah, dan ekspansi pasar digital. Slot tersisa 1 orang.</p>
            <button class="btn-wax" onclick="sendRequest(this)">
                <div class="wax-seal"><i class="fa-solid fa-feather-pointed"></i></div>
                <span>Ajukan Bimbingan</span>
            </button>
        </div>

        <!-- Card 3 -->
        <div class="nexus-card">
            <div class="card-badge">Mentor Spiritual</div>
            <div class="card-avatar" style="background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop');"></div>
            <h3 class="card-title">Ustadz Hasan Al-Banna</h3>
            <div class="card-subtitle">Dewan Syuro Eksekutif</div>
            <p class="card-desc">Tarbiyah intensif via Majlis eksklusif mengenai Adab Muamalah dan menjaga keseimbangan dunia-akhirat.</p>
            <button class="btn-wax" onclick="sendRequest(this)">
                <div class="wax-seal"><i class="fa-solid fa-feather-pointed"></i></div>
                <span>Ajukan Bimbingan</span>
            </button>
        </div>
    </div>

    <!-- B2B TAB (Hidden by default) -->
    <div class="nexus-grid" id="b2b-tab" style="display: none;">
        <!-- Card 1 -->
        <div class="nexus-card">
            <div class="card-badge b2b">Tender Terbuka</div>
            <h3 class="card-title">Sistem ERP Syariah</h3>
            <div class="card-subtitle">PT. Sovereign Teknologi Investama</div>
            <p class="card-desc">Mencari vendor internal angkatan untuk pengembangan Modul Keuangan Syariah dengan nilai kontrak klasifikasi [A].</p>
            <button class="btn-wax" onclick="sendRequest(this)">
                <div class="wax-seal"><i class="fa-solid fa-handshake"></i></div>
                <span>Ajukan Proposal</span>
            </button>
        </div>

        <!-- Card 2 -->
        <div class="nexus-card">
            <div class="card-badge b2b">Peluang Investasi</div>
            <h3 class="card-title">Ekspansi Jaringan Klinik</h3>
            <div class="card-subtitle">Sifa Medika Group</div>
            <p class="card-desc">Dibuka porsi saham eksklusif (Mudarabah) untuk pembangunan 3 klinik cabang di Jawa Barat. Khusus anggota terverifikasi.</p>
            <button class="btn-wax" onclick="sendRequest(this)">
                <div class="wax-seal"><i class="fa-solid fa-handshake"></i></div>
                <span>Pelajari Dokumen</span>
            </button>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro Animation
    const tl = gsap.timeline();
    tl.from(".btn-back", { x: -30, opacity: 0, duration: 0.8, ease: "power2.out" })
      .from(".header-titles", { y: -30, opacity: 0, duration: 0.8, ease: "power2.out" }, "-=0.5")
      .from(".nexus-tabs", { opacity: 0, duration: 0.5 }, "-=0.3")
      .from(".nexus-card", { y: 50, opacity: 0, duration: 0.8, stagger: 0.15, ease: "back.out(1.2)" }, "-=0.2");
});

function switchTab(tabId) {
    // Update active button
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    // Fade out current grid
    const currentGrid = document.querySelector('.nexus-grid:not([style*="display: none"])');
    const newGrid = document.getElementById(tabId + '-tab');

    if (currentGrid.id === newGrid.id) return;

    gsap.to(currentGrid, {
        opacity: 0, y: 20, duration: 0.3, 
        onComplete: () => {
            currentGrid.style.display = 'none';
            newGrid.style.display = 'grid';
            gsap.fromTo(newGrid.querySelectorAll('.nexus-card'), 
                { opacity: 0, y: 30 },
                { opacity: 1, y: 0, duration: 0.5, stagger: 0.1, ease: "power2.out" }
            );
        }
    });
}

function sendRequest(btn) {
    const originalText = btn.querySelector('span').innerText;
    
    // Animate wax seal press
    gsap.to(btn.querySelector('.wax-seal'), {
        scale: 0.8,
        duration: 0.2,
        yoyo: true,
        repeat: 1
    });

    btn.querySelector('span').innerText = "Mengirim...";
    btn.style.pointerEvents = "none";
    
    setTimeout(() => {
        btn.querySelector('span').innerText = "Terkirim & Disegel";
        btn.style.borderColor = "var(--gold-main)";
        btn.style.background = "rgba(212,175,55,0.1)";
        btn.querySelector('.wax-seal').innerHTML = '<i class="fa-solid fa-check"></i>';
    }, 1500);
}
</script>
<?= $this->endSection() ?>
