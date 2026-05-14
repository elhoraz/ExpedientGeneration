<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biometric Security - Expedient</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+New&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body, html {
            margin: 0; padding: 0; width: 100%; height: 100%;
            background-color: #050505; color: #fff;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            font-family: 'Courier New', monospace; overflow: hidden;
            user-select: none; /* Mencegah user menyeleksi teks saat menahan klik */
        }

        /* Latar Belakang Garis-Garis Matrix */
        .grid-bg {
            position: absolute; inset: 0; z-index: 0; opacity: 0.1;
            background-image: linear-gradient(#d4af37 1px, transparent 1px), linear-gradient(90deg, #d4af37 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .auth-container {
            position: relative; z-index: 10; text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 30px;
        }

        .auth-title { color: #d4af37; font-size: 1.2rem; letter-spacing: 5px; text-transform: uppercase; margin: 0; }
        .auth-status { font-size: 0.85rem; color: #aaa; letter-spacing: 2px; height: 20px; }

        /* KOTAK PEMINDAI SIDIK JARI */
        .scanner-box {
            position: relative; width: 150px; height: 180px;
            border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px;
            display: flex; justify-content: center; align-items: center;
            cursor: pointer; overflow: hidden; transition: 0.3s;
            background: rgba(212, 175, 55, 0.05); box-shadow: 0 0 30px rgba(0,0,0,0.8);
        }

        /* Ikon Sidik Jari */
        .fingerprint-icon {
            font-size: 5rem; color: rgba(212, 175, 55, 0.4);
            transition: 0.3s; position: relative; z-index: 2;
        }

        /* Garis Laser Pemindai */
        .scan-laser {
            position: absolute; top: 0; left: 0; width: 100%; height: 3px;
            background: #00ff88; box-shadow: 0 0 15px #00ff88, 0 0 30px #00ff88;
            opacity: 0; z-index: 5;
        }

        /* Animasi Laser */
        @keyframes scanAnim {
            0% { top: 0; }
            50% { top: 100%; }
            100% { top: 0; }
        }

        /* Lingkaran Progress Bar */
        .progress-ring {
            position: absolute; width: 220px; height: 220px;
            border-radius: 50%; border: 2px dashed rgba(212, 175, 55, 0.2);
            z-index: 1; transition: 0.2s;
        }
        
        /* STATE: SAAT DITEKAN (SCANNING) */
        .scanner-box.scanning { border-color: #00ff88; box-shadow: 0 0 40px rgba(0, 255, 136, 0.2); }
        .scanner-box.scanning .fingerprint-icon { color: #00ff88; filter: drop-shadow(0 0 10px #00ff88); }
        .scanner-box.scanning .scan-laser { opacity: 1; animation: scanAnim 1.5s linear infinite; }
        .progress-ring.scanning { border-color: #00ff88; animation: spin 4s linear infinite; }

        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* STATE: ACCESS GRANTED */
        .granted-overlay {
            position: fixed; inset: 0; background: rgba(0, 255, 136, 0.1);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            z-index: 999; opacity: 0; pointer-events: none; transition: 0.5s ease;
        }
        .granted-overlay.active { opacity: 1; pointer-events: auto; background: #000; }
        .granted-text {
            color: #00ff88; font-size: 3rem; font-family: 'Playfair Display', serif; font-weight: 900;
            letter-spacing: 10px; text-shadow: 0 0 20px #00ff88;
        }
        .granted-sub { color: #fff; font-family: 'Inter', sans-serif; letter-spacing: 3px; margin-top: 10px; font-size: 0.9rem; }

        /* Tombol Batal */
        .btn-cancel {
            margin-top: 20px; background: none; border: 1px solid rgba(255,255,255,0.2); color: #aaa;
            padding: 10px 20px; border-radius: 50px; font-size: 0.7rem; font-family: 'Inter', sans-serif;
            letter-spacing: 2px; cursor: pointer; text-decoration: none; transition: 0.3s;
        }
        .btn-cancel:hover { background: rgba(255,51,102,0.1); border-color: #ff3366; color: #ff3366; }
    </style>
</head>
<body>

    <div class="grid-bg"></div>

    <div class="auth-container">
        <h2 class="auth-title">Otorisasi Agen</h2>
        <div class="auth-status" id="statusText">AWAITING BIOMETRIC INPUT</div>

        <div class="progress-ring" id="progressRing"></div>

        <div class="scanner-box" id="scannerBox">
            <i class="fa-solid fa-fingerprint fingerprint-icon"></i>
            <div class="scan-laser"></div>
        </div>

        <p style="font-size: 0.7rem; color: #666; letter-spacing: 2px;">TAHAN UNTUK MEMINDAI SIDIK JARI</p>

        <a href="<?= base_url('beranda') ?>" class="btn-cancel">BATALKAN OTORISASI</a>
    </div>

    <div class="granted-overlay" id="grantedOverlay">
        <div class="granted-text">ACCESS GRANTED</div>
        <div class="granted-sub">MEMUAT PROFIL ANDA...</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scannerBox = document.getElementById('scannerBox');
            const statusText = document.getElementById('statusText');
            const progressRing = document.getElementById('progressRing');
            const grantedOverlay = document.getElementById('grantedOverlay');
            
            let scanTimer;
            let isScanning = false;
            let scanDuration = 2000; // 2 detik ditahan

            // Fungsi memulai scan
            const startScan = (e) => {
                // Mencegah klik kanan atau multi-touch aneh
                if(e.type === 'mousedown' && e.button !== 0) return; 

                isScanning = true;
                scannerBox.classList.add('scanning');
                progressRing.classList.add('scanning');
                statusText.innerText = "VERIFYING IDENTITY...";
                statusText.style.color = "#00ff88";

                if (navigator.vibrate) navigator.vibrate(50); // Getar di HP

                scanTimer = setTimeout(() => {
                    scanSuccess();
                }, scanDuration);
            };

            // Fungsi batal scan (dilepas sebelum 2 detik)
            const stopScan = () => {
                if (!isScanning) return;
                isScanning = false;
                clearTimeout(scanTimer);
                
                scannerBox.classList.remove('scanning');
                progressRing.classList.remove('scanning');
                statusText.innerText = "BIOMETRIC INPUT INTERRUPTED";
                statusText.style.color = "#ff3366";

                setTimeout(() => {
                    if(!isScanning) {
                        statusText.innerText = "AWAITING BIOMETRIC INPUT";
                        statusText.style.color = "#aaa";
                    }
                }, 1500);
            };

            // Fungsi Sukses
            const scanSuccess = () => {
                if (navigator.vibrate) navigator.vibrate([100, 50, 100]); // Getar sukses
                
                grantedOverlay.classList.add('active');
                
                // Lempar ke halaman profil setelah animasi sukses
                setTimeout(() => {
                    window.location.href = '<?= base_url('profil') ?>'; // <--- Tujuan akhirnya!
                }, 2000);
            };

            // Event Listeners untuk Desktop (Mouse) & Mobile (Touch)
            scannerBox.addEventListener('mousedown', startScan);
            window.addEventListener('mouseup', stopScan);
            
            scannerBox.addEventListener('touchstart', (e) => { e.preventDefault(); startScan(e); });
            window.addEventListener('touchend', stopScan);
        });
    </script>
</body>
</html>