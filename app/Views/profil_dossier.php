<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dossier - <?= esc($user['nama_panggilan']) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700;900&family=Space+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --pure-gold: #ffd700; 
            --dark-gold: #d4af37; 
            --bg-noir: #050505; 
            --glass-bg: rgba(20, 20, 20, 0.7); 
            --glass-border: rgba(212, 175, 55, 0.3); 
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            background-color: var(--bg-noir); 
            color: #fff; 
            font-family: 'Inter', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            overflow-x: hidden; 
            background-image: radial-gradient(circle at 50% 50%, #1a1a1a 0%, #050505 100%);
        }

        /* Hacker Loader */
        #hacker-loader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: var(--bg-noir);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        .loader-text {
            font-family: 'Space Mono', monospace;
            color: var(--dark-gold);
            font-size: 1.2rem;
            letter-spacing: 5px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .progress-bar {
            width: 250px;
            height: 2px;
            background: rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }
        .progress-fill {
            position: absolute;
            top: 0; left: 0; height: 100%;
            width: 0%;
            background: var(--dark-gold);
            box-shadow: 0 0 10px var(--dark-gold);
            transition: width 0.1s linear;
        }

        /* Main Content Container */
        .mobile-container {
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            padding: 30px 20px;
            position: relative;
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .mobile-container.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .btn-exit {
            color: var(--dark-gold);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .btn-exit:hover { color: #fff; text-shadow: 0 0 10px var(--dark-gold); }

        /* Dossier Card (Glassmorphism) */
        .dossier-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5), inset 0 0 40px rgba(212,175,55,0.05);
            position: relative;
            overflow: hidden;
        }
        
        /* Grid background effect inside card */
        .dossier-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: linear-gradient(rgba(212,175,55,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(212,175,55,0.05) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.5;
            z-index: 0;
            pointer-events: none;
        }

        .avatar-container {
            width: 140px;
            height: 140px;
            margin: 0 auto 25px;
            position: relative;
            z-index: 2;
            border-radius: 50%;
            padding: 5px;
            background: linear-gradient(135deg, var(--dark-gold), transparent);
            animation: pulse-ring 4s infinite alternate;
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 15px rgba(212,175,55,0.2); }
            100% { box-shadow: 0 0 30px rgba(212,175,55,0.6); }
        }
        
        .avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--bg-noir);
        }

        .title-badge {
            background: linear-gradient(90deg, transparent, rgba(212,175,55,0.2), transparent);
            color: var(--dark-gold);
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 4px;
            padding: 5px 0;
            margin-bottom: 15px;
            text-transform: uppercase;
            z-index: 2;
            position: relative;
        }

        .name {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #fff;
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            z-index: 2;
            position: relative;
        }

        .user-id {
            color: var(--dark-gold);
            font-family: 'Space Mono', monospace;
            margin-bottom: 25px;
            letter-spacing: 2px;
            font-size: 0.9rem;
            z-index: 2;
            position: relative;
        }

        .motivation {
            font-style: italic;
            color: #aaa;
            line-height: 1.6;
            margin-bottom: 35px;
            padding: 0 10px;
            z-index: 2;
            position: relative;
            font-weight: 300;
        }
        .motivation::before, .motivation::after {
            content: '"';
            color: var(--dark-gold);
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            line-height: 0;
            vertical-align: -0.2em;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            z-index: 2;
            position: relative;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            border-radius: 12px;
            text-decoration: none;
            font-family: 'Space Mono', monospace;
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: rgba(212,175,55,0.1);
            border: 1px solid var(--dark-gold);
            color: var(--dark-gold);
        }
        .btn-primary:hover {
            background: var(--dark-gold);
            color: #000;
            box-shadow: 0 0 20px rgba(212,175,55,0.4);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.3);
        }

        @media (min-width: 768px) {
            .mobile-container { max-width: 600px; }
            .actions { grid-template-columns: 1fr 1fr; }
            .btn-action:first-child { grid-column: 1 / -1; }
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div id="hacker-loader">
        <div class="loader-text" id="decrypt-text">INITIALIZING...</div>
        <div class="progress-bar"><div class="progress-fill" id="progress"></div></div>
    </div>

    <div class="mobile-container" id="main-content">
        <div class="header">
            <!-- Gunakan javascript history.back() agar bisa kembali ke Nexus atau asal rute yang benar -->
            <a href="javascript:void(0)" onclick="window.history.length > 1 ? window.history.back() : window.location.href='/fitur';" class="btn-exit">
                <i class="fa-solid fa-arrow-left"></i> RETURN
            </a>
            <div style="font-family:'Space Mono'; font-size:10px; color:var(--dark-gold); letter-spacing: 2px;">SECURE VERIFIED <i class="fa-solid fa-check"></i></div>
        </div>
        
        <div class="dossier-card" id="tilt-card">
            <div class="avatar-container">
                <img src="<?= !empty($user['foto_profil']) && $user['foto_profil'] != 'default.webp' ? base_url('uploads/profiles/'.$user['foto_profil']) : 'https://ui-avatars.com/api/?name='.urlencode($user['nama_panggilan']).'&background=d4af37&color=000' ?>" class="avatar" alt="Avatar">
            </div>

            <div class="title-badge">SOVEREIGN ENTITY</div>
            
            <div class="name" id="hacker-name" data-value="<?= esc($user['nama_lengkap']) ?>">
                <!-- Teks ini akan di-dekripsi oleh JS -->
                <?= esc($user['nama_lengkap']) ?>
            </div>
            
            <div class="user-id">EXP-<?= sprintf('%03d', $user['id']) ?></div>
            
            <p class="motivation">
                <?= esc($user['motivasi_hidup'] ?? 'Merangkai baris kode, membangun fondasi masa depan.') ?>
            </p>

            <div class="actions">
                <a href="/chat/personal/<?= $user['id'] ?>" class="btn-action btn-primary">
                    <i class="fa-solid fa-comment-dots"></i> SECURE COMMS
                </a>
                <a href="<?= !empty($user['akun_ig']) ? 'https://instagram.com/'.esc($user['akun_ig']) : '#' ?>" target="_blank" class="btn-action btn-secondary" <?= empty($user['akun_ig']) ? 'style="opacity:0.5; cursor:not-allowed;"' : '' ?>>
                    <i class="fa-brands fa-instagram"></i> INSTAGRAM
                </a>
                <a href="<?= base_url('download_vcard/' . $user['public_token']) ?>" class="btn-action btn-secondary">
                    <i class="fa-solid fa-address-card"></i> SAVE DOSSIER
                </a>
            </div>
        </div>
    </div>

    <script src="/vendor/gsap/gsap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const loader = document.getElementById('hacker-loader');
            const progress = document.getElementById('progress');
            const text = document.getElementById('decrypt-text');
            const mainContent = document.getElementById('main-content');
            
            const strings = ["DECRYPTING DATA...", "BYPASSING FIREWALL...", "ACCESS GRANTED."];
            const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*";
            
            let progressValue = 0;
            let strIndex = 0;

            // Efek Loading Bar & Teks Hacker
            const loadInterval = setInterval(() => {
                progressValue += Math.random() * 15;
                if(progressValue > 100) progressValue = 100;
                progress.style.width = progressValue + '%';
                
                if (progressValue > 30 && strIndex === 0) { strIndex++; text.innerText = strings[strIndex]; }
                if (progressValue > 70 && strIndex === 1) { strIndex++; text.innerText = strings[strIndex]; }
                
                if (progressValue === 100) {
                    clearInterval(loadInterval);
                    setTimeout(() => {
                        loader.style.opacity = '0';
                        setTimeout(() => {
                            loader.style.display = 'none';
                            mainContent.classList.add('visible');
                            decryptName();
                        }, 500);
                    }, 400);
                }
            }, 80);

            // Efek dekripsi nama saat kartu muncul
            function decryptName() {
                const nameEl = document.getElementById('hacker-name');
                const targetText = nameEl.getAttribute('data-value');
                let iterations = 0;
                
                const interval = setInterval(() => {
                    nameEl.innerText = targetText.split("").map((letter, index) => {
                        if(index < iterations) return targetText[index];
                        return letters[Math.floor(Math.random() * letters.length)];
                    }).join("");
                    
                    if(iterations >= targetText.length){
                        clearInterval(interval);
                    }
                    iterations += 1 / 3;
                }, 30);
            }

            // Simple 3D Tilt Effect on mousemove (desktop only)
            const card = document.getElementById('tilt-card');
            if(window.innerWidth > 768) {
                document.addEventListener('mousemove', (e) => {
                    const xAxis = (window.innerWidth / 2 - e.pageX) / 40;
                    const yAxis = (window.innerHeight / 2 - e.pageY) / 40;
                    card.style.transform = `perspective(1000px) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
                });
                
                document.addEventListener('mouseenter', () => {
                    card.style.transition = 'none';
                });
                
                document.addEventListener('mouseleave', () => {
                    card.style.transition = 'transform 0.5s ease';
                    card.style.transform = `perspective(1000px) rotateY(0deg) rotateX(0deg)`;
                });
            }
        });
    </script>
</body>
</html>