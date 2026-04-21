<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Expedient Vault - Protokol Keamanan Tingkat Tinggi
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-light: #fff2cd;
        --gold-main: #d4af37;
        --gold-dark: #aa771c;
        --glass-bg: rgba(5, 10, 8, 0.4);
        --neon-green: #00ff88;
        --neon-blue: #00e5ff;
        --hud-color: var(--gold-main);
    }

    body {
        background-color: #010201;
        background-image: 
            radial-gradient(circle at 50% -20%, rgba(212,175,55,0.15) 0%, transparent 50%),
            linear-gradient(180deg, #050a08 0%, #010201 100%);
        overflow-x: hidden;
    }

    .vault-wrapper {
        position: relative; width: 100%; min-height: 100vh;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 20px; z-index: 10;
    }

    /* ================= FASE 1: GOD-TIER HUD SCANNER ================= */
    .auth-vault {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        width: 100%; max-width: 500px; transition: all 1s cubic-bezier(0.25, 1, 0.5, 1); z-index: 50;
    }

    .hud-container {
        position: relative; width: clamp(260px, 70vw, 340px); aspect-ratio: 3/4;
        display: flex; justify-content: center; align-items: center;
    }

    /* Sudut-sudut HUD (Bracket) */
    .hud-bracket {
        position: absolute; width: 40px; height: 40px; border: 3px solid var(--hud-color); z-index: 10;
        transition: all 0.3s; box-shadow: 0 0 15px var(--hud-color); opacity: 0.8;
    }
    .hud-tl { top: -10px; left: -10px; border-right: none; border-bottom: none; }
    .hud-tr { top: -10px; right: -10px; border-left: none; border-bottom: none; }
    .hud-bl { bottom: -10px; left: -10px; border-right: none; border-top: none; }
    .hud-br { bottom: -10px; right: -10px; border-left: none; border-top: none; }

    .camera-ring {
        position: relative; width: 100%; height: 100%; border-radius: 20px;
        background: rgba(0,0,0,0.8); box-shadow: 0 20px 50px rgba(0,0,0,0.8); overflow: hidden;
        border: 1px solid rgba(212,175,55,0.2);
    }

    .camera-feed { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); filter: contrast(1.1) saturate(1.2); }
    
    .scanner-sweep {
        position: absolute; top: 0; left: 0; width: 100%; height: 20%;
        background: linear-gradient(to bottom, transparent, rgba(212,175,55,0.4), rgba(212,175,55,0.8));
        border-bottom: 2px solid var(--hud-color); box-shadow: 0 5px 20px var(--hud-color);
        animation: elegantSweep 2.5s ease-in-out infinite alternate; pointer-events: none; display: none;
    }
    @keyframes elegantSweep { 0% { transform: translateY(-100%); } 100% { transform: translateY(500%); } }

    /* Indikator Liveness (3 Tahap) */
    .liveness-steps {
        display: flex; gap: 10px; margin-top: 25px;
    }
    .step-dot {
        width: 40px; height: 6px; border-radius: 3px; background: rgba(255,255,255,0.1);
        transition: all 0.5s; position: relative; overflow: hidden;
    }
    .step-dot.active { background: var(--hud-color); box-shadow: 0 0 10px var(--hud-color); }
    .step-dot.done { background: var(--neon-green); box-shadow: 0 0 10px var(--neon-green); }

    .status-badge {
        margin-top: 20px; padding: 15px 35px; background: rgba(0,0,0,0.6); backdrop-filter: blur(20px);
        border: 1px solid rgba(212,175,55,0.3); border-radius: 8px; color: #fff; font-family: 'Courier New', monospace;
        font-size: 0.9rem; letter-spacing: 2px; font-weight: bold; text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5); border-left: 4px solid var(--hud-color); text-transform: uppercase;
    }


    /* ================= FASE 2: MAGNETIC 3D GRID (AWWWARDS LEVEL) ================= */
    .features-dashboard {
        display: none; width: 100%; max-width: 1200px;
        flex-direction: column; align-items: center; opacity: 0; z-index: 20;
    }

    .dashboard-header { text-align: center; margin-bottom: 70px; }
    .dashboard-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: clamp(2.5rem, 6vw, 4.5rem); margin: 0; letter-spacing: 5px; text-transform: uppercase; text-shadow: 0 15px 30px rgba(0,0,0,0.9); }
    .dashboard-subtitle { color: #7b8e9b; font-size: 1rem; letter-spacing: 6px; text-transform: uppercase; margin-top: 15px; }

    .cinematic-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; width: 100%; padding: 0 20px; perspective: 1500px; }

    /* Kartu Magnetic 3D */
    .premium-card {
        position: relative; height: 420px; border-radius: 20px; text-decoration: none; transform-style: preserve-3d;
        box-shadow: 0 25px 50px rgba(0,0,0,0.8); border: 1px solid rgba(255,255,255,0.05); 
        /* Transisi diatur oleh JS saat hover agar mulus */
        background-color: #050a08;
    }

    .premium-card .card-bg {
        position: absolute; inset: -20px; background-size: cover; background-position: center; 
        transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1), filter 0.8s; z-index: 1; filter: grayscale(100%) brightness(0.4);
    }
    .premium-card .card-bg::after {
        content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, transparent 80%);
    }

    .premium-card::before {
        content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 50% 100%, rgba(212,175,55,0.2) 0%, transparent 60%);
        z-index: 2; opacity: 0; transition: opacity 0.5s; pointer-events: none;
    }

    .card-content {
        position: absolute; inset: 0; z-index: 10; padding: 40px 30px; display: flex; flex-direction: column; justify-content: flex-end;
        transform: translateZ(40px); /* Efek Teks Melayang (Parallax) */ pointer-events: none;
    }

    .card-icon { position: absolute; top: 30px; right: 30px; font-size: 2.2rem; color: rgba(255,255,255,0.2); transition: color 0.5s, transform 0.5s; transform: translateZ(30px); }
    .card-title { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.8rem; margin: 0 0 10px 0; transform: translateY(20px); transition: 0.5s; }
    .card-desc { color: #7b8e9b; font-size: 0.85rem; line-height: 1.6; margin: 0; opacity: 0; transform: translateY(20px); transition: 0.5s; }
    .launch-btn { margin-top: 20px; font-size: 0.75rem; letter-spacing: 3px; color: var(--gold-main); text-transform: uppercase; opacity: 0; transform: translateY(20px); transition: 0.5s; display: flex; align-items: center; gap: 10px; font-weight: bold; }

    /* HOVER STATE (JS takes over tilt, CSS handles color/opacity) */
    .premium-card:hover .card-bg { filter: grayscale(0%) brightness(0.7); transform: scale(1.05); }
    .premium-card:hover::before { opacity: 1; }
    .premium-card:hover .card-icon { color: var(--gold-main); transform: translateZ(50px) scale(1.1); text-shadow: 0 0 20px rgba(212,175,55,0.5); }
    .premium-card:hover .card-title, .premium-card:hover .card-desc, .premium-card:hover .launch-btn { transform: translateY(0); opacity: 1; }

    .locked-card { filter: grayscale(100%); opacity: 0.5; pointer-events: none; }
    .locked-card .card-icon { color: #ff3366 !important; }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="vault-wrapper">

    <div class="auth-vault" id="authVault">
        <div class="hud-container">
            <div class="hud-bracket hud-tl" id="hudTl"></div>
            <div class="hud-bracket hud-tr" id="hudTr"></div>
            <div class="hud-bracket hud-bl" id="hudBl"></div>
            <div class="hud-bracket hud-br" id="hudBr"></div>
            
            <div class="camera-ring">
                <video id="cameraFeed" class="camera-feed" autoplay playsinline muted></video>
                <div class="scanner-sweep" id="scannerSweep"></div>
            </div>
        </div>

        <div class="liveness-steps">
            <div class="step-dot" id="step1"></div>
            <div class="step-dot" id="step2"></div>
            <div class="step-dot" id="step3"></div>
        </div>

        <div class="status-badge" id="statusBadge">
            <span id="statusText">INISIASI SISTEM...</span>
        </div>
    </div>

    <div class="features-dashboard" id="featuresDashboard">
        <div class="dashboard-header">
            <h1 class="dashboard-title">The Sovereign Vault</h1>
            <p class="dashboard-subtitle">Akses Eksklusif Entitas Expedient Terverifikasi</p>
        </div>

        <div class="cinematic-grid">
            <a href="/sovereign" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-gem card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Sovereign ID</h3>
                    <p class="card-desc">Modul identitas 5D interaktif. Merender ulang data biometrik dan arsip Anda dalam bentuk holografik.</p>
                    <div class="launch-btn">Inisiasi Protokol <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/profil" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2670&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-id-badge card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Profil Entitas</h3>
                    <p class="card-desc">Ruang kendali utama. Manajemen data pribadi dan pengaturan kunci keamanan fisik (Passkey).</p>
                    <div class="launch-btn">Akses Ruang Kendali <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <div class="premium-card locked-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2672&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-address-book card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Direktori Global</h3>
                    <p class="card-desc">Peta pelacakan interaktif. Memvisualisasikan lokasi terenkripsi dari seluruh entitas Expedient.</p>
                    <div class="launch-btn" style="color:#ff3366;">Akses Dikunci</div>
                </div>
            </div>

            <div class="premium-card locked-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1481819613568-3701cbc70156?q=80&w=2680&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-images card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Ekho Memori</h3>
                    <p class="card-desc">Rekaman visual sejarah angkatan. Disusun dalam galeri masonry tingkat militer.</p>
                    <div class="launch-btn" style="color:#ff3366;">Akses Dikunci</div>
                </div>
            </div>
        </div>
    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // === ELEMEN UI ===
    const videoElement = document.getElementById('cameraFeed');
    const statusText = document.getElementById('statusText');
    const statusBadge = document.getElementById('statusBadge');
    const authVault = document.getElementById('authVault');
    const featuresDashboard = document.getElementById('featuresDashboard');
    const scannerSweep = document.getElementById('scannerSweep');
    
    // HUD & Steps
    const brackets = document.querySelectorAll('.hud-bracket');
    const s1 = document.getElementById('step1');
    const s2 = document.getElementById('step2');
    const s3 = document.getElementById('step3');

    // === STATE ===
    let streamRef = null;
    let authInterval = null;
    let isAuthenticating = false;
    let currentStep = 'matching'; // matching -> smiling -> turning

    // Ambil Data Wajah
    const dbFaceDataRaw = <?= empty($face_data_db) || $face_data_db === 'null' ? 'null' : $face_data_db ?>;
    let targetDescriptor = null;

    // === HELPER UI ===
    const updateHUD = (text, color, sweepOn, step) => {
        statusText.innerText = text;
        statusText.style.color = color;
        statusBadge.style.borderLeftColor = color;
        scannerSweep.style.display = sweepOn ? "block" : "none";
        
        // Ubah warna HUD Bracket
        brackets.forEach(b => {
            b.style.borderColor = color;
            b.style.boxShadow = `0 0 15px ${color}`;
        });

        // Update Indikator Step
        if(step === 1) { s1.className = 'step-dot active'; s2.className = 'step-dot'; s3.className = 'step-dot'; }
        if(step === 2) { s1.className = 'step-dot done'; s2.className = 'step-dot active'; s3.className = 'step-dot'; }
        if(step === 3) { s1.className = 'step-dot done'; s2.className = 'step-dot done'; s3.className = 'step-dot active'; }
        if(step === 'done') { s1.className = 'step-dot done'; s2.className = 'step-dot done'; s3.className = 'step-dot done'; }
    };

   // === SISTEM LIVENESS 3 TAHAP (REVISI KETAT) ===
    const startFaceVerification = async () => {
        if (dbFaceDataRaw === null) {
            updateHUD("DATA WAJAH KOSONG", "#ff3366", false, 0);
            authVault.innerHTML += `<br><button onclick="unlockVault()" style="margin-top:20px; padding:10px 20px; background:transparent; border:1px solid var(--gold-main); color:var(--gold-main); border-radius:5px; cursor:pointer;">BYPASS PROTOKOL</button>`;
            return;
        }

        targetDescriptor = new Float32Array(dbFaceDataRaw);
        updateHUD("MEMUAT MODEL AI (8 FILES)...", "var(--gold-main)", false, 0);

        try {
            const MODEL_URL = window.location.origin + '/assets/models';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
            ]);
        } catch (err) {
            return updateHUD("GAGAL MEMUAT MODEL AI", "#ff3366", false, 0);
        }

        updateHUD("MENGHUBUNGKAN OPTIK...", "var(--gold-main)", false, 0);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
            videoElement.srcObject = stream;
            streamRef = stream;
        } catch (err) {
            updateHUD("AKSES KAMERA DITOLAK", "#ff3366", false, 0);
            return;
        }

        videoElement.onplay = () => {
            updateHUD("MENCOCOKKAN MATRIKS WAJAH...", "var(--gold-main)", true, 1);
            
            authInterval = setInterval(async () => {
                if (isAuthenticating) return;

                const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions())
                                               .withFaceLandmarks().withFaceDescriptor().withFaceExpressions();
                
                if (!detection) return updateHUD("WAJAH TIDAK TERDETEKSI", "var(--gold-main)", true, currentStep === 'matching'? 1 : (currentStep==='smiling'? 2:3));

                // TAHAP 1: PENCOCOKAN WAJAH
                if (currentStep === 'matching') {
                    const distance = faceapi.euclideanDistance(targetDescriptor, detection.descriptor);
                    if (distance < 0.5) {
                        currentStep = 'smiling';
                        isAuthenticating = true;
                        updateHUD("COCOK. SILAKAN TERSENYUM...", "var(--neon-blue)", true, 2);
                        setTimeout(() => { isAuthenticating = false; }, 1200); // Jeda 1.2 detik
                    } else {
                        updateHUD("ENTITAS TIDAK DIKENALI", "#ff3366", true, 1);
                    }
                } 
                // TAHAP 2: DETEKSI SENYUM
                else if (currentStep === 'smiling') {
                    if (detection.expressions.happy > 0.85) {
                        currentStep = 'turning';
                        isAuthenticating = true;
                        updateHUD("BAGUS. TOLEHKAN KEPALA ANDA...", "var(--neon-blue)", true, 3);
                        // Jeda diperpanjang agar AI tidak langsung membaca kemiringan kepala sisa senyum
                        setTimeout(() => { isAuthenticating = false; }, 1500); 
                    } else {
                        updateHUD("SISTEM MENUNGGU SENYUMAN ANDA...", "var(--neon-blue)", true, 2);
                    }
                } 
                // TAHAP 3: DETEKSI MENOLEH (Kalkulasi Absolut)
                else if (currentStep === 'turning') {
                    const landmarks = detection.landmarks;
                    const nose = landmarks.getNose()[0];
                    const leftEye = landmarks.getLeftEye()[0];
                    const rightEye = landmarks.getRightEye()[0];
                    
                    // Gunakan Math.abs agar selalu bernilai positif mutlak
                    const distLeft = Math.abs(nose.x - leftEye.x);
                    const distRight = Math.abs(nose.x - rightEye.x);
                    
                    if (distRight === 0) return; // Mencegah error pembagian nol
                    
                    const ratio = distLeft / distRight;
                    
                    // Toleransi diperketat menjadi < 0.35 atau > 2.5 (Memaksa user benar-benar menoleh tajam)
                    if (ratio < 0.35 || ratio > 2.5) {
                        clearInterval(authInterval);
                        updateHUD("OTORISASI ABSOLUT BERHASIL!", "var(--neon-green)", false, 'done');
                        setTimeout(unlockVault, 1200);
                    } else {
                        // Jika belum mencapai rasio tajam, paksa user terus mencoba
                        updateHUD("TOLEHKAN KEPALA LEBIH JAUH...", "var(--neon-blue)", true, 3);
                    }
                }
            }, 600);
        };
    };

    // === FUNGSI UNLOCK & AJAX ===
    window.unlockVault = async () => {
        if (streamRef) streamRef.getTracks().forEach(track => track.stop());

        try {
            await fetch('/fitur/unlock', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            });
        } catch (error) {}

        // GSAP Transisi Epik
        gsap.to(authVault, {
            opacity: 0, scale: 0.5, y: -50, duration: 1, ease: "power3.in",
            onComplete: () => {
                authVault.style.display = "none";
                featuresDashboard.style.display = "flex";
                
                gsap.to(featuresDashboard, { opacity: 1, duration: 1 });
                gsap.from(".dashboard-header", { opacity: 0, y: -50, duration: 1.5, ease: "expo.out" });
                
                // Stagger efek kartu masuk dari bawah
                gsap.from(".premium-card", { 
                    opacity: 0, y: 100, rotationX: -20, duration: 1.2, 
                    stagger: 0.15, ease: "back.out(1.5)", delay: 0.2 
                });
            }
        });
    };

    // === MAGNETIC 3D TILT EFFECT (AWWWARDS JS) ===
    const cards = document.querySelectorAll('.js-tilt-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; // x position within the element.
            const y = e.clientY - rect.top;  // y position within the element.
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            // Calculate rotation (max 15 degrees)
            const rotateX = ((y - centerY) / centerY) * -15;
            const rotateY = ((x - centerX) / centerX) * 15;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            card.style.transition = "none"; // Remove transition for smooth tracking
        });

        card.addEventListener('mouseleave', () => {
            // Reset position smoothly
            card.style.transition = "transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)";
            card.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
        });
    });

    // Start!
    startFaceVerification();
});
</script>
<?= $this->endSection() ?>