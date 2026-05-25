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
        position: relative;
    }
    
    .btn-back {
        position: absolute;
        top: 40px;
        left: 20px;
        background: rgba(212, 175, 55, 0.1);
        color: #d4af37;
        border: 1px solid rgba(212, 175, 55, 0.3);
        padding: 10px 20px;
        border-radius: 30px;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-decoration: none;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 10;
    }
    .btn-back:hover {
        background: #d4af37;
        color: #000;
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
    }

    .nexus-header {
        margin-bottom: 40px;
        margin-top: 20px;
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

    .nexus-sphere-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 30px;
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
        transform: scale(1.05);
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
        margin-top: 30px;
    }

    .match-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .match-card {
        background: linear-gradient(145deg, rgba(20,20,20,0.8), rgba(5,5,5,0.9));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(212, 175, 55, 0.2);
        border-radius: 20px;
        padding: 40px 30px;
        position: relative;
        overflow: hidden;
        text-align: center;
        opacity: 0;
        transform: translateY(30px);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s;
    }
    
    .match-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.5), inset 0 0 30px rgba(212, 175, 55, 0.05);
        border-color: rgba(212, 175, 55, 0.5);
    }
    
    .match-percentage {
        position: absolute;
        top: 20px;
        right: 20px;
        font-family: 'Space Mono', monospace;
        font-size: 1.5rem;
        font-weight: 700;
        color: #d4af37;
        background: rgba(212, 175, 55, 0.1);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }
    .match-percentage span { font-size: 0.8rem; }

    .match-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        border: 2px solid #d4af37;
        object-fit: cover;
        margin: 0 auto 20px;
        padding: 4px;
        background: #000;
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
    }

    .match-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        color: #fff;
        margin-bottom: 5px;
        text-shadow: 0 2px 5px rgba(0,0,0,0.5);
    }
    
    .match-category {
        font-size: 0.75rem;
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 25px;
    }

    .btn-connect {
        background: transparent;
        color: #d4af37;
        border: 1px solid #d4af37;
        padding: 12px 30px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
        width: 100%;
        box-sizing: border-box;
    }
    .btn-connect:hover {
        background: #d4af37;
        color: #000;
        box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
    }

    .btn-reanalyze {
        display: none;
        background: rgba(212, 175, 55, 0.1);
        color: #d4af37;
        border: 1px solid rgba(212, 175, 55, 0.5);
        padding: 10px 25px;
        border-radius: 30px;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 20px auto 0;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-reanalyze:hover {
        background: #d4af37;
        color: #000;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="nexus-wrapper">
    <a href="/fitur" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <div class="nexus-header reveal-up">
        <h1 class="nexus-title"><?= cms_text('nexus_title', 'The Nexus') ?></h1>
        <p class="nexus-subtitle"><?= cms_text('nexus_subtitle', 'Algoritma Analitik Menghubungkan Visi, Merajut Jaringan Bisnis Eksekutif Masa Depan Anda.') ?></p>
    </div>

    <div class="nexus-sphere-container" id="sphereContainer">
        <div class="nexus-sphere reveal-up" id="btnAnalyze" onclick="startAnalysis()">
            <div class="scanning-line" id="scanLine"></div>
            <div class="nexus-core-text" id="coreText"><?= cms_html('nexus_core', 'AKTIVASI<br>ANALISIS') ?></div>
        </div>
        <div id="statusText" style="color:var(--text-secondary); font-family:monospace; letter-spacing:2px; font-size:0.85rem; height:20px; margin-top:20px;"></div>
    </div>
    
    <button class="btn-reanalyze" id="btnReanalyze" onclick="forceReanalyze()">
        <i class="fa-solid fa-rotate"></i> <?= cms_text('nexus_btn_reanalyze', 'Analisis Ulang') ?>
    </button>

    <div class="match-results" id="matchResults">
        <h3 style="font-family:'Playfair Display',serif; color:var(--text-primary); font-size:2rem; font-weight:normal; margin-bottom:15px;"><?= cms_text('nexus_results_title', 'Kolega Strategis Anda') ?></h3>
        <div style="width:60px; height:3px; background:#d4af37; margin:0 auto; border-radius:3px; box-shadow:0 0 10px #d4af37;"></div>
        
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
    const STORAGE_KEY = 'nexus_results_cache';

    document.addEventListener('DOMContentLoaded', () => {
        // Cek jika ada state tersimpan
        const cachedResults = sessionStorage.getItem(STORAGE_KEY);
        if (cachedResults) {
            try {
                const matches = JSON.parse(cachedResults);
                renderResultsInstantly(matches);
            } catch (e) {
                sessionStorage.removeItem(STORAGE_KEY);
            }
        }
    });

    function forceReanalyze() {
        sessionStorage.removeItem(STORAGE_KEY);
        const sphereContainer = document.getElementById('sphereContainer');
        const resultsBox = document.getElementById('matchResults');
        const btnReanalyze = document.getElementById('btnReanalyze');
        
        sphereContainer.style.display = 'flex';
        resultsBox.style.display = 'none';
        btnReanalyze.style.display = 'none';
        
        startAnalysis();
    }

    async function startAnalysis() {
        if(isAnalyzing) return;
        isAnalyzing = true;

        const sphere = document.getElementById('btnAnalyze');
        const coreText = document.getElementById('coreText');
        const scanLine = document.getElementById('scanLine');
        const statusText = document.getElementById('statusText');
        const resultsBox = document.getElementById('matchResults');
        const grid = document.getElementById('matchGrid');
        const btnReanalyze = document.getElementById('btnReanalyze');

        // Reset UI
        resultsBox.style.display = 'none';
        btnReanalyze.style.display = 'none';
        grid.innerHTML = '';
        
        // Animasi Scanning
        coreText.innerHTML = "<?= cms_raw('nexus_core_analyzing', 'MENGANALISIS...') ?>";
        gsap.to(sphere, { scale: 1.1, duration: 0.5 });
        gsap.set(scanLine, { opacity: 1 });
        const scanAnim = gsap.to(scanLine, { top: "100%", duration: 1.5, repeat: -1, yoyo: true, ease: "sine.inOut" });

        const statuses = [
            "<?= cms_raw('nexus_status_1', 'Mengekstraksi jejak linguistik...') ?>",
            "<?= cms_raw('nexus_status_2', 'Membandingkan matriks kategori...') ?>",
            "<?= cms_raw('nexus_status_3', 'Mengkalkulasi irisan visi & cita-cita...') ?>",
            "<?= cms_raw('nexus_status_4', 'Menyinkronisasi konstelasi eksekutif...') ?>"
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
                coreText.innerHTML = "<?= cms_raw('nexus_core_done', 'SELESAI') ?>";
                statusText.innerText = "<?= cms_raw('nexus_status_success', 'Sinkronisasi Berhasil. Memuat Hasil.') ?>";
                
                // Simpan ke Session Storage agar state terjaga saat navigasi kembali
                if(data.status === 'success') {
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data.data));
                }

                setTimeout(() => {
                    renderResults(data.data);
                }, 800);
            }, 2500);

        } catch (e) {
            clearInterval(statusInterval);
            statusText.innerText = "<?= cms_raw('nexus_status_error', 'Koneksi Terputus.') ?>";
            coreText.innerHTML = "<?= cms_raw('nexus_core_fail', 'GAGAL') ?>";
            scanAnim.kill();
            isAnalyzing = false;
        }
    }

    function renderResults(matches) {
        buildResultGrid(matches);
        
        // Hide sphere, show results & reanalyze button
        const sphereContainer = document.getElementById('sphereContainer');
        gsap.to(sphereContainer, { opacity: 0, height: 0, duration: 0.5, onComplete: () => {
            sphereContainer.style.display = 'none';
            document.getElementById('matchResults').style.display = 'block';
            document.getElementById('btnReanalyze').style.display = 'inline-block';
            
            gsap.fromTo('.match-card', 
                { y: 50, opacity: 0 }, 
                { y: 0, opacity: 1, duration: 0.8, stagger: 0.15, ease: "back.out(1.2)" }
            );
        }});
        
        isAnalyzing = false;
    }

    function renderResultsInstantly(matches) {
        document.getElementById('sphereContainer').style.display = 'none';
        document.getElementById('matchResults').style.display = 'block';
        document.getElementById('btnReanalyze').style.display = 'inline-block';
        buildResultGrid(matches);
        
        gsap.set('.match-card', { y: 0, opacity: 1 });
    }

    function buildResultGrid(matches) {
        const grid = document.getElementById('matchGrid');
        if (!matches || matches.length === 0) {
            grid.innerHTML = '<div style="color:var(--text-secondary); width:100%; grid-column:1/-1; padding: 40px; border:1px solid rgba(255,255,255,0.1); border-radius:15px; background:rgba(0,0,0,0.3);"><?= cms_raw('nexus_empty_data', 'Belum ada data kolega yang memadai untuk analisis saat ini.') ?></div>';
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
                    <div class="match-category">${m.syndicate_category || '<?= cms_raw('nexus_lbl_independen', 'Independen') ?>'}</div>
                    <a href="/profil/${m.public_token}" class="btn-connect"><?= cms_raw('nexus_btn_profile', 'Lihat Profil Eksekutif') ?></a>
                </div>
            `;
        });

        grid.innerHTML = html;
    }
</script>
<?= $this->endSection() ?>
