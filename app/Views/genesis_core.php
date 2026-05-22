<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>The Genesis Core - Manifestasi Entitas</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --genesis-gold: #d4af37;
            --genesis-dark: #020202;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            background-color: var(--genesis-dark);
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

        .genesis-wrapper {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #canvas1 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            touch-action: none; /* Prevent scrolling on mobile while swiping */
        }

        .hud-overlay {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            pointer-events: none; /* Let clicks pass through to canvas mostly */
        }

        .hud-text {
            font-family: 'Courier New', monospace;
            color: rgba(212,175,55,0.7);
            font-size: 0.8rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            text-align: center;
            text-shadow: 0 0 10px rgba(0,0,0,0.8);
            animation: pulseText 2s infinite alternate;
        }

        @keyframes pulseText {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .btn-singularity {
            pointer-events: auto; /* Enable click */
            background: rgba(212, 175, 55, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid var(--genesis-gold);
            color: var(--genesis-gold);
            padding: 15px 40px;
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 4px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 0 20px rgba(0,0,0,0.8);
        }

        .btn-singularity:hover {
            background: var(--genesis-gold);
            color: #000;
            box-shadow: 0 0 40px rgba(212, 175, 55, 0.6);
            transform: scale(1.05);
        }

        /* Final Message Reveal */
        .revelation-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            text-align: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 2s ease;
        }

        .revelation-box.show {
            opacity: 1;
            pointer-events: auto;
        }

        .rev-title {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: clamp(2.5rem, 6vw, 5rem);
            text-shadow: 0 0 30px var(--genesis-gold);
            margin: 0;
            letter-spacing: 8px;
            text-transform: uppercase;
        }

        .rev-subtitle {
            font-family: 'Courier New', monospace;
            color: #d4af37;
            font-size: 1rem;
            letter-spacing: 5px;
            margin-top: 15px;
        }

        .btn-return {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 30px;
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 30px;
            transition: 0.3s;
        }
        .btn-return:hover {
            background: #fff;
            color: #000;
        }

        @media (max-width: 768px) {
            .btn-back-vault { top: 20px; left: 20px; padding: 8px 15px; font-size: 10px; }
        }
    </style>
</head>
<body>

    <a href="/fitur" class="btn-back-vault">
        <i class="fa-solid fa-chevron-left"></i> Exit Genesis
    </a>

    <div class="genesis-wrapper">
        <canvas id="canvas1"></canvas>
        
        <div class="hud-overlay" id="hudOverlay">
            <div class="hud-text">Usap layar untuk mendisrupsi partikel<br>Biarkan untuk membentuk identitas</div>
            <form action="/genesis/log" method="POST">
                <?= csrf_field() ?>
                <button type="button" class="btn-singularity" id="btnSingularity">Initiate Singularity</button>
            </form>
        </div>

        <div class="revelation-box" id="revelationBox">
            <h1 class="rev-title">WE ARE ONE</h1>
            <div class="rev-subtitle">Ribuan entitas, satu kekuatan tak tertembus.</div>
            <a href="/fitur" class="btn-return">Kembali ke Vault</a>
        </div>
    </div>

    <script src="/vendor/gsap/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const canvas = document.getElementById('canvas1');
        const ctx = canvas.getContext('2d', { willReadFrequently: true });
        
        // Fit canvas to screen
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let particleArray = [];
        let adjustX = 0;
        let adjustY = 0;

        // Mouse/Touch Interaction Area
        const mouse = {
            x: null,
            y: null,
            radius: 80 // Radius tolakan
        };

        window.addEventListener('mousemove', function(event) {
            mouse.x = event.x;
            mouse.y = event.y;
        });

        window.addEventListener('touchmove', function(event) {
            mouse.x = event.touches[0].clientX;
            mouse.y = event.touches[0].clientY;
        });

        window.addEventListener('mouseleave', function() {
            mouse.x = undefined;
            mouse.y = undefined;
        });

        window.addEventListener('touchend', function() {
            mouse.x = undefined;
            mouse.y = undefined;
        });

        let isSingularity = false;

        // The Particle Class
        class Particle {
            constructor(x, y, color) {
                // Posisi awal tersebar acak agar efek merakitnya dramatis saat load
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.baseX = x; // Titik target (membentuk logo)
                this.baseY = y;
                this.size = (Math.random() * 1.5) + 0.5; // Ukuran bervariasi agar elegan
                this.color = color;
                this.density = (Math.random() * 30) + 1; // Menentukan seberapa cepat merespon
            }

            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.closePath();
                ctx.fill();
            }

            update() {
                if (isSingularity) return; // Singularity diurus oleh fungsi terpisah

                // Jarak antara partikel dan target awalnya (baseX, baseY)
                let dx = mouse.x - this.x;
                let dy = mouse.y - this.y;
                let distance = Math.sqrt(dx * dx + dy * dy);
                
                // Logika Fisika Tolakan (Repulsion)
                let forceDirectionX = dx / distance;
                let forceDirectionY = dy / distance;
                let maxDistance = mouse.radius;
                let force = (maxDistance - distance) / maxDistance;
                let directionX = forceDirectionX * force * this.density;
                let directionY = forceDirectionY * force * this.density;

                if (distance < mouse.radius) {
                    // Dorong menjauh (dikurangi posisinya)
                    this.x -= directionX;
                    this.y -= directionY;
                } else {
                    // Efek Pegas (Spring back) menuju posisi base (logo)
                    if (this.x !== this.baseX) {
                        let dx = this.x - this.baseX;
                        this.x -= dx / 10; // Kecepatan balik
                    }
                    if (this.y !== this.baseY) {
                        let dy = this.y - this.baseY;
                        this.y -= dy / 10;
                    }
                }
            }
        }

        // Inisialisasi Partikel dari Gambar Logo
        function init() {
            particleArray = [];
            
            const image = new Image();
            image.src = '/images/logo-utuh.png'; 
            
            image.onload = function() {
                // Tentukan ukuran logo di layar
                const isMobile = window.innerWidth <= 768;
                const logoWidth = isMobile ? 250 : 400; 
                const scale = logoWidth / image.width;
                const logoHeight = image.height * scale;
                
                // Hitung koordinat tengah
                const startX = (canvas.width - logoWidth) / 2;
                const startY = (canvas.height - logoHeight) / 2 - 50; // Agak ke atas sedikit
                
                // Buat canvas sementara untuk membaca pixel
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');
                tempCanvas.width = logoWidth;
                tempCanvas.height = logoHeight;
                
                tempCtx.drawImage(image, 0, 0, logoWidth, logoHeight);
                const pixels = tempCtx.getImageData(0, 0, logoWidth, logoHeight).data;

                // Kerapatan pixel yang dibaca (Semakin kecil = makin banyak partikel tapi berat)
                // Desktop bisa handle step 3, Mobile step 4 atau 5
                const step = isMobile ? 5 : 3;

                for (let y = 0; y < logoHeight; y += step) {
                    for (let x = 0; x < logoWidth; x += step) {
                        const index = (y * logoWidth + x) * 4;
                        const alpha = pixels[index + 3];
                        
                        if (alpha > 128) {
                            // Ambil warna pixel dari gambar asli
                            const r = pixels[index];
                            const g = pixels[index + 1];
                            const b = pixels[index + 2];
                            // Jadikan warnanya sedikit lebih emas/menyala untuk VVIP feel
                            const color = `rgb(${r}, ${g}, ${b})`;
                            
                            // Posisi absolut partikel di layar
                            let positionX = x + startX;
                            let positionY = y + startY;
                            
                            particleArray.push(new Particle(positionX, positionY, color));
                        }
                    }
                }
            };
        }

        init();

        // Game Loop
        function animate() {
            if (!isSingularity) {
                // Efek jejak (Trail effect) yang mewah (Tidak dihapus 100%, tapi ditimpa transparansi)
                ctx.fillStyle = 'rgba(2, 2, 2, 0.3)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                for (let i = 0; i < particleArray.length; i++) {
                    particleArray[i].draw();
                    particleArray[i].update();
                }
            }
            requestAnimationFrame(animate);
        }
        animate();

        // Handle Resize
        window.addEventListener('resize', function() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            init(); // Rebuild partikel berdasarkan posisi layar baru
        });

        // ==========================================
        // THE SINGULARITY EVENT (Animasi Puncak)
        // ==========================================
        const btnSingularity = document.getElementById('btnSingularity');
        
        btnSingularity.addEventListener('click', () => {
            if (isSingularity) return;
            isSingularity = true;

            // Sembunyikan HUD
            document.getElementById('hudOverlay').style.opacity = 0;
            
            // Haptic Feedback dramatis
            if(navigator.vibrate) navigator.vibrate([50, 100, 50, 100, 200, 500]);

            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;

            // Tahap 1: Implosion (Tersedot ke tengah)
            gsap.to(particleArray, {
                x: centerX,
                y: centerY,
                duration: 2,
                ease: "power2.in",
                stagger: {
                    amount: 1,
                    from: "random"
                },
                onUpdate: function() {
                    ctx.fillStyle = 'rgba(2, 2, 2, 0.2)';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    for (let i = 0; i < particleArray.length; i++) {
                        particleArray[i].draw();
                    }
                },
                onComplete: function() {
                    // Tahap 2: Shockwave Explosion
                    if(navigator.vibrate) navigator.vibrate([1000]); // Getaran panjang ledakan
                    
                    // Buat Flash putih
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    gsap.to(particleArray, {
                        x: () => centerX + (Math.random() - 0.5) * window.innerWidth * 2,
                        y: () => centerY + (Math.random() - 0.5) * window.innerHeight * 2,
                        duration: 2,
                        ease: "expo.out",
                        onUpdate: function() {
                            ctx.fillStyle = 'rgba(2, 2, 2, 0.4)';
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            for (let i = 0; i < particleArray.length; i++) {
                                particleArray[i].draw();
                            }
                        },
                        onComplete: function() {
                            // Tampilkan Pesan Rahasia
                            document.getElementById('revelationBox').classList.add('show');
                            
                            // Hentikan partikel
                            ctx.clearRect(0,0, canvas.width, canvas.height);

                            // Submit form logging ke backend
                            setTimeout(() => {
                                btnSingularity.closest('form').submit();
                            }, 3000);
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
