<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
The Nexus - Prediksi Eksekutif
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .nexus-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 1000px;
        margin: 0 auto;
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .nexus-header {
        margin-bottom: 40px;
    }
    .nexus-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 4px;
        margin-bottom: 10px;
        text-shadow: 0 0 30px rgba(212,175,55,0.3);
    }
    .nexus-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .nexus-sphere {
        width: 250px;
        height: 250px;
        border-radius: 50%;
        border: 1px solid rgba(212,175,55,0.3);
        margin: 40px auto;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: inset 0 0 50px rgba(212,175,55,0.05), 0 0 50px rgba(212,175,55,0.05);
        cursor: pointer;
        transition: 0.5s;
    }
    .nexus-sphere:hover {
        border-color: rgba(212,175,55,0.8);
        box-shadow: inset 0 0 80px rgba(212,175,55,0.1), 0 0 80px rgba(212,175,55,0.1);
    }
    .nexus-sphere::before {
        content: '';
        position: absolute;
        inset: 15px;
        border-radius: 50%;
        border: 1px dashed rgba(212,175,55,0.5);
        animation: rotate 20s linear infinite;
    }
    @keyframes rotate { 100% { transform: rotate(360deg); } }
    
    .nexus-core-text {
        font-family: 'Playfair Display', serif;
        color: #d4af37;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        z-index: 2;
    }

    .scanning-line {
        position: absolute;
        width: 100%;
        height: 2px;
        background: #d4af37;
        box-shadow: 0 0 15px #d4af37;
        top: 0;
        left: 0;
        opacity: 0;
        z-index: 3;
    }

    .match-results {
        display: none;
        width: 100%;
        margin-top: 50px;
    }

    .match-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .match-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        text-align: center;
        opacity: 0;
        transform: translateY(30px);
    }
    
    .match-percentage {
        position: absolute;
        top: 20px;
        right: 20px;
        font-family: 'Inter', sans-serif;
        font-size: 1.5rem;
        font-weight: 900;
        color: #d4af37;
    }
    .match-percentage span { font-size: 0.8rem; }

    .match-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 2px solid #d4af37;
        object-fit: cover;
        margin: 0 auto 20px;
        padding: 3px;
    }

    .match-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: var(--text-primary);
        margin-bottom: 5px;
    }
    
    .match-category {
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .btn-connect {
        background: transparent;
        color: #d4af37;
        border: 1px solid #d4af37;
        padding: 10px 25px;
        border-radius: 30px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-connect:hover {
        background: #d4af37;
        color: #000;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="nexus-wrapper">
    <div class="nexus-header reveal-up">
        <h1 class="nexus-title">The Nexus</h1>
        <p class="nexus-subtitle">Algoritma Analitik Menghubungkan Visi, Merajut Jaringan Bisnis Eksekutif Masa Depan Anda.</p>
    </div>

    <div class="nexus-sphere reveal-up" id="btnAnalyze" onclick="startAnalysis()">
        <div class="scanning-line" id="scanLine"></div>
        <div class="nexus-core-text" id="coreText">AKTIVASI<br>ANALISIS</div>
    </div>
    
    <div id="statusText" style="color:var(--text-secondary); font-family:monospace; letter-spacing:2px; font-size:0.85rem; height:20px;"></div>

    <div class="match-results" id="matchResults">
        <h3 style="font-family:'Playfair Display',serif; color:var(--text-primary); font-size:1.8rem; font-weight:normal; margin-bottom:10px;">Kolega Strategis Anda</h3>
        <div style="width:50px; height:2px; background:#d4af37; margin:0 auto;"></div>
        
        <div class="match-grid" id="matchGrid">
            <!-- Hasil di-render JS -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script>
    let isAnalyzing = false;

    async function startAnalysis() {
        if(isAnalyzing) return;
        isAnalyzing = true;

        const sphere = document.getElementById('btnAnalyze');
        const coreText = document.getElementById('coreText');
        const scanLine = document.getElementById('scanLine');
        const statusText = document.getElementById('statusText');
        const resultsBox = document.getElementById('matchResults');
        const grid = document.getElementById('matchGrid');

        // Reset UI
        resultsBox.style.display = 'none';
        grid.innerHTML = '';
        
        // Animasi Scanning
        coreText.innerHTML = "MENGANALISIS...";
        gsap.to(sphere, { scale: 1.1, duration: 0.5 });
        gsap.set(scanLine, { opacity: 1 });
        const scanAnim = gsap.to(scanLine, { top: "100%", duration: 1.5, repeat: -1, yoyo: true, ease: "sine.inOut" });

        const statuses = [
            "Mengekstraksi jejak linguistik...",
            "Membandingkan matriks kategori...",
            "Mengkalkulasi irisan visi & cita-cita...",
            "Menyinkronisasi konstelasi eksekutif..."
        ];

        let i = 0;
        const statusInterval = setInterval(() => {
            statusText.innerText = statuses[i % statuses.length];
            i++;
        }, 800);

        try {
            const res = await fetch('/nexus/calculate');
            const data = await res.json();
            
            clearInterval(statusInterval);
            
            // Artificial delay to make it feel "premium"
            setTimeout(() => {
                scanAnim.kill();
                gsap.to(scanLine, { opacity: 0, duration: 0.3 });
                gsap.to(sphere, { scale: 1, duration: 0.5, ease: "power2.out" });
                coreText.innerHTML = "SELESAI";
                statusText.innerText = "Sinkronisasi Berhasil. Memuat Hasil.";
                
                setTimeout(() => {
                    renderResults(data.data);
                }, 800);
            }, 2500);

        } catch (e) {
            clearInterval(statusInterval);
            statusText.innerText = "Koneksi Terputus.";
            coreText.innerHTML = "GAGAL";
            scanAnim.kill();
            isAnalyzing = false;
        }
    }

    function renderResults(matches) {
        const resultsBox = document.getElementById('matchResults');
        const grid = document.getElementById('matchGrid');
        
        resultsBox.style.display = 'block';
        document.getElementById('statusText').innerText = "";
        
        if (!matches || matches.length === 0) {
            grid.innerHTML = '<div style="color:var(--text-secondary); width:100%; grid-column:1/-1;">Belum ada data kolega yang memadai untuk analisis saat ini.</div>';
            isAnalyzing = false;
            return;
        }

        let html = '';
        matches.forEach(m => {
            const avatarUrl = m.foto_profil && m.foto_profil !== 'default.webp' ? '/uploads/profiles/'+m.foto_profil : 'https://ui-avatars.com/api/?name='+encodeURIComponent(m.nama_panggilan || m.nama_lengkap)+'&background=d4af37&color=000';
            html += `
                <div class="match-card">
                    <div class="match-percentage">${m.match_score}<span>%</span></div>
                    <img src="${avatarUrl}" class="match-avatar" alt="${m.nama_panggilan}">
                    <div class="match-name">${m.nama_panggilan || m.nama_lengkap}</div>
                    <div class="match-category">${m.syndicate_category || 'Independen'}</div>
                    <a href="/profil/${m.id}" class="btn-connect">Lihat Profil Eksekutif</a>
                </div>
            `;
        });

        grid.innerHTML = html;

        gsap.to('.match-card', {
            y: 0,
            opacity: 1,
            duration: 0.8,
            stagger: 0.2,
            ease: "back.out(1.2)"
        });

        isAnalyzing = false;
    }
</script>
<?= $this->endSection() ?>
