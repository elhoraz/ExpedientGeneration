<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>The Oracle's Vision - Analisis Aura</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --oracle-gold: #d4af37;
            --oracle-dark: #050a08;
        }
        
        * { box-sizing: border-box; }
        
        body {
            margin: 0; padding: 0;
            background-color: var(--oracle-dark);
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        .btn-back-vault {
            position: absolute; top: 30px; left: 30px; z-index: 100;
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; background: rgba(0,0,0,0.6);
            border: 1px solid rgba(212,175,55,0.3); border-radius: 8px;
            color: #d4af37; font-size: 11px; font-weight: 600; 
            letter-spacing: 3px; text-decoration: none; text-transform: uppercase;
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease; cursor: pointer;
        }
        .btn-back-vault:hover {
            transform: translateX(-5px); box-shadow: 0 0 20px rgba(212,175,55,0.5); border-color: #ffd700; color: #fff;
        }

        .oracle-wrapper {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Video Feed Container */
        .vision-container {
            position: relative;
            width: clamp(280px, 80vw, 450px);
            aspect-ratio: 3/4;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.9), 0 0 40px rgba(212,175,55,0.1);
            border: 1px solid rgba(212,175,55,0.3);
            background: #000;
            z-index: 10;
            transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }

        #videoFeed {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1); /* Mirror effect */
            filter: contrast(1.1) brightness(0.9);
            opacity: 0;
            transition: opacity 1s;
        }

        #captureCanvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            z-index: 5;
            transform: scaleX(-1); /* Mirror canvas to match video */
        }

        /* Holographic Logo Overlay */
        .logo-reticle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            opacity: 0.15;
            z-index: 20;
            pointer-events: none;
            filter: drop-shadow(0 0 10px rgba(212,175,55,0.5));
            transition: all 1s;
        }
        
        .scanning .logo-reticle {
            opacity: 0.8;
            filter: drop-shadow(0 0 25px rgba(212,175,55,1));
            animation: pulseReticle 2s infinite alternate;
        }

        @keyframes pulseReticle {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            100% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.9; }
        }

        /* Scanner Line */
        .scanner-laser {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--oracle-gold);
            box-shadow: 0 0 20px 10px rgba(212,175,55,0.4);
            z-index: 25;
            display: none;
        }

        /* HUD Elements */
        .hud-text {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            font-family: 'Courier New', monospace;
            color: var(--oracle-gold);
            font-size: 0.8rem;
            letter-spacing: 3px;
            z-index: 30;
            text-shadow: 0 0 10px rgba(0,0,0,0.8);
            opacity: 0;
        }

        /* Controls */
        .controls-panel {
            margin-top: 40px;
            text-align: center;
            z-index: 15;
        }

        .btn-initiate {
            background: rgba(212, 175, 55, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid var(--oracle-gold);
            color: var(--oracle-gold);
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 4px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .btn-initiate:hover {
            background: var(--oracle-gold);
            color: #000;
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-initiate:disabled {
            opacity: 0.5;
            pointer-events: none;
            border-color: #555;
            color: #555;
        }

        /* Final Result Overlay */
        .result-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
            z-index: 40;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 1s ease;
        }

        .result-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .aura-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: #fff;
            text-shadow: 0 0 30px rgba(212,175,55,0.8);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            transform: translateY(30px);
            opacity: 0;
        }

        .aura-desc {
            font-family: 'Courier New', monospace;
            color: #ddd;
            font-size: 0.8rem;
            letter-spacing: 2px;
            margin-top: 10px;
            text-align: center;
            padding: 0 20px;
            transform: translateY(20px);
            opacity: 0;
        }

        .watermark-logo {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 60px;
            opacity: 0.8;
            filter: drop-shadow(0 0 5px rgba(212,175,55,0.5));
        }

        /* Camera Permission Notice */
        .permission-notice {
            position: absolute;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            color: #777;
            font-size: 0.8rem;
            text-align: center;
            letter-spacing: 2px;
            text-transform: uppercase;
            width: 80%;
        }
        
        .btn-reset {
            margin-top: 25px;
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: underline;
            display: none;
        }
        .btn-reset:hover { color: #fff; }
        
        @media (max-width: 768px) {
            .btn-back-vault { top: 20px; left: 20px; padding: 8px 15px; font-size: 10px; }
        }
    </style>
</head>
<body>

    <a href="/fitur" class="btn-back-vault">
        <i class="fa-solid fa-chevron-left"></i> Exit Vision
    </a>

    <div class="oracle-wrapper">
        <div class="vision-container" id="visionContainer">
            <div class="permission-notice" id="permNotice">Menunggu Izin Kamera...</div>
            <video id="videoFeed" autoplay playsinline></video>
            <canvas id="captureCanvas"></canvas>
            
            <img src="/images/logo-utuh.png" class="logo-reticle" id="logoReticle" alt="Scanner Reticle">
            <div class="scanner-laser" id="scannerLaser"></div>
            <div class="hud-text" id="hudText">SYSTEM STANDBY</div>
            
            <!-- The Result Overlay -->
            <div class="result-overlay" id="resultOverlay">
                <h2 class="aura-title" id="auraTitle">The Tycoon</h2>
                <div class="aura-desc" id="auraDesc">Resonansi Dominan. Kepemimpinan Absolut.</div>
                <img src="/images/logo-utuh.png" class="watermark-logo">
            </div>
        </div>

        <div class="controls-panel">
            <button class="btn-initiate hover-trigger" id="btnScan" disabled>
                <i class="fa-solid fa-eye"></i> Inisiasi Visi
            </button>
            <button class="btn-reset hover-trigger" id="btnReset">Pindai Ulang</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const video = document.getElementById('videoFeed');
        const canvas = document.getElementById('captureCanvas');
        const ctx = canvas.getContext('2d');
        const btnScan = document.getElementById('btnScan');
        const btnReset = document.getElementById('btnReset');
        const permNotice = document.getElementById('permNotice');
        const visionContainer = document.getElementById('visionContainer');
        const logoReticle = document.getElementById('logoReticle');
        const scannerLaser = document.getElementById('scannerLaser');
        const hudText = document.getElementById('hudText');
        const resultOverlay = document.getElementById('resultOverlay');
        
        let stream = null;

        // Auras Database (Gamified) — 30 Aura untuk 124 entitas
        const auras = [
            { title: "The Tycoon", desc: "Resonansi Dominan. Insting Bisnis Tajam.", color: "rgba(212, 175, 55, 0.4)", filter: "sepia(0.6) hue-rotate(-10deg) brightness(0.8) contrast(1.2)" },
            { title: "The Diplomat", desc: "Aura Tenang. Karisma Negosiator Kelas Atas.", color: "rgba(0, 162, 255, 0.4)", filter: "sepia(0.5) hue-rotate(180deg) brightness(0.9) contrast(1.1)" },
            { title: "The Architect", desc: "Pemikir Visioner. Struktur Logika Sempurna.", color: "rgba(0, 255, 136, 0.4)", filter: "sepia(0.3) hue-rotate(90deg) brightness(0.9) contrast(1.3)" },
            { title: "The Sovereign", desc: "Entitas Puncak. Wibawa Tak Terbantahkan.", color: "rgba(170, 119, 28, 0.5)", filter: "sepia(0.8) contrast(1.4) brightness(0.7)" },
            { title: "The Enigma", desc: "Sulit Dibaca. Menarik Perhatian dari Bayangan.", color: "rgba(138, 43, 226, 0.4)", filter: "grayscale(1) contrast(1.5) brightness(0.8)" },
            { title: "The Strategist", desc: "Otak Perang. Selalu Tiga Langkah di Depan.", color: "rgba(0, 200, 200, 0.4)", filter: "sepia(0.4) hue-rotate(150deg) brightness(0.85) contrast(1.2)" },
            { title: "The Pioneer", desc: "Penjelajah Batas. Membuka Jalan yang Tak Ada.", color: "rgba(255, 140, 0, 0.4)", filter: "sepia(0.5) hue-rotate(20deg) brightness(0.85) contrast(1.3)" },
            { title: "The Sentinel", desc: "Penjaga Gerbang. Loyalitas Tanpa Batas.", color: "rgba(100, 149, 237, 0.4)", filter: "sepia(0.4) hue-rotate(200deg) brightness(0.8) contrast(1.2)" },
            { title: "The Alchemist", desc: "Pengubah Keadaan. Menyulap Krisis Jadi Emas.", color: "rgba(255, 215, 0, 0.5)", filter: "sepia(0.7) hue-rotate(-5deg) brightness(0.75) contrast(1.4)" },
            { title: "The Phoenix", desc: "Bangkit dari Abu. Tak Pernah Benar-benar Kalah.", color: "rgba(255, 69, 0, 0.4)", filter: "sepia(0.6) hue-rotate(10deg) brightness(0.8) contrast(1.3)" },
            { title: "The Phantom", desc: "Bergerak Tanpa Jejak. Hadir Tanpa Diundang.", color: "rgba(50, 50, 80, 0.5)", filter: "grayscale(0.8) brightness(0.6) contrast(1.6)" },
            { title: "The Oracle", desc: "Menatap Masa Depan. Intuisi Setajam Pedang.", color: "rgba(180, 100, 255, 0.4)", filter: "sepia(0.3) hue-rotate(260deg) brightness(0.85) contrast(1.2)" },
            { title: "The Catalyst", desc: "Pemicu Reaksi. Kehadirannya Mengubah Segalanya.", color: "rgba(0, 255, 200, 0.4)", filter: "sepia(0.2) hue-rotate(120deg) brightness(0.9) contrast(1.2)" },
            { title: "The Vanguard", desc: "Garis Depan. Memimpin Saat Yang Lain Ragu.", color: "rgba(200, 50, 50, 0.4)", filter: "sepia(0.5) hue-rotate(350deg) brightness(0.8) contrast(1.3)" },
            { title: "The Sage", desc: "Bijaksana Melampaui Usia. Kata-katanya Adalah Hukum.", color: "rgba(120, 180, 120, 0.4)", filter: "sepia(0.4) hue-rotate(80deg) brightness(0.85) contrast(1.1)" },
            { title: "The Rogue", desc: "Melanggar Aturan dengan Gaya. Pemberontak Elegan.", color: "rgba(220, 20, 60, 0.4)", filter: "sepia(0.5) hue-rotate(340deg) brightness(0.8) contrast(1.4)" },
            { title: "The Luminary", desc: "Cahaya di Kegelapan. Menginspirasi Tanpa Bicara.", color: "rgba(255, 255, 200, 0.3)", filter: "sepia(0.3) brightness(1.1) contrast(1.1)" },
            { title: "The Titan", desc: "Kekuatan Fisik & Mental. Pilar Tak Tergoyahkan.", color: "rgba(100, 80, 60, 0.5)", filter: "sepia(0.7) brightness(0.7) contrast(1.5)" },
            { title: "The Nomad", desc: "Jiwa Bebas. Menemukan Rumah di Mana Saja.", color: "rgba(139, 195, 74, 0.4)", filter: "sepia(0.3) hue-rotate(70deg) brightness(0.9) contrast(1.2)" },
            { title: "The Warden", desc: "Pelindung Warisan. Menjaga yang Sakral.", color: "rgba(75, 0, 130, 0.4)", filter: "sepia(0.5) hue-rotate(280deg) brightness(0.8) contrast(1.2)" },
            { title: "The Artisan", desc: "Pencipta Keindahan. Menyentuh Dunia Lewat Karya.", color: "rgba(255, 160, 200, 0.4)", filter: "sepia(0.4) hue-rotate(320deg) brightness(0.9) contrast(1.1)" },
            { title: "The Tempest", desc: "Badai yang Mengubah Lanskap. Tak Bisa Dihentikan.", color: "rgba(0, 100, 200, 0.5)", filter: "sepia(0.4) hue-rotate(190deg) brightness(0.75) contrast(1.4)" },
            { title: "The Harbinger", desc: "Pembawa Kabar. Tanda-tanda Mengikuti Langkahnya.", color: "rgba(80, 80, 80, 0.5)", filter: "grayscale(0.6) brightness(0.7) contrast(1.3)" },
            { title: "The Monarch", desc: "Darah Biru. Keagungan dalam Setiap Gerak.", color: "rgba(160, 120, 200, 0.4)", filter: "sepia(0.5) hue-rotate(270deg) brightness(0.85) contrast(1.2)" },
            { title: "The Ember", desc: "Api yang Tak Pernah Padam. Hangat dan Berbahaya.", color: "rgba(255, 100, 50, 0.4)", filter: "sepia(0.6) hue-rotate(5deg) brightness(0.8) contrast(1.3)" },
            { title: "The Cipher", desc: "Kode Hidup. Hanya Sedikit yang Bisa Memahaminya.", color: "rgba(0, 180, 180, 0.4)", filter: "sepia(0.3) hue-rotate(160deg) brightness(0.85) contrast(1.3)" },
            { title: "The Revenant", desc: "Kembali dari Ketiadaan. Lebih Kuat dari Sebelumnya.", color: "rgba(40, 40, 60, 0.5)", filter: "grayscale(0.9) brightness(0.65) contrast(1.5)" },
            { title: "The Meridian", desc: "Titik Keseimbangan. Harmoni Sempurna dalam Kekacauan.", color: "rgba(100, 200, 255, 0.3)", filter: "sepia(0.2) hue-rotate(185deg) brightness(0.95) contrast(1.1)" },
            { title: "The Apex", desc: "Puncak dari Segalanya. Tidak Ada yang Lebih Tinggi.", color: "rgba(212, 175, 55, 0.6)", filter: "sepia(0.9) brightness(0.65) contrast(1.5)" },
            { title: "The Nexus", desc: "Titik Temu. Menghubungkan Dunia yang Terputus.", color: "rgba(0, 255, 100, 0.3)", filter: "sepia(0.2) hue-rotate(100deg) brightness(0.9) contrast(1.2)" }
        ];

        // Initialize Camera
        async function initCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
                video.srcObject = stream;
                
                video.onloadedmetadata = () => {
                    video.style.opacity = 1;
                    permNotice.style.display = 'none';
                    btnScan.disabled = false;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                };
            } catch (err) {
                console.error(err);
                permNotice.innerHTML = "AKSES KAMERA DITOLAK.<br>Mohon berikan izin pada browser.";
                permNotice.style.color = "#ff3366";
            }
        }

        initCamera();

        let scanAnim;
        
        // Scan Sequence
        btnScan.addEventListener('click', () => {
            btnScan.style.display = 'none';
            
            // 1. Prepare UI
            visionContainer.classList.add('scanning');
            scannerLaser.style.display = 'block';
            hudText.style.opacity = 1;
            
            // Haptic feedback
            if (navigator.vibrate) navigator.vibrate([50, 50, 50]);

            // 2. Animate Laser (GSAP)
            scanAnim = gsap.fromTo(scannerLaser, 
                { y: 0, opacity: 0 }, 
                { y: visionContainer.offsetHeight, opacity: 1, duration: 1.2, ease: "linear", repeat: -1, yoyo: true }
            );

            // 3. Fake Processing Texts
            const messages = [
                "MENYELARASKAN MATRIKS WAJAH...",
                "MENGEKSTRAKSI TINGKAT KARISMA...",
                "MENGUJI RESONANSI EKSEKUTIF...",
                "MENCOCOKKAN ARSIP VVIP..."
            ];
            
            let msgIndex = 0;
            hudText.innerText = messages[0];
            const msgInterval = setInterval(() => {
                msgIndex++;
                if(msgIndex < messages.length) {
                    hudText.innerText = messages[msgIndex];
                }
            }, 800);

            // 4. Capture & Result
            setTimeout(() => {
                clearInterval(msgInterval);
                scanAnim.kill();
                scannerLaser.style.display = 'none';
                visionContainer.classList.remove('scanning');
                hudText.innerText = "AURA TERVERIFIKASI";
                if (navigator.vibrate) navigator.vibrate([100, 50, 100]);

                // Select random aura
                const selectedAura = auras[Math.floor(Math.random() * auras.length)];
                
                // Draw to canvas and apply filter
                ctx.filter = selectedAura.filter;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Draw a color overlay
                ctx.fillStyle = selectedAura.color;
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Show canvas, hide video
                canvas.style.display = 'block';
                video.style.opacity = 0;
                logoReticle.style.opacity = 0;

                // Show Result Overlay
                document.getElementById('auraTitle').innerText = selectedAura.title;
                document.getElementById('auraDesc').innerText = selectedAura.desc;
                document.getElementById('auraTitle').style.color = (selectedAura.title === "The Sovereign") ? "#d4af37" : "#fff";
                
                resultOverlay.classList.add('show');
                gsap.to('.aura-title', { opacity: 1, y: 0, duration: 1, ease: "power3.out", delay: 0.3 });
                gsap.to('.aura-desc', { opacity: 1, y: 0, duration: 1, ease: "power3.out", delay: 0.6 });
                
                btnReset.style.display = 'inline-block';
                
            }, 3200); // 3.2s scan time
        });

        // Reset
        btnReset.addEventListener('click', () => {
            canvas.style.display = 'none';
            video.style.opacity = 1;
            logoReticle.style.opacity = 0.15;
            resultOverlay.classList.remove('show');
            gsap.set('.aura-title', { opacity: 0, y: 30 });
            gsap.set('.aura-desc', { opacity: 0, y: 20 });
            hudText.style.opacity = 0;
            btnReset.style.display = 'none';
            btnScan.style.display = 'inline-block';
        });
    });
    </script>
</body>
</html>
