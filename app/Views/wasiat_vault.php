<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Amanah & Wasiat - The Legacy Vault
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --vault-steel: #0a0b0a;
        --bg-dark: #010201;
        --danger-red: #8b0000;
        --neon-red: #ff3333;
        --neon-green: #00ff88;
    }

    body {
        background-color: var(--bg-dark);
        background-image: repeating-linear-gradient(
            0deg,
            rgba(0, 0, 0, 0.1),
            rgba(0, 0, 0, 0.1) 1px,
            transparent 1px,
            transparent 2px
        ); /* Scanline effect */
        font-family: 'Courier New', monospace; /* Monospace for vault feel */
        color: #fff;
        min-height: 100vh;
    }

    .vault-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
        z-index: 10;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* HEADER */
    .vault-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 50px;
        border-bottom: 1px solid rgba(212,175,55,0.2);
        padding-bottom: 20px;
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
        font-family: 'Inter', sans-serif;
    }
    .btn-back:hover {
        color: #fff;
        text-shadow: 0 0 10px var(--gold-main);
        transform: translateX(-5px);
    }

    .header-titles {
        text-align: right;
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--danger-red);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 5px;
        text-shadow: 0 0 20px rgba(139,0,0,0.8);
    }
    .status-badge {
        font-size: 0.7rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--neon-red);
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .blink-dot {
        width: 8px; height: 8px; background: var(--neon-red); border-radius: 50%;
        animation: blink 1.5s infinite;
    }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.2; } }

    /* CONTENT GRID */
    .wasiat-list {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    /* LOCKBOX CARD */
    .lockbox {
        background: var(--vault-steel);
        border: 2px solid #1a1a1a;
        border-left: 4px solid var(--danger-red);
        padding: 30px;
        position: relative;
        box-shadow: inset 0 0 50px rgba(0,0,0,0.8), 0 10px 30px rgba(0,0,0,0.9);
        overflow: hidden;
    }
    .lockbox::before {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.02) 45%, transparent 50%);
        pointer-events: none;
    }

    .lockbox-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
        padding-bottom: 15px;
    }

    .box-id {
        color: var(--gold-dark);
        font-size: 0.8rem;
        letter-spacing: 3px;
        margin-bottom: 5px;
    }
    .box-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #fff;
        margin: 0;
    }
    .box-meta {
        text-align: right;
        font-size: 0.7rem;
        color: var(--text-muted);
        letter-spacing: 1px;
    }

    /* SECRET TEXT AREA */
    .secret-content {
        background: #000;
        padding: 20px;
        border: 1px solid #222;
        border-radius: 4px;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #555; /* Dim color for encrypted state */
        position: relative;
        min-height: 100px;
        margin-bottom: 20px;
    }

    .scramble-text {
        word-break: break-all;
        filter: blur(1px);
        transition: all 0.5s;
    }

    /* UNLOCK BUTTON */
    .btn-unlock {
        background: transparent;
        color: var(--danger-red);
        border: 1px solid var(--danger-red);
        padding: 10px 25px;
        font-size: 0.8rem;
        font-family: 'Courier New', monospace;
        letter-spacing: 3px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-unlock:hover {
        background: rgba(139,0,0,0.1);
        box-shadow: 0 0 15px rgba(139,0,0,0.3);
    }

    /* SCAN OVERLAY (Full Screen) */
    .scan-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95);
        z-index: 100;
        display: none; /* hidden by default */
        flex-direction: column;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(10px);
    }

    .fingerprint-scanner {
        width: 150px; height: 150px;
        border: 2px solid var(--danger-red);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        display: flex; justify-content: center; align-items: center;
        box-shadow: 0 0 50px rgba(139,0,0,0.2);
    }
    .fingerprint-icon {
        font-size: 5rem;
        color: rgba(139,0,0,0.3);
        transition: color 0.5s;
    }
    .scan-line {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 5px;
        background: var(--danger-red);
        box-shadow: 0 0 20px var(--danger-red);
        opacity: 0;
    }

    .scan-text {
        margin-top: 30px;
        color: var(--danger-red);
        font-size: 1rem;
        letter-spacing: 5px;
        text-transform: uppercase;
    }

    /* UNLOCKED STATE */
    .lockbox.unlocked {
        border-color: var(--neon-green);
        border-left-color: var(--neon-green);
    }
    .lockbox.unlocked .scramble-text {
        filter: blur(0);
        color: #fff;
        font-family: 'Inter', sans-serif;
    }
    .lockbox.unlocked .btn-unlock {
        display: none;
    }
    .lockbox.unlocked .box-id {
        color: var(--neon-green);
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="vault-wrapper">
    <header class="vault-header">
        <a href="/fitur" class="btn-back">
            <i class="fa-solid fa-chevron-left"></i> Abort / Kembali
        </a>
        <div class="header-titles">
            <h1 class="page-title">Amanah & Wasiat</h1>
            <div class="status-badge"><div class="blink-dot"></div> Restricted Access Area</div>
        </div>
    </header>

    <div class="wasiat-list">
        
        <!-- Lockbox 1 -->
        <div class="lockbox" id="box-1">
            <div class="lockbox-header">
                <div>
                    <div class="box-id">FILE.ID: WST-9002-A</div>
                    <h2 class="box-title">Wasiat Eksekutif (Bpk. Ridwan)</h2>
                </div>
                <div class="box-meta">
                    <div>ENCRYPTED: SHA-256</div>
                    <div>SIZE: 2.4 KB</div>
                </div>
            </div>
            <div class="secret-content">
                <div class="scramble-text" id="text-1">
                    #@$*! 0x8F9A2B &^% SDFJH ^&*( LDFK #$ @! KLDF 0x00A1 99SDF *(^ KLJSDF 908 SDF LKJ #$ (*& SDF KLJ 908 SDF LKJ #$ (*& SDF KLJ 908 SDF LKJ #$ (*& SDF KLJ 908 SDF LKJ #$ (*& SDF KLJ 908 SDF LKJ #$ (*& SDF LKJ 908 SDF LKJ #$ (*& SDF KLJ 908 SDF LKJ #$ (*& SDF KLJ 908
                </div>
            </div>
            <button class="btn-unlock" onclick="initiateUnlock('box-1', 'text-1')">
                <i class="fa-solid fa-fingerprint"></i> Dekripsi Pesan
            </button>
        </div>

        <!-- Lockbox 2 -->
        <div class="lockbox" id="box-2">
            <div class="lockbox-header">
                <div>
                    <div class="box-id">FILE.ID: AMN-404-X</div>
                    <h2 class="box-title">Amanah Angkatan (Protokol Darurat)</h2>
                </div>
                <div class="box-meta">
                    <div>ENCRYPTED: AES-512</div>
                    <div>SIZE: 1.1 KB</div>
                </div>
            </div>
            <div class="secret-content">
                <div class="scramble-text" id="text-2">
                    &*( HJGK 0x99B2 &^% MNBV ^&*( YUIO #$ @! QWER 0x01C3 77HJK *(^ ZXCV 102 MNB LKJ #$ (*& POI UYT 456 MNB VCX #$ (*& LKH JGF 789 DSA EWQ #$ (*& POI UYT 456 MNB VCX #$ (*& LKH JGF 789 DSA EWQ #$ (*& POI UYT 456 MNB VCX #$ (*& LKH JGF 789 DSA EWQ
                </div>
            </div>
            <button class="btn-unlock" onclick="initiateUnlock('box-2', 'text-2')">
                <i class="fa-solid fa-fingerprint"></i> Dekripsi Pesan
            </button>
        </div>

    </div>
</div>

<!-- Scan Overlay -->
<div class="scan-overlay" id="scanOverlay">
    <div class="fingerprint-scanner">
        <i class="fa-solid fa-fingerprint fingerprint-icon" id="fpIcon"></i>
        <div class="scan-line" id="scanLine"></div>
    </div>
    <div class="scan-text" id="scanText">Meminta Akses Biometrik...</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Intro
    gsap.from(".lockbox", { y: 50, opacity: 0, duration: 0.8, stagger: 0.3, ease: "power2.out" });
});

// Decrypted actual texts
const secretData = {
    'text-1': "Assalamu'alaikum. Jika pesan ini otomatis terbuka, berarti sistem mendeteksi saya tidak aktif selama 30 hari. Tolong hubungi keluarga saya di nomor 0812-XXXX-XXXX dan cairkan dana darurat Ukhuwah sebesar Rp 15 Juta yang telah saya titipkan di Baitul Maal untuk biaya sekolah anak saya. Terima kasih saudaraku.",
    'text-2': "PROTOKOL ALPHA. Seluruh kunci server utama telah dipindahkan ke brankas fisik di lokasi B-4. Hanya anggota dengan Clearance Level 5 yang diizinkan mengambilnya. Jangan beritahukan hal ini kepada pihak eksternal."
};

let currentBoxId = '';
let currentTextId = '';

function initiateUnlock(boxId, textId) {
    currentBoxId = boxId;
    currentTextId = textId;
    
    const overlay = document.getElementById('scanOverlay');
    const scanLine = document.getElementById('scanLine');
    const fpIcon = document.getElementById('fpIcon');
    const scanText = document.getElementById('scanText');

    overlay.style.display = 'flex';
    gsap.fromTo(overlay, {opacity: 0}, {opacity: 1, duration: 0.5});

    scanText.innerText = "MENGOTENTIKASI...";
    scanText.style.color = "var(--danger-red)";
    fpIcon.style.color = "rgba(139,0,0,0.5)";

    // Scan Animation
    gsap.to(scanLine, {
        top: "100%",
        opacity: 1,
        duration: 1.5,
        yoyo: true,
        repeat: 1,
        ease: "linear",
        onComplete: () => {
            // Success State
            scanText.innerText = "AKSES DIBERIKAN";
            scanText.style.color = "var(--neon-green)";
            fpIcon.style.color = "var(--neon-green)";
            
            setTimeout(() => {
                // Hide Overlay
                gsap.to(overlay, {opacity: 0, duration: 0.5, onComplete: () => {
                    overlay.style.display = 'none';
                    decryptBox(currentBoxId, currentTextId);
                }});
            }, 1000);
        }
    });
}

function decryptBox(boxId, textId) {
    const box = document.getElementById(boxId);
    const textEl = document.getElementById(textId);
    const finalString = secretData[textId];
    
    box.classList.add('unlocked');
    
    // Matrix style decoding effect
    let iterations = 0;
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#$%&*";
    
    let interval = setInterval(() => {
        textEl.innerText = finalString.split("").map((letter, index) => {
            if(index < iterations) {
                return finalString[index];
            }
            return letters[Math.floor(Math.random() * 42)];
        }).join("");
        
        if(iterations >= finalString.length){ 
            clearInterval(interval);
        }
        iterations += 2; // speed
    }, 20);
}

</script>
<?= $this->endSection() ?>
