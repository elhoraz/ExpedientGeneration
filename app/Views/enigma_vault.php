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
            overflow: hidden;
            user-select: none;
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
        }
    </style>
</head>
<body>

    <a href="/fitur" class="btn-back-vault">
        <i class="fa-solid fa-chevron-left"></i> Exit Enigma
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
            <div class="riddle-title">Tahap Refleksi <?= isset($progress) ? $progress['current_level'] : 1 ?></div>
            <div class="riddle-text">
                <?= isset($puzzle) ? $puzzle['question'] : "Identitas Sejati tersembunyi dalam struktur." ?>
            </div>
            
            <?php if(isset($progress) && $progress['is_completed']): ?>
                <div style="margin-top: 20px; color: #00ff88; font-weight: bold; letter-spacing: 2px;">
                    SIMPUL TELAH TERPECAHKAN.
                </div>
            <?php else: ?>
                <form action="/enigma/verify" method="POST" style="margin-top: 25px; display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <?= csrf_field() ?>
                    <input type="text" name="answer" placeholder="Tuliskan pemahaman Anda..." required 
                        style="width: 100%; max-width: 300px; padding: 12px; background: rgba(0,0,0,0.5); border: 1px solid var(--enigma-gold); color: #fff; font-family: 'Courier New', monospace; text-align: center; border-radius: 8px;">
                    <button type="submit" class="btn-return" style="opacity: 1; transform: none; display: inline-block; padding: 10px 25px; margin-top: 0;">Konfirmasi Kebijaksanaan</button>
                </form>
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div style="margin-top: 15px; color: #ff3366; font-size: 0.85rem; letter-spacing: 1px;"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div style="margin-top: 15px; color: #00ff88; font-size: 0.85rem; letter-spacing: 1px;"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
        </div>

        <div class="success-overlay" id="successOverlay">
            <div class="clearance-title">Clearance: Apex</div>
            <div class="secret-quote">"Intelijen sejati bukanlah mengetahui segalanya,<br>melainkan melihat apa yang disembunyikan oleh dunia."</div>
            <a href="/fitur" class="btn-return">Kembali ke Vault</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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
        let startAngle = 0;
        let initialRotation = 0;
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
            
            startAngle = getAngle(clientX, clientY, rect);
            initialRotation = ringRotations[activeRing];
            
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
            let deltaAngle = currentAngle - startAngle;
            
            // Memperbaiki lompatan kalkulasi sudut (saat melewati -180/180)
            if (deltaAngle > 180) deltaAngle -= 360;
            if (deltaAngle < -180) deltaAngle += 360;

            let newRotation = initialRotation + deltaAngle;
            
            gsap.set(ringElement, { rotation: newRotation });
        }

        function onPointerUp(e) {
            if (activeRing === null || isUnlocked) return;
            
            const ringElement = rings[activeRing];
            ringElement.style.cursor = 'grab';
            
            // Dapatkan rotasi aktual dari GSAP (bisa jadi negatif atau >360)
            let finalRot = gsap.getProperty(ringElement, "rotation");
            
            // Snap ke kelipatan 36 terdekat (efek mekanik)
            let snappedRot = Math.round(finalRot / 36) * 36;
            
            gsap.to(ringElement, { 
                rotation: snappedRot, 
                duration: 0.3, 
                ease: "back.out(1.5)",
                onComplete: () => {
                    ringRotations[activeRing] = snappedRot;
                    checkSolution();
                }
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

        function checkSolution() {
            // Evaluasi di backend, ring putar hanya interaksi visual haptic
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
    });
    </script>
</body>
</html>
