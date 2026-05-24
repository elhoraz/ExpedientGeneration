<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>The Enigma Vault - Uji Inisiasi</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --enigma-gold: #d4af37;
            --enigma-dark: #020403;
            --enigma-glow: rgba(212,175,55,0.4);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            background-color: var(--enigma-dark);
            user-select: none;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
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

        .enigma-wrapper {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .vault-container {
            position: relative;
            width: clamp(300px, 80vw, 500px);
            aspect-ratio: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Rings Setup */
        .ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(212,175,55,0.2);
            box-shadow: 
                inset 0 0 30px rgba(0,0,0,0.8),
                0 0 20px rgba(0,0,0,0.8),
                inset 0 0 5px var(--enigma-gold);
            background: radial-gradient(circle at center, #0a0f0c 0%, #030504 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: grab;
            transition: filter 0.3s ease;
        }

        .ring:active { cursor: grabbing; }

        /* Sizes */
        .ring.outer { width: 100%; height: 100%; z-index: 10; }
        .ring.middle { width: 75%; height: 75%; z-index: 20; }
        .ring.inner { width: 50%; height: 50%; z-index: 30; }

        /* Center Core */
        .vault-core {
            position: absolute;
            width: 25%;
            height: 25%;
            border-radius: 50%;
            background: #000;
            z-index: 40;
            box-shadow: 
                inset 0 0 10px rgba(0,0,0,0.9),
                0 0 30px rgba(212,175,55,0.2);
            border: 2px solid var(--enigma-gold);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 1s ease;
        }

        .core-logo {
            width: 60%;
            opacity: 0.1;
            filter: grayscale(1);
            transition: all 1s ease;
        }

        /* Ring Symbols */
        .symbol {
            position: absolute;
            color: rgba(212,175,55,0.6);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: clamp(14px, 4vw, 22px);
            text-shadow: 0 0 10px rgba(0,0,0,0.8);
            transform-origin: center center;
            pointer-events: none;
        }

        /* Fixed Selection Indicator */
        .selection-marker {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 25px solid var(--enigma-gold);
            z-index: 50;
            filter: drop-shadow(0 0 10px var(--enigma-gold));
        }

        /* HUD Text */
        .riddle-box {
            margin-top: 50px;
            text-align: center;
            width: 80%;
            max-width: 600px;
            z-index: 100;
        }

        .riddle-title {
            font-family: 'Playfair Display', serif;
            color: var(--enigma-gold);
            font-size: 1.5rem;
            letter-spacing: 4px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .riddle-text {
            font-family: 'Courier New', monospace;
            color: #8b9ba8;
            font-size: 0.9rem;
            line-height: 1.6;
            letter-spacing: 2px;
        }

        /* Success State */
        .vault-container.unlocked .ring {
            filter: brightness(1.5) drop-shadow(0 0 20px var(--enigma-gold));
            pointer-events: none;
        }

        .vault-container.unlocked .vault-core {
            background: radial-gradient(circle at center, #d4af37 0%, #aa771c 100%);
            box-shadow: 0 0 100px var(--enigma-gold);
            transform: scale(1.1);
        }

        .vault-container.unlocked .core-logo {
            opacity: 1;
            filter: grayscale(0) drop-shadow(0 0 15px #fff);
        }

        /* Overlays */
        .success-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.9);
            z-index: 999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 1.5s ease;
        }

        .success-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .clearance-title {
            font-family: 'Playfair Display', serif;
            color: var(--enigma-gold);
            font-size: clamp(2rem, 6vw, 4rem);
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 20px;
            text-align: center;
            transform: translateY(30px);
            opacity: 0;
        }

        .secret-quote {
            font-family: 'Courier New', monospace;
            color: #fff;
            font-size: 1rem;
            text-align: center;
            max-width: 80%;
            line-height: 1.8;
            letter-spacing: 3px;
            transform: translateY(20px);
            opacity: 0;
        }

        .btn-return {
            margin-top: 40px;
            padding: 12px 30px;
            border: 1px solid var(--enigma-gold);
            background: rgba(212,175,55,0.1);
            color: var(--enigma-gold);
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 3px;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            transform: translateY(20px);
            opacity: 0;
        }
        .btn-return:hover {
            background: var(--enigma-gold);
            color: #000;
        }

        @media (max-width: 768px) {
            .btn-back-vault { top: 20px; left: 20px; padding: 8px 15px; font-size: 10px; }
            .enigma-wrapper { height: auto; min-height: 100vh; padding: 80px 20px 40px 20px; justify-content: flex-start; }
            .vault-container { width: clamp(280px, 90vw, 350px); margin-bottom: 20px; }
        }
        @media (min-width: 769px) {
            .enigma-wrapper { flex-direction: row; gap: 60px; }
            .riddle-box { text-align: left; }
            .riddle-text { text-align: left !important; }
            .vault-container { width: 450px; }
        }
    </style>
</head>
<body>

    <a href="/fitur" class="btn-back-vault">
        <i class="fa-solid fa-chevron-left"></i> <?= cms_text('enigma_btn_exit', 'Exit Enigma') ?>
    </a>

    <div class="enigma-wrapper">
        <div class="vault-container" id="vaultContainer">
            <div class="selection-marker"></div>
            
            <div class="ring outer" id="ringOuter" data-ring="0"></div>
            <div class="ring middle" id="ringMiddle" data-ring="1"></div>
            <div class="ring inner" id="ringInner" data-ring="2"></div>
            
            <div class="vault-core" id="vaultCore">
                <img src="/images/logo-utuh.png" class="core-logo" id="coreLogo">
            </div>
        </div>

        <div class="riddle-box">
            <div class="riddle-title"><?= cms_text('enigma_riddle_title', 'The Grand Alignment') ?></div>
            
            <?php if(isset($progress) && $progress['is_completed']): ?>
                <div style="margin-top: 20px; color: #00ff88; font-weight: bold; letter-spacing: 2px;">
                    <?= cms_text('enigma_solved_msg', 'SIMPUL TELAH TERPECAHKAN.') ?>
                </div>
                <div style="margin-top: 20px;">
                    <a href="/enigma/reset" class="btn-return" style="opacity: 1; transform: none; display: inline-block; padding: 10px 25px; margin-top: 0; background: transparent; border-color: #ff3366; color: #ff3366; font-size: 0.8rem;"><?= cms_text('enigma_btn_relock_main', 'KUNCI ULANG BRANKAS') ?></a>
                </div>
            <?php else: ?>
                <div class="riddle-text" style="text-align: left; display: inline-block; margin-top: 15px;">
                    <em><?= cms_text('enigma_riddle_intro', 'Selaraskan cincin untuk membuka gerbang:') ?></em><br><br>
                    <strong><?= cms_text('enigma_riddle_lapis_luar', 'Lapis Luar:') ?></strong> <?= cms_text('enigma_riddle_clue_luar', 'Bintang kejayaan peradaban.') ?><br>
                    <strong><?= cms_text('enigma_riddle_lapis_tengah', 'Lapis Tengah:') ?></strong> <?= cms_text('enigma_riddle_clue_tengah', 'Perisai waktu lima waktu.') ?><br>
                    <strong><?= cms_text('enigma_riddle_lapis_dalam', 'Lapis Dalam:') ?></strong> <?= cms_text('enigma_riddle_clue_dalam', 'Yang Maha Esa.') ?><br>
                </div>
                
                <div style="margin-top: 30px;">
                    <button id="btnUnlock" class="btn-return" style="opacity: 1; transform: none; display: inline-block; padding: 12px 30px; margin-top: 0; background: var(--enigma-gold); color: #000; font-weight: bold;"><?= cms_text('enigma_btn_initiate', 'INISIASI PEMBUKAAN') ?></button>
                </div>
                <div id="errorMsg" style="margin-top: 15px; color: #ff3366; font-size: 0.85rem; letter-spacing: 1px; display: none;"><?= cms_text('enigma_error_msg', 'Kombinasi tidak selaras. Getaran ditolak.') ?></div>
            <?php endif; ?>
        </div>

        <div class="success-overlay" id="successOverlay">
            <div class="clearance-title"><?= cms_text('enigma_clearance_title', 'Clearance: Apex') ?></div>
            <div class="secret-quote"><?= cms_html('enigma_secret_quote', '"Intelijen sejati bukanlah mengetahui segalanya,<br>melainkan melihat apa yang disembunyikan oleh dunia."') ?></div>
            <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;" class="action-buttons">
                <a href="/fitur" class="btn-return"><?= cms_text('enigma_btn_return_vault', 'Kembali ke Vault') ?></a>
                <a href="/enigma/reset" class="btn-return" style="background: transparent; border-color: #ff3366; color: #ff3366;"><?= cms_text('enigma_btn_relock', 'Kunci Ulang') ?></a>
            </div>
        </div>
    </div>

    <script src="/vendor/gsap/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const rings = [
            document.getElementById('ringOuter'),
            document.getElementById('ringMiddle'),
            document.getElementById('ringInner')
        ];
        
        // Konfigurasi Ring
        const symbols = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"];
        const sectionAngle = 360 / symbols.length; // 36 derajat per simbol

        // Target Solusi: Outer = IV (Index 3), Middle = II (Index 1), Inner = X (Index 9)
        const targetIndices = [3, 1, 9]; 
        let currentIndices = [0, 0, 0];
        let ringRotations = [0, 0, 0];
        
        // Acak posisi awal agar tidak langsung terbuka
        ringRotations = [
            Math.floor(Math.random()*10)*36, 
            Math.floor(Math.random()*10)*36, 
            Math.floor(Math.random()*10)*36
        ];

        // Mencegah posisi awal sama persis dengan target
        for(let i=0; i<3; i++) {
            let currentIndex = (10 - Math.round(ringRotations[i] / 36) % 10) % 10;
            if(currentIndex === targetIndices[i]) {
                ringRotations[i] += 36;
            }
        }

        // Bangun elemen visual simbol dalam setiap ring
        rings.forEach((ring, rIndex) => {
            const radius = ring.offsetWidth / 2 - 25; // Jarak dari pusat
            
            symbols.forEach((sym, i) => {
                const el = document.createElement('div');
                el.className = 'symbol';
                el.innerText = sym;
                ring.appendChild(el);
                
                // Posisikan melingkar menggunakan matematika dasar (dikurangi 90deg agar index 0 di atas/jam 12)
                const angleRad = (i * sectionAngle - 90) * (Math.PI / 180);
                
                el.style.left = `calc(50% + ${Math.cos(angleRad) * radius}px)`;
                el.style.top = `calc(50% + ${Math.sin(angleRad) * radius}px)`;
                
                // Rotasi agar huruf menghadap pusat (atau berdiri tegak)
                el.style.transform = `translate(-50%, -50%) rotate(${i * sectionAngle}deg)`;
            });

            // Set rotasi awal
            gsap.set(ring, { rotation: ringRotations[rIndex] });
        });

        // Interaksi Drag/Putar
        let activeRing = null;
        let lastAngle = 0;
        let isUnlocked = false;

        function getAngle(x, y, rect) {
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            return Math.atan2(y - centerY, x - centerX) * (180 / Math.PI);
        }

        function onPointerDown(e) {
            if(isUnlocked) return;
            e.preventDefault();
            const ring = e.currentTarget;
            activeRing = parseInt(ring.getAttribute('data-ring'));
            
            const rect = ring.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            
            lastAngle = getAngle(clientX, clientY, rect);
            
            ring.style.cursor = 'grabbing';
            e.stopPropagation();
        }

        function onPointerMove(e) {
            if (activeRing === null || isUnlocked) return;
            e.preventDefault();
            
            const ringElement = rings[activeRing];
            const rect = ringElement.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            
            const currentAngle = getAngle(clientX, clientY, rect);
            let deltaAngle = currentAngle - lastAngle;
            
            // Memperbaiki lompatan kalkulasi sudut (saat melewati -180/180)
            if (deltaAngle > 180) deltaAngle -= 360;
            if (deltaAngle < -180) deltaAngle += 360;

            let currentRot = gsap.getProperty(ringElement, "rotation");
            let newRotation = currentRot + deltaAngle;
            
            gsap.set(ringElement, { rotation: newRotation });
            
            lastAngle = currentAngle; // Update untuk frame berikutnya
        }

        function onPointerUp(e) {
            if (activeRing === null || isUnlocked) return;
            
            const currentRingIndex = activeRing; // Capture block-scoped variable for closure
            const ringElement = rings[currentRingIndex];
            ringElement.style.cursor = 'grab';
            
            // Dapatkan rotasi aktual dari GSAP (bisa jadi negatif atau >360)
            let finalRot = gsap.getProperty(ringElement, "rotation");
            
            // Snap ke kelipatan 36 terdekat (efek mekanik)
            let snappedRot = Math.round(finalRot / 36) * 36;
            
            // Perbarui state SEGERA agar tidak ada race condition jika di-klik cepat
            ringRotations[currentRingIndex] = snappedRot;

            gsap.to(ringElement, { 
                rotation: snappedRot, 
                duration: 0.3, 
                ease: "back.out(1.5)"
            });

            // Haptic Audio Simulasi
            if (navigator.vibrate) navigator.vibrate(15);
            
            activeRing = null;
        }

        // Mendaftarkan event
        rings.forEach(ring => {
            ring.addEventListener('mousedown', onPointerDown);
            ring.addEventListener('touchstart', onPointerDown, {passive: false});
        });

        window.addEventListener('mousemove', onPointerMove);
        window.addEventListener('touchmove', onPointerMove, {passive: false});

        window.addEventListener('mouseup', onPointerUp);
        window.addEventListener('touchend', onPointerUp);

        function getTopIndex(rotation) {
            // Normalisasi rotasi ke 0-359
            let norm = ((rotation % 360) + 360) % 360;
            // Bulatkan ke kelipatan 36 terdekat untuk menghindari floating point
            let steps = Math.round(norm / 36);
            if (steps >= 10) steps = 0; // 360 derajat = 0 derajat
            // Rotasi searah jarum jam = simbol bergerak berlawanan, jadi indeks top = (10 - steps) % 10
            return (10 - steps) % 10;
        }

        const btnUnlock = document.getElementById('btnUnlock');
        if (btnUnlock) {
            btnUnlock.addEventListener('click', () => {
                const errorMsg = document.getElementById('errorMsg');
                errorMsg.style.display = 'none';

                // Ambil rotasi aktual
                let r0 = gsap.getProperty(rings[0], "rotation");
                let r1 = gsap.getProperty(rings[1], "rotation");
                let r2 = gsap.getProperty(rings[2], "rotation");

                let combo = [getTopIndex(r0), getTopIndex(r1), getTopIndex(r2)];

                // Animasi loading kecil
                btnUnlock.innerText = "<?= cms_raw('enigma_btn_verify', 'MEMVERIFIKASI...') ?>";
                btnUnlock.style.opacity = 0.5;
                btnUnlock.style.pointerEvents = 'none';

                fetch('/enigma/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({ combination: combo })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btnUnlock.innerText = "<?= cms_raw('enigma_btn_access', 'AKSES DIBERIKAN') ?>";
                        btnUnlock.style.background = "#00ff88";
                        triggerUnlock();
                    } else {
                        // Gagal
                        btnUnlock.innerText = "<?= cms_raw('enigma_btn_initiate', 'INISIASI PEMBUKAAN') ?>";
                        btnUnlock.style.opacity = 1;
                        btnUnlock.style.pointerEvents = 'auto';
                        errorMsg.style.display = 'block';

                        // Shake animation
                        gsap.to('#vaultContainer', {
                            x: -10, duration: 0.1, yoyo: true, repeat: 5, ease: "linear",
                            onComplete: () => { gsap.set('#vaultContainer', {x: 0}); }
                        });
                        if (navigator.vibrate) navigator.vibrate(200);
                    }
                })
                .catch(err => {
                    console.error(err);
                    btnUnlock.innerText = "<?= cms_raw('enigma_btn_initiate', 'INISIASI PEMBUKAAN') ?>";
                    btnUnlock.style.opacity = 1;
                    btnUnlock.style.pointerEvents = 'auto';
                });
            });
        }

        function triggerUnlock() {
            isUnlocked = true;
            if (navigator.vibrate) navigator.vibrate([100, 50, 200]);
            
            const vaultContainer = document.getElementById('vaultContainer');
            vaultContainer.classList.add('unlocked');

            // Mainkan Animasi Gembok Terbuka (Menggeser ring keluar)
            gsap.to('#ringOuter', { scale: 1.2, opacity: 0, duration: 1.5, ease: "power2.inOut", delay: 0.5 });
            gsap.to('#ringMiddle', { scale: 1.4, opacity: 0, duration: 1.5, ease: "power2.inOut", delay: 0.8 });
            gsap.to('#ringInner', { scale: 1.6, opacity: 0, duration: 1.5, ease: "power2.inOut", delay: 1.1 });

            // Tampilkan Pesan Sukses
            setTimeout(() => {
                const overlay = document.getElementById('successOverlay');
                overlay.classList.add('show');
                gsap.to('.clearance-title', { opacity: 1, y: 0, duration: 1.5, ease: "power3.out", delay: 0.5 });
                gsap.to('.secret-quote', { opacity: 1, y: 0, duration: 1.5, ease: "power3.out", delay: 1.5 });
                gsap.to('.btn-return', { opacity: 1, y: 0, duration: 1, ease: "power3.out", delay: 2.5 });
            }, 2000);
        }

        <?php if(isset($progress) && $progress['is_completed']): ?>
        setTimeout(() => {
            triggerUnlock();
        }, 500);
        <?php endif; ?>
    });
    </script>
</body>
</html>
