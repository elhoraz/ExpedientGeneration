<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Sovereign Scanner - Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-main: #d4af37;
        --bg-dark: #010402;
        --glass-bg: rgba(10, 15, 12, 0.7);
        --glass-border: rgba(212, 175, 55, 0.2);
    }
    
    body {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at 50% 0%, rgba(212,175,55,0.1) 0%, transparent 70%);
        color: #fff;
    }

    .scanner-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 80px);
        padding: 40px 20px;
        position: relative;
    }

    .scanner-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .scanner-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--gold-main);
        margin: 0 0 10px 0;
        letter-spacing: 2px;
    }

    .scanner-subtitle {
        color: #8899a6;
        font-size: 0.95rem;
        letter-spacing: 1px;
    }

    .scanner-container {
        width: 100%;
        max-width: 500px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 20px;
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5), inset 0 0 20px rgba(212,175,55,0.05);
        position: relative;
        overflow: hidden;
    }

    #reader {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
    }

    #reader video {
        border-radius: 10px;
        object-fit: cover;
    }

    #reader img[alt="Info icon"] {
        display: none !important;
    }
    
    #reader__dashboard_section_csr span {
        color: #d4af37 !important;
    }

    #reader__dashboard_section_csr button {
        background: rgba(212,175,55,0.2) !important;
        border: 1px solid var(--gold-main) !important;
        color: var(--gold-main) !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: all 0.3s !important;
    }
    #reader__dashboard_section_csr button:hover {
        background: var(--gold-main) !important;
        color: #000 !important;
    }

    .scanner-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        border: 2px solid transparent;
        border-radius: 20px;
        transition: border-color 0.3s;
        z-index: 10;
    }
    .scanner-overlay.active {
        border-color: #00ff88;
        box-shadow: inset 0 0 30px rgba(0,255,136,0.3);
    }

    .btn-back-scanner {
        margin-top: 30px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--gold-main);
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 30px;
        border: 1px solid rgba(212,175,55,0.3);
        background: rgba(0,0,0,0.4);
        transition: all 0.3s;
    }
    
    .btn-back-scanner:hover {
        background: rgba(212,175,55,0.1);
        transform: translateY(-3px);
    }

    /* Target line animation */
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--gold-main);
        box-shadow: 0 0 10px var(--gold-main), 0 0 20px var(--gold-main);
        animation: scanDown 3s linear infinite;
        opacity: 0.7;
        z-index: 5;
        pointer-events: none;
        display: none;
    }

    @keyframes scanDown {
        0% { top: 0; }
        50% { top: 100%; }
        100% { top: 0; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="scanner-wrapper">
    <div class="scanner-header">
        <h1 class="scanner-title">Sovereign Scanner</h1>
        <div class="scanner-subtitle">Pindai KTA untuk Verifikasi Identitas Entitas</div>
    </div>

    <div class="scanner-container" id="scannerContainer">
        <div class="scan-line" id="scanLine"></div>
        <div class="scanner-overlay" id="scannerOverlay"></div>
        <div id="reader"></div>
    </div>

    <a href="/fitur" class="btn-back-scanner">
        <i class="fa-solid fa-chevron-left"></i> Kembali ke Vault
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let html5QrcodeScanner = null;
    let isScanning = false;
    
    function onScanSuccess(decodedText, decodedResult) {
        if (isScanning) return; 
        isScanning = true;
        
        // Visual feedback
        document.getElementById('scannerOverlay').classList.add('active');
        document.getElementById('scanLine').style.display = 'none';
        
        if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
        
        // Cek jika outputnya adalah URL
        if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
            window.location.href = decodedText;
        } else {
            alert('Format KTA tidak dikenali: ' + decodedText);
            setTimeout(() => { 
                isScanning = false; 
                document.getElementById('scannerOverlay').classList.remove('active');
                document.getElementById('scanLine').style.display = 'block';
            }, 3000);
        }
        
        if (html5QrcodeScanner) html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
        // Abaikan error rutin saat kamera belum menangkap QR code
    }

    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    // Kustomisasi UI bawaan (hilangkan tulisan/link yang mengganggu)
    const observer = new MutationObserver((mutations) => {
        const scanLine = document.getElementById('scanLine');
        const stopBtn = document.getElementById('html5-qrcode-button-camera-stop');
        if (stopBtn) {
            scanLine.style.display = 'block';
        } else {
            scanLine.style.display = 'none';
        }
        
        const aTags = document.querySelectorAll('#reader a');
        aTags.forEach(tag => { tag.style.display = 'none'; });
    });
    
    observer.observe(document.getElementById('reader'), { childList: true, subtree: true });
});
</script>
<?= $this->endSection() ?>
