<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Baitul Maal - Constellation of Giving
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --islamic-green: #021a0f;
        --bg-dark: #010402;
    }

    body {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at 50% 10%, rgba(212,175,55,0.1) 0%, transparent 80%);
        font-family: 'Inter', sans-serif;
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .maal-wrapper {
        position: relative;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* HEADER */
    .maal-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
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

    /* THE GOLDEN WELL (Visualizer) */
    .well-container {
        position: relative;
        width: 350px;
        height: 350px;
        margin: 40px 0 80px 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .well-outer-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px dashed rgba(212,175,55,0.3);
        animation: spin 30s linear infinite;
    }

    .well-inner-ring {
        position: absolute;
        width: 80%;
        height: 80%;
        border-radius: 50%;
        border: 1px solid rgba(212,175,55,0.6);
        box-shadow: 0 0 50px rgba(212,175,55,0.1), inset 0 0 50px rgba(212,175,55,0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle, rgba(2,26,15,0.8) 0%, rgba(0,0,0,0.9) 100%);
        backdrop-filter: blur(10px);
        z-index: 10;
        transition: box-shadow 0.5s;
    }

    .well-inner-ring.glow {
        box-shadow: 0 0 100px rgba(212,175,55,0.5), inset 0 0 80px rgba(212,175,55,0.4);
    }

    @keyframes spin { 100% { transform: rotate(360deg); } }

    .total-amount {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #fff;
        margin: 0;
        text-shadow: 0 0 20px rgba(212,175,55,0.5);
    }
    .amount-label {
        color: var(--gold-dark);
        font-size: 0.8rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-top: 5px;
    }

    /* PARTICLES */
    .particle {
        position: absolute;
        width: 10px; height: 10px;
        background: var(--gold-main);
        border-radius: 50%;
        box-shadow: 0 0 15px var(--gold-light), 0 0 30px var(--gold-main);
        pointer-events: none;
        z-index: 20;
    }

    /* ACTION BUTTON */
    .btn-donate {
        background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-main) 100%);
        color: #000;
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(212,175,55,0.3);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 60px;
    }
    .btn-donate:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 40px rgba(212,175,55,0.5);
    }
    .btn-donate i { font-size: 1.3rem; }

    /* CAMPAIGN CARDS */
    .campaign-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        width: 100%;
    }

    .campaign-card {
        background: rgba(10, 15, 12, 0.6);
        border: 1px solid rgba(212,175,55,0.15);
        border-radius: 20px;
        padding: 30px;
        position: relative;
        backdrop-filter: blur(10px);
        transition: transform 0.4s;
    }
    .campaign-card:hover {
        transform: translateY(-10px);
        border-color: rgba(212,175,55,0.4);
    }

    .camp-icon {
        width: 50px; height: 50px;
        background: rgba(212,175,55,0.1);
        border-radius: 50%;
        display: flex; justify-content: center; align-items: center;
        color: var(--gold-main);
        font-size: 1.5rem;
        margin-bottom: 20px;
        border: 1px solid rgba(212,175,55,0.3);
    }

    .camp-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        margin: 0 0 10px 0;
    }
    .camp-desc {
        font-size: 0.85rem;
        color: #8899a6;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .progress-track {
        width: 100%;
        height: 6px;
        background: rgba(255,255,255,0.1);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .progress-fill {
        height: 100%;
        background: var(--gold-main);
        box-shadow: 0 0 10px var(--gold-main);
        border-radius: 3px;
    }
    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--gold-main);
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .maal-header { flex-direction: column-reverse; gap: 20px; align-items: center; }
        .page-title { text-align: center; }
        .well-container { width: 280px; height: 280px; }
        .total-amount { font-size: 2rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="maal-wrapper">
    
    <header class="maal-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> Vault
        </a>
        <h1 class="page-title">Baitul Maal</h1>
    </header>

    <!-- Visualizer / Golden Well -->
    <div class="well-container" id="goldenWell">
        <div class="well-outer-ring"></div>
        <div class="well-inner-ring" id="wellInner">
            <h2 class="total-amount" id="totalAmount">Rp 84.5M</h2>
            <div class="amount-label">Total Sedekah Jariyah</div>
        </div>
    </div>

    <!-- Action Button -->
    <button class="btn-donate" id="btnKhidmah">
        <i class="fa-solid fa-hand-holding-dollar"></i> Tunaikan Khidmah
    </button>

    <!-- Specific Campaigns -->
    <div class="campaign-grid">
        <div class="campaign-card">
            <div class="camp-icon"><i class="fa-solid fa-mosque"></i></div>
            <h3 class="camp-title">Wakaf Sumur & Masjid</h3>
            <p class="camp-desc">Pembangunan fasilitas air bersih dan perluasan area shalat di pelosok Nusa Tenggara.</p>
            <div class="progress-track"><div class="progress-fill" style="width: 75%;"></div></div>
            <div class="progress-stats"><span>Terkumpul: 75%</span><span>Target: Rp 200 Jt</span></div>
        </div>

        <div class="campaign-card">
            <div class="camp-icon"><i class="fa-solid fa-book-open-reader"></i></div>
            <h3 class="camp-title">Beasiswa Perintis</h3>
            <p class="camp-desc">Bantuan dana pendidikan penuh untuk 10 santri tahfidz berprestasi hingga sarjana.</p>
            <div class="progress-track"><div class="progress-fill" style="width: 40%;"></div></div>
            <div class="progress-stats"><span>Terkumpul: 40%</span><span>Target: Rp 500 Jt</span></div>
        </div>

        <div class="campaign-card">
            <div class="camp-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <h3 class="camp-title">Dana Darurat Ukhuwah</h3>
            <p class="camp-desc">Kas siaga untuk membantu entitas angkatan atau keluarga inti yang tertimpa musibah/sakit keras.</p>
            <div class="progress-track"><div class="progress-fill" style="width: 90%;"></div></div>
            <div class="progress-stats"><span>Terkumpul: 90%</span><span>Target: Rp 100 Jt</span></div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro Animations
    gsap.from(".maal-header", { y: -30, opacity: 0, duration: 1, ease: "power3.out" });
    gsap.from(".well-container", { scale: 0.5, opacity: 0, duration: 1.5, ease: "back.out(1.5)" });
    gsap.from(".btn-donate", { y: 30, opacity: 0, duration: 1, delay: 0.5, ease: "power2.out" });
    gsap.from(".campaign-card", { y: 50, opacity: 0, duration: 0.8, stagger: 0.2, delay: 0.8, ease: "power2.out" });

    // Interaction logic
    const btn = document.getElementById('btnKhidmah');
    const wellInner = document.getElementById('wellInner');
    const totalEl = document.getElementById('totalAmount');
    
    // Parse initial amount (simplistic logic for visual demo)
    let currentTotal = 84.5;

    btn.addEventListener('click', (e) => {
        // 1. Button press effect
        gsap.to(btn, { scale: 0.95, duration: 0.1, yoyo: true, repeat: 1 });

        // 2. Create particle (Coin of Light)
        const particle = document.createElement('div');
        particle.classList.add('particle');
        document.body.appendChild(particle);

        // Get coordinates
        const btnRect = btn.getBoundingClientRect();
        const wellRect = wellInner.getBoundingClientRect();

        // Start from button center
        const startX = btnRect.left + btnRect.width / 2;
        const startY = btnRect.top + btnRect.height / 2;

        // End at well center
        const endX = wellRect.left + wellRect.width / 2;
        const endY = wellRect.top + wellRect.height / 2;

        gsap.set(particle, { x: startX, y: startY });

        // Arc animation using bezier-like motion via separate X/Y eases
        gsap.to(particle, {
            duration: 0.8,
            x: endX,
            ease: "power1.inOut"
        });
        
        gsap.to(particle, {
            duration: 0.8,
            y: endY,
            ease: "back.in(1.5)",
            onComplete: () => {
                particle.remove();
                
                // 3. Well Glows Up
                wellInner.classList.add('glow');
                setTimeout(() => wellInner.classList.remove('glow'), 500);

                // 4. Increase counter
                currentTotal += 0.1; // Add 100 million for visual demo
                
                // Counter animation
                gsap.to(totalEl, { 
                    scale: 1.2, 
                    color: "#fff", 
                    textShadow: "0 0 40px #fff",
                    duration: 0.2, 
                    yoyo: true, 
                    repeat: 1 
                });
                totalEl.innerText = "Rp " + currentTotal.toFixed(1) + "M";
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
