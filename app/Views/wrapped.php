<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Expedient Wrapped <?= esc($year) ?></title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --gold: #d4af37;
            --dark-gold: #b28a24;
            --bg-dark: #050505;
            --bg-darker: #010101;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background-color: var(--bg-dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
        }

        .wrapped-container {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 450px; /* Mobile focused */
            background: linear-gradient(135deg, var(--bg-darker) 0%, #111 100%);
            box-shadow: 0 0 50px rgba(0,0,0,0.8);
            overflow: hidden;
        }

        /* Progress Bar at top */
        .progress-container {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            gap: 5px;
            z-index: 100;
        }

        .progress-bar {
            flex: 1;
            height: 3px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: var(--gold);
        }

        /* Navigation overlay */
        .nav-overlay {
            position: absolute;
            top: 30px;
            bottom: 0;
            width: 50%;
            z-index: 15;
            cursor: pointer;
        }
        .nav-left { left: 0; }
        .nav-right { right: 0; }

        /* Story Slides */
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            opacity: 0;
            visibility: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 10;
            pointer-events: none;
        }
        .slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 20;
        }
        .slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }

        .slide-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--gold);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .slide-highlight {
            font-size: 5rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 0 20px rgba(212,175,55,0.5);
            margin: 20px 0;
            line-height: 1;
        }

        .slide-desc {
            font-size: 1.1rem;
            color: #ddd;
            line-height: 1.6;
        }

        .slide-icon {
            font-size: 4rem;
            color: var(--gold);
            margin-bottom: 30px;
            filter: drop-shadow(0 0 10px rgba(212,175,55,0.4));
        }

        .close-btn {
            position: absolute;
            top: 35px;
            right: 20px;
            color: #fff;
            font-size: 1.5rem;
            z-index: 100;
            cursor: pointer;
            text-decoration: none;
            opacity: 0.7;
        }

        .confetti-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 30;
        }

        .final-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
            width: 100%;
        }
        .final-stat {
            background: rgba(255,255,255,0.05);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(212,175,55,0.2);
        }
        .final-stat-val {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--gold);
        }
        .final-stat-label {
            font-size: 0.75rem;
            color: #aaa;
            text-transform: uppercase;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapped-container" id="wrappedContainer">
        
        <a href="/fitur" class="close-btn"><i class="fa-solid fa-xmark"></i></a>

        <div class="progress-container" id="progressContainer">
            <div class="progress-bar"><div class="progress-fill"></div></div>
            <div class="progress-bar"><div class="progress-fill"></div></div>
            <div class="progress-bar"><div class="progress-fill"></div></div>
            <div class="progress-bar"><div class="progress-fill"></div></div>
            <div class="progress-bar"><div class="progress-fill"></div></div>
        </div>

        <div class="nav-overlay nav-left" onclick="prevSlide()"></div>
        <div class="nav-overlay nav-right" onclick="nextSlide()"></div>

        <!-- Slide 1: Intro -->
        <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1534447677768-be436bb09401?q=80&w=1000&auto=format&fit=crop');">
            <i class="fa-solid fa-gem slide-icon gs-anim"></i>
            <h2 class="slide-title gs-anim"><?= cms_text('wrapped_intro_title', 'Tahun Ini...') ?></h2>
            <p class="slide-desc gs-anim"><?= cms_html('wrapped_intro_desc1', 'Anda telah melalui berbagai momen luar biasa bersama <strong>Expedient Generation</strong>.') ?></p>
            <p class="slide-desc gs-anim" style="margin-top:20px;"><?= cms_text('wrapped_intro_desc2', 'Mari kita lihat kembali jejak digital Anda di The Vault sepanjang') ?> <?= esc($year) ?>.</p>
        </div>

        <!-- Slide 2: Kehadiran -->
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1000&auto=format&fit=crop');">
            <h2 class="slide-title gs-anim"><?= cms_text('wrapped_login_title', 'Loyalitas Tanpa Batas') ?></h2>
            <div class="slide-highlight gs-anim"><?= $loginCount ?></div>
            <p class="slide-desc gs-anim"><?= cms_text('wrapped_login_desc1', 'Kali Anda mengunjungi The Vault tahun ini.') ?></p>
            <p class="slide-desc gs-anim" style="margin-top:20px; font-size:0.9rem; color:#aaa;"><?= cms_text('wrapped_login_desc2', 'Anda mengumpulkan') ?> <strong><?= number_format($totalPrestise) ?></strong> <?= cms_text('wrapped_login_desc3', 'poin prestise.') ?></p>
        </div>

        <!-- Slide 3: Baitul Maal -->
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1579621970795-87facc2f976d?q=80&w=1000&auto=format&fit=crop'); filter: grayscale(50%) hue-rotate(20deg);">
            <i class="fa-solid fa-coins slide-icon gs-anim"></i>
            <h2 class="slide-title gs-anim"><?= cms_text('wrapped_sedekah_title', 'Jariyah Abadi') ?></h2>
            <div class="slide-highlight gs-anim">Rp <?= number_format($totalSedekah, 0, ',', '.') ?></div>
            <p class="slide-desc gs-anim"><?= cms_text('wrapped_sedekah_desc1', 'Total kontribusi Anda di Baitul Maal.') ?></p>
            <p class="slide-desc gs-anim" style="margin-top:20px; font-size:0.9rem; color:#aaa;"><?= cms_text('wrapped_sedekah_desc2', 'Terima kasih telah menanam benih kebaikan bersama.') ?></p>
        </div>

        <!-- Slide 4: Interaksi -->
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1594954002661-8f55fc15d7de?q=80&w=1000&auto=format&fit=crop');">
            <h2 class="slide-title gs-anim"><?= cms_text('wrapped_interact_title', 'Sang Visioner') ?></h2>
            <p class="slide-desc gs-anim"><?= cms_text('wrapped_interact_desc1', 'Anda telah menanam') ?> <strong><?= $oracleCount ?></strong> <?= cms_text('wrapped_interact_desc2', 'pesan rahasia di Oracle,') ?></p>
            <p class="slide-desc gs-anim" style="margin-top:10px;"><?= cms_text('wrapped_interact_desc3', 'dan menyumbangkan suara di') ?> <strong><?= $majlisVotes ?></strong> <?= cms_text('wrapped_interact_desc4', 'topik Majlis Syura.') ?></p>
            <i class="fa-solid fa-bullhorn slide-icon gs-anim" style="margin-top:30px;"></i>
        </div>

        <!-- Slide 5: Summary -->
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=1000&auto=format&fit=crop');">
            <h2 class="slide-title gs-anim" style="font-size:2rem;"><?= cms_text('wrapped_summary_title', 'Expedient') ?> <?= esc($year) ?></h2>
            <div class="gs-anim" style="font-size:1.5rem; font-weight:bold; margin-bottom:20px;"><?= cms_text('wrapped_summary_entity', 'Entitas:') ?> <?= esc($user['nama_panggilan']) ?></div>
            
            <div class="final-grid gs-anim">
                <div class="final-stat">
                    <div class="final-stat-val"><?= $loginCount ?></div>
                    <div class="final-stat-label"><?= cms_text('wrapped_stat_visit', 'Kunjungan') ?></div>
                </div>
                <div class="final-stat">
                    <div class="final-stat-val"><?= number_format($totalPrestise) ?></div>
                    <div class="final-stat-label"><?= cms_text('wrapped_stat_poin', 'Poin Prestise') ?></div>
                </div>
                <div class="final-stat" style="grid-column: span 2;">
                    <div class="final-stat-val">Rp <?= number_format($totalSedekah, 0, ',', '.') ?></div>
                    <div class="final-stat-label"><?= cms_text('wrapped_stat_maal', 'Baitul Maal') ?></div>
                </div>
            </div>
            
            <button onclick="window.location.href='/fitur'" class="gs-anim" style="pointer-events: auto; margin-top:40px; padding:15px 30px; background:var(--gold); color:#000; font-weight:bold; border:none; border-radius:30px; letter-spacing:2px; font-family:'Inter'; cursor:pointer;"><?= cms_text('wrapped_btn_back', 'KEMBALI KE VAULT') ?></button>
            <canvas class="confetti-canvas" id="confettiCanvas"></canvas>
        </div>

    </div>

    <script src="/vendor/gsap/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const fills = document.querySelectorAll('.progress-fill');
        let slideTimer;
        const DURATION = 6000; // 6 detik per slide

        function initSlide(index) {
            // Reset semua
            slides.forEach(s => s.classList.remove('active'));
            gsap.killTweensOf('.gs-anim');
            
            // Set slide aktif
            slides[index].classList.add('active');
            
            // Animasi elemen dalam slide
            const anims = slides[index].querySelectorAll('.gs-anim');
            gsap.fromTo(anims, 
                { y: 30, opacity: 0 }, 
                { y: 0, opacity: 1, duration: 0.8, stagger: 0.2, ease: "power2.out" }
            );

            // Confetti untuk slide terakhir
            if (index === slides.length - 1) {
                setTimeout(() => {
                    var myCanvas = document.getElementById('confettiCanvas');
                    var myConfetti = confetti.create(myCanvas, {
                      resize: true,
                      useWorker: true
                    });
                    myConfetti({
                      particleCount: 100,
                      spread: 160,
                      colors: ['#d4af37', '#ffffff', '#b28a24']
                    });
                }, 800);
            }
        }

        function fillProgress(index) {
            // Reset fill
            fills.forEach((fill, i) => {
                gsap.killTweensOf(fill);
                if (i < index) fill.style.width = '100%';
                else if (i > index) fill.style.width = '0%';
            });

            // Animate current
            fills[index].style.width = '0%';
            gsap.to(fills[index], { width: '100%', duration: DURATION / 1000, ease: 'none', onComplete: nextSlide });
        }

        function startStory() {
            clearTimeout(slideTimer);
            initSlide(currentSlide);
            fillProgress(currentSlide);
        }

        function nextSlide() {
            if (currentSlide < slides.length - 1) {
                currentSlide++;
                startStory();
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                startStory();
            }
        }

        // Start 
        document.addEventListener('DOMContentLoaded', startStory);
    </script>
</body>
</html>
