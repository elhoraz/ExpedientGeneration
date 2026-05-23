<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>The Celestial Codex - Kartu Takdir</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --gold: #d4af37;
            --gold-light: #ffd700;
            --dark: #020202;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            user-select: none;
            height: 100vh;
            width: 100vw;
        }

        /* Ambient Background */
        .ambient {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
        }
        .ambient::before {
            content: ''; position: absolute; width: 80vw; height: 80vw;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(212,175,55,0.06) 0%, transparent 70%);
            animation: ambientPulse 6s infinite alternate ease-in-out;
        }
        @keyframes ambientPulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            100% { transform: translate(-50%, -50%) scale(1.3); opacity: 1; }
        }

        /* Floating Particles */
        .particle {
            position: fixed;
            width: 3px; height: 3px;
            background: var(--gold);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            opacity: 0;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0% { opacity: 0; transform: translateY(100vh) scale(0); }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { opacity: 0; transform: translateY(-10vh) scale(1); }
        }

        /* Back Button */
        .btn-back {
            position: fixed; top: 30px; left: 30px; z-index: 200;
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; background: rgba(0,0,0,0.6);
            border: 1px solid rgba(212,175,55,0.3); border-radius: 8px;
            color: var(--gold); font-size: 11px; font-weight: 600;
            letter-spacing: 3px; text-decoration: none; text-transform: uppercase;
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            transform: translateX(-5px); box-shadow: 0 0 20px rgba(212,175,55,0.5); color: #fff;
        }

        /* Main Layout */
        .codex-wrapper {
            position: relative; z-index: 10;
            width: 100vw; height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 30px;
        }

        /* Title */
        .codex-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            color: var(--gold);
            letter-spacing: 8px;
            text-transform: uppercase;
            text-shadow: 0 0 30px rgba(212,175,55,0.3);
            text-align: center;
            opacity: 0;
            transform: translateY(-20px);
        }

        .codex-subtitle {
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
            color: #7b8e9b;
            letter-spacing: 4px;
            text-align: center;
            opacity: 0;
            transform: translateY(-10px);
        }

        /* Card Area */
        .card-stage {
            display: flex;
            gap: clamp(15px, 4vw, 40px);
            align-items: center;
            justify-content: center;
            perspective: 1500px;
            min-height: 300px;
        }

        /* 3D Card */
        .tarot-card {
            width: clamp(100px, 22vw, 160px);
            height: clamp(160px, 35vw, 260px);
            cursor: pointer;
            perspective: 1200px;
            opacity: 0;
            transform: translateY(50px);
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }

        .tarot-card.flipped .card-inner {
            transform: rotateY(180deg);
        }

        .card-face {
            position: absolute;
            width: 100%; height: 100%;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            border-radius: 15px;
            overflow: hidden;
        }

        /* Card Back (Face Down) */
        .card-back {
            background: linear-gradient(145deg, #0a0e0c 0%, #050805 100%);
            border: 2px solid rgba(212,175,55,0.4);
            display: flex; justify-content: center; align-items: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.8), inset 0 0 40px rgba(0,0,0,0.5);
        }

        .card-back-design {
            width: 80%; height: 80%;
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 10px;
            display: flex; justify-content: center; align-items: center;
            position: relative;
        }

        .card-back-design::before {
            content: '';
            position: absolute; inset: 8px;
            border: 1px solid rgba(212,175,55,0.15);
            border-radius: 8px;
        }

        .card-back-design img {
            width: 50%;
            opacity: 0.2;
            filter: drop-shadow(0 0 10px rgba(212,175,55,0.3));
        }

        /* Shimmer Effect on Card Back */
        .card-back::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(
                120deg,
                transparent 20%,
                rgba(212,175,55,0.08) 40%,
                rgba(255,255,255,0.05) 50%,
                rgba(212,175,55,0.08) 60%,
                transparent 80%
            );
            background-size: 200% 100%;
            animation: shimmerCard 3s infinite linear;
        }
        @keyframes shimmerCard {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Card Front (Face Up) */
        .card-front {
            transform: rotateY(180deg);
            background: linear-gradient(160deg, #0c1210 0%, #050a08 50%, #0a0e0c 100%);
            border: 2px solid var(--gold);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 20px 15px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.9), 0 0 30px rgba(212,175,55,0.2);
        }

        .card-numeral {
            font-family: 'Playfair Display', serif;
            font-size: clamp(0.7rem, 2vw, 0.9rem);
            color: var(--gold);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 10px;
            opacity: 0.7;
        }

        .card-symbol {
            font-size: clamp(2.5rem, 7vw, 4rem);
            margin-bottom: 15px;
            filter: drop-shadow(0 0 15px rgba(212,175,55,0.5));
        }

        .card-name {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1rem, 3vw, 1.4rem);
            color: #fff;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .card-meaning {
            font-family: 'Courier New', monospace;
            font-size: clamp(0.55rem, 1.5vw, 0.7rem);
            color: #8b9ba8;
            letter-spacing: 1px;
            line-height: 1.6;
        }

        .card-line {
            width: 40px; height: 1px;
            background: var(--gold);
            margin: 12px 0;
            opacity: 0.5;
        }

        /* Hover on unflipped */
        .tarot-card:not(.flipped):hover .card-inner {
            transform: translateY(-10px) rotateX(5deg);
        }
        .tarot-card:not(.flipped):hover .card-back {
            box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 20px rgba(212,175,55,0.3);
            border-color: var(--gold-light);
        }

        /* Draw Button */
        .btn-draw {
            padding: 16px 50px;
            background: rgba(212,175,55,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid var(--gold);
            color: var(--gold);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 5px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            transform: translateY(20px);
        }
        .btn-draw:hover {
            background: var(--gold);
            color: #000;
            box-shadow: 0 15px 50px rgba(212,175,55,0.5);
            transform: translateY(-3px) scale(1.02);
        }
        .btn-draw:disabled {
            opacity: 0.3; pointer-events: none;
        }

        .btn-reset {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: underline;
            transition: 0.3s;
            display: none;
        }
        .btn-reset:hover { color: #fff; }

        /* Card Flip Burst Effect */
        .burst-container {
            position: fixed; inset: 0; pointer-events: none; z-index: 100;
        }
        .burst-particle {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--gold);
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .btn-back { top: 20px; left: 20px; padding: 8px 15px; font-size: 10px; }
            .codex-wrapper { gap: 20px; }
        }
    </style>
</head>
<body>

    <div class="ambient"></div>

    <!-- Floating Particles -->
    <div id="particleField"></div>

    <a href="/fitur" class="btn-back">
        <i class="fa-solid fa-chevron-left"></i> Exit Codex
    </a>

    <div class="codex-wrapper">
        <h1 class="codex-title" id="codexTitle">The Celestial Codex</h1>
        <p class="codex-subtitle" id="codexSubtitle">Tarik tiga kartu untuk mengungkap takdir Anda hari ini</p>

        <div class="card-stage" id="cardStage">
            <div class="tarot-card" id="card0" data-index="0">
                <div class="card-inner">
                    <div class="card-face card-back">
                        <div class="card-back-design"><img src="/images/logo-utuh.png" alt="Codex"></div>
                    </div>
                    <div class="card-face card-front" id="front0"></div>
                </div>
            </div>

            <div class="tarot-card" id="card1" data-index="1">
                <div class="card-inner">
                    <div class="card-face card-back">
                        <div class="card-back-design"><img src="/images/logo-utuh.png" alt="Codex"></div>
                    </div>
                    <div class="card-face card-front" id="front1"></div>
                </div>
            </div>

            <div class="tarot-card" id="card2" data-index="2">
                <div class="card-inner">
                    <div class="card-face card-back">
                        <div class="card-back-design"><img src="/images/logo-utuh.png" alt="Codex"></div>
                    </div>
                    <div class="card-face card-front" id="front2"></div>
                </div>
            </div>
        </div>

        <button class="btn-draw" id="btnDraw">Tarik Kartu Takdir</button>
        <button class="btn-reset" id="btnReset">Tarik Ulang</button>
    </div>

    <div class="burst-container" id="burstContainer"></div>

    <script src="/vendor/gsap/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {

        // === KARTU TAKDIR DATABASE — Mengambil dari PHP Backend ===
        const codex = <?= json_encode($cards, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        let drawnCards = [];
        let flippedCount = 0;

        // === AMBIENT PARTICLES ===
        const particleField = document.getElementById('particleField');
        for (let i = 0; i < 30; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.animationDuration = (Math.random() * 8 + 6) + 's';
            p.style.animationDelay = (Math.random() * 10) + 's';
            p.style.width = (Math.random() * 3 + 1) + 'px';
            p.style.height = p.style.width;
            particleField.appendChild(p);
        }

        // === ENTRANCE ANIMATION ===
        const tl = gsap.timeline();
        tl.to('#codexTitle', { opacity: 1, y: 0, duration: 1, ease: "power3.out" }, 0.3)
          .to('#codexSubtitle', { opacity: 1, y: 0, duration: 1, ease: "power3.out" }, 0.6)
          .to('.tarot-card', { opacity: 1, y: 0, duration: 0.8, stagger: 0.15, ease: "back.out(1.5)" }, 0.8)
          .to('#btnDraw', { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" }, 1.4);

        // === DRAW CARDS ===
        document.getElementById('btnDraw').addEventListener('click', () => {
            // Pilih 3 kartu acak unik
            const shuffled = [...codex].sort(() => Math.random() - 0.5);
            drawnCards = shuffled.slice(0, 3);

            // Isi konten masing-masing kartu
            drawnCards.forEach((card, i) => {
                const front = document.getElementById('front' + i);
                front.textContent = ''; // Kosongkan elemen secara aman
                
                const divNumeral = document.createElement('div');
                divNumeral.className = 'card-numeral';
                divNumeral.textContent = card.numeral;
                
                const divSymbol = document.createElement('div');
                divSymbol.className = 'card-symbol';
                divSymbol.textContent = card.symbol;
                
                const divLine1 = document.createElement('div');
                divLine1.className = 'card-line';
                
                const divName = document.createElement('div');
                divName.className = 'card-name';
                divName.textContent = card.name;
                
                const divLine2 = document.createElement('div');
                divLine2.className = 'card-line';
                
                const divMeaning = document.createElement('div');
                divMeaning.className = 'card-meaning';
                divMeaning.textContent = card.meaning;
                
                front.append(divNumeral, divSymbol, divLine1, divName, divLine2, divMeaning);
            });

            // Sembunyikan tombol draw
            document.getElementById('btnDraw').style.display = 'none';
            document.getElementById('codexSubtitle').innerText = 'Sentuh setiap kartu untuk mengungkap takdir Anda';

            // Aktifkan klik pada kartu
            document.querySelectorAll('.tarot-card').forEach(card => {
                card.style.cursor = 'pointer';
            });

            if (navigator.vibrate) navigator.vibrate(30);
        });

        // === CARD FLIP ===
        document.querySelectorAll('.tarot-card').forEach(card => {
            card.addEventListener('click', function() {
                if (this.classList.contains('flipped')) return;
                if (drawnCards.length === 0) return; // Belum ditarik

                this.classList.add('flipped');
                flippedCount++;

                // Haptic
                if (navigator.vibrate) navigator.vibrate([30, 50, 80]);

                // Burst Particles Effect
                createBurst(this);

                // Jika semua 3 sudah terbuka
                if (flippedCount >= 3) {
                    setTimeout(() => {
                        document.getElementById('codexSubtitle').innerText = '"Takdir telah berbicara. Simpan kebijaksanaannya."';
                        document.getElementById('codexSubtitle').style.color = '#d4af37';
                        document.getElementById('btnReset').style.display = 'inline-block';
                        gsap.from('#btnReset', { opacity: 0, y: 10, duration: 0.5 });
                    }, 1200);
                }
            });
        });

        // === RESET ===
        document.getElementById('btnReset').addEventListener('click', () => {
            flippedCount = 0;
            drawnCards = [];

            document.querySelectorAll('.tarot-card').forEach(card => {
                card.classList.remove('flipped');
            });

            document.getElementById('codexSubtitle').innerText = 'Tarik tiga kartu untuk mengungkap takdir Anda hari ini';
            document.getElementById('codexSubtitle').style.color = '#7b8e9b';

            setTimeout(() => {
                document.getElementById('btnDraw').style.display = 'inline-block';
                document.getElementById('btnReset').style.display = 'none';
            }, 600);
        });

        // === BURST PARTICLE EFFECT ===
        function createBurst(cardEl) {
            const rect = cardEl.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const container = document.getElementById('burstContainer');

            for (let i = 0; i < 20; i++) {
                const p = document.createElement('div');
                p.className = 'burst-particle';
                p.style.left = cx + 'px';
                p.style.top = cy + 'px';
                container.appendChild(p);

                const angle = (Math.PI * 2 / 20) * i;
                const dist = 60 + Math.random() * 80;
                const tx = Math.cos(angle) * dist;
                const ty = Math.sin(angle) * dist;

                gsap.to(p, {
                    x: tx,
                    y: ty,
                    opacity: 0,
                    scale: 0,
                    duration: 0.8 + Math.random() * 0.4,
                    ease: "power2.out",
                    onComplete: () => p.remove()
                });
            }
        }
    });
    </script>
</body>
</html>
