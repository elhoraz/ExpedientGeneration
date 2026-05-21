<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Museum Utama Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="/assets/css/beranda.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="premium-loader" id="loader">
    <div class="circular-loader">
        <div class="loader-ring"></div>
        <div class="loader-ring-active"></div>
        <div class="loader-percent" id="loadPercent">0%</div>
    </div>
    <div class="loader-lore" id="loaderLore">"Sovereign Protocol Initiated..."</div>
    <div class="loader-status">Mempersiapkan Ruang Pameran</div>
</div>

<div class="mecha-stage" id="stage">
    
    <div class="god-rays" id="godRays"></div>
    <div class="monumental-text" id="monumentalText">E X P E D I E N T</div>

    <canvas id="constellationCanvas"></canvas>

    <div class="logo-utuh-container hover-trigger" id="fullLogoBox">
        <canvas id="fullLogoCanvas" width="800" height="450"></canvas>
        <img src="/images/logo-utuh.png" id="fullLogoStatic" alt="Static">
    </div>
    
    <div class="shards-universe" id="shardsContainer"></div>
    <div class="merge-flash" id="flashEffect"></div>
    
    <div class="hud-controls">
        <div class="hud-hint" id="hudHint"><i class="fa-solid fa-arrows-left-right"></i> Tahan & Geser Untuk Memutar</div>
        <button class="btn-mecha hover-trigger" id="btnAction"><i class="fa-solid fa-expand"></i> Pencar Formasi</button>
    </div>

    <i class="fa-solid fa-chevron-down scroll-indicator" style="position: absolute; bottom: 5vh; left: 50%; transform: translateX(-50%); color: #d4af37; font-size: 2rem; animation: bounceIndicator 2s infinite; z-index: 20; opacity: 0.7;"></i>
</div>

<div class="phil-modal" id="philModal">
    <div class="phil-content">
        <h2 class="phil-title" id="modalTitle">Judul</h2>
        <p class="phil-desc" id="modalDesc">Deskripsi filosofi.</p>
        <button class="btn-mecha hover-trigger" style="margin-top:25px; padding:10px 25px; font-size:0.8rem;" onclick="closeModal()">TUTUP</button>
    </div>
</div>

<main class="museum-halls">

    <section class="hall-section epigraph-section">
        <h1 class="grand-text reveal-up">
            Kami bukan sekadar angkatan.<br>
            Kami adalah <span class="highlight-gold">barisan pelopor</span> yang lahir dari rahim Arrisalah,<br>
            dibentuk oleh waktu, dipersatukan oleh takdir.
        </h1>
    </section>

    <section class="hall-section">
        <div class="stats-grid">
            <div class="stat-card glass-panel reveal-up">
                <h3 class="stat-number"><span class="gsap-counter" data-target="124">0</span>+</h3>
                <p class="stat-label">Entitas Expedient</p>
            </div>
            <div class="stat-card glass-panel reveal-up">
                <h3 class="stat-number"><span class="gsap-counter" data-target="34">0</span></h3>
                <p class="stat-label">Wilayah Sebaran</p>
            </div>
            <div class="stat-card glass-panel reveal-up">
                <h3 class="stat-number"><span class="gsap-counter" data-target="2025">0</span></h3>
                <p class="stat-label">Tahun Kebangkitan</p>
            </div>
        </div>
    </section>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Lorong Kenangan</h2>
        <div class="horizontal-scroll-snap reveal-up">
            <div class="echo-frame">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=400&auto=format&fit=crop" alt="Kenangan">
                <div class="echo-caption">Kenangan Masa Perjuangan</div>
            </div>
            <div class="echo-frame">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=400&auto=format&fit=crop" alt="Visi">
                <div class="echo-caption">Deklarasi Visi Expedient</div>
            </div>
            <div class="echo-frame">
                <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=400&auto=format&fit=crop" alt="Keakraban">
                <div class="echo-caption">Malam Keakraban Solidaritas</div>
            </div>
        </div>
    </section>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Manuskrip Sejarah</h2>
        <div class="news-list">
            <article class="news-item reveal-up" onclick="openArchive('visi')" style="cursor:pointer;">
                <span class="news-date">30 MARET 2026</span>
                <h3 class="news-title">Penetapan Visi Angkatan</h3>
                <a href="javascript:void(0)" class="news-link">BACA DOKUMEN <i class="fa-solid fa-book-open"></i></a>
            </article>
            <article class="news-item reveal-up" onclick="openArchive('simpul')" style="cursor:pointer;">
                <span class="news-date">15 FEBRUARI 2026</span>
                <h3 class="news-title">Simpul Kesucian: Menjaga Nilai-Nilai Arrisalah</h3>
                <a href="javascript:void(0)" class="news-link">BACA DOKUMEN <i class="fa-solid fa-book-open"></i></a>
            </article>
        </div>
    </section>

    <!-- Modal Sovereign Archives -->
    <div class="archive-modal" id="archiveModal">
        <div class="archive-backdrop" onclick="closeArchive()"></div>
        <div class="archive-paper" id="archivePaper">
            <div class="archive-header">
                <div>SOVEREIGN ARCHIVE</div>
                <div id="arcDate">---</div>
            </div>
            <h1 class="archive-title" id="arcTitle">TITLE</h1>
            <div class="archive-body" id="arcBody">
                <!-- Content injected here -->
            </div>
            <button class="btn-stamp" style="margin-top:40px; display:block; width:100%; border-color: var(--glass-border); color: var(--text-primary);" onclick="closeArchive()">TUTUP MANUSKRIP</button>
        </div>
    </div>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Para Kurator</h2>
        <div class="curator-grid">
            <?php foreach ($kurator as $k): ?>
            <div class="curator-card glass-panel reveal-up">
                <div class="curator-img-wrap"><img src="<?= esc($k['foto']) ?>" alt="<?= esc($k['nama']) ?>"></div>
                <div class="curator-info">
                    <h4><?= esc($k['nama']) ?></h4>
                    <p><?= esc($k['jabatan']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SECTION GAMIFIKASI: LEADERBOARD PRESTISE -->
    <?php if (!empty($leaderboard)): ?>
    <section class="hall-section" style="padding-top: 0;">
        <h2 class="section-title reveal-up" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">Jajaran Kehormatan</h2>
        <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 15px;">
            <?php $rank = 1; foreach ($leaderboard as $l): ?>
            <div class="glass-panel reveal-up" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 30px; border-left: 4px solid <?= $rank == 1 ? '#FFD700' : ($rank == 2 ? '#E5E4E2' : ($rank == 3 ? '#cd7f32' : 'var(--glass-border)')) ?>; transition: 0.3s; cursor: default;" onmouseover="this.style.transform='translateX(10px)'" onmouseout="this.style.transform='translateX(0)'">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #d4af37; font-weight: 900; width: 40px;">#<?= $rank ?></div>
                    <?php $foto_profil = $l['foto_profil'] ?? 'default.webp'; ?>
                    <img src="<?= $foto_profil !== 'default.webp' ? '/uploads/profiles/'.$foto_profil : 'https://ui-avatars.com/api/?name='.urlencode($l['nama_panggilan'] ?: $l['nama_lengkap']).'&background=d4af37&color=000' ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(212,175,55,0.4);" alt="Avatar">
                    <div>
                        <div style="font-size: 1.2rem; font-family: 'Playfair Display', serif; color: var(--text-primary);"><?= esc($l['nama_panggilan'] ?: $l['nama_lengkap']) ?></div>
                        <div style="font-size: 0.8rem; font-family: 'Courier New', monospace; color: var(--text-secondary); letter-spacing: 2px;">POIN TERAKUMULASI</div>
                    </div>
                </div>
                <div style="font-size: 1.8rem; color: #d4af37; font-weight: 700; font-family: 'Inter', sans-serif;">
                    <?= number_format($l['prestise_points']) ?>
                </div>
            </div>
            <?php $rank++; endforeach; ?>
        </div>
    </section>
    <?php endif; ?>


    <section class="hall-section monolith-section">
        <div class="monolith-pillar">
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('keikhlasan')">Keikhlasan</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('kesederhanaan')">Kesederhanaan</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('kemandirian')">Kemandirian</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('ukhuwah')">Ukhuwwah Islamiyyah</h2>
            <h2 class="gold-engraving panca-jiwa" onclick="openJiwa('kebebasan')">Kebebasan</h2>
        </div>
    </section>

    <!-- Modal Khusus Panca Jiwa -->
    <div class="jiwa-modal" id="jiwaModal">
        <div class="jiwa-anim-container" id="jiwaAnimContainer">
            <!-- Animasi di-inject lewat JS -->
        </div>
        <div class="jiwa-content" id="jiwaContent">
            <h2 class="jiwa-title" id="jiwaTitle">Judul</h2>
            <div class="jiwa-desc" id="jiwaDesc">Penjelasan...</div>
            <button class="btn-stamp" style="margin-top: 30px; font-size: 0.8rem; padding: 10px 20px;" onclick="closeJiwa()">Tutup Penjelasan</button>
        </div>
    </div>

    <section class="hall-section">
        <h2 class="section-title reveal-up">Garis Waktu</h2>
        <div class="golden-timeline">
            <div class="timeline-node">
                <div class="node-dot"></div>
                <div class="node-content glass-panel">
                    <span class="node-year">AWAL MULA</span>
                    <p>Angkatan Expedient pertama kali menapakkan jejaknya di bumi Arrisalah, mengikat janji untuk menjadi barisan pelopor peradaban.</p>
                </div>
            </div>
            <div class="timeline-node">
                <div class="node-dot"></div>
                <div class="node-content glass-panel">
                    <span class="node-year">MASA PENEMPAAN</span>
                    <p>Melewati berbagai ujian dan dinamika pondok yang membentuk mental baja, kemandirian, serta ukhuwah islamiyah yang tak tergoyahkan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="hall-section ledger-section reveal-up">
        <h2 class="section-title" style="margin-bottom: 20px;">Buku Tamu Eksklusif</h2>
        <p style="color: var(--text-muted, #5e7a6b); margin-bottom: 40px; font-size: 0.9rem;">Segel kehadiran Anda di dalam sejarah peradaban.</p>
        
        <?php if (session()->get('logged_in')): ?>
        <form action="/beranda/simpan_pesan" method="POST" class="ledger-form" id="ledgerForm">
            <?= csrf_field() ?>
            <input type="text" name="nama" class="luxury-input input-signature" placeholder="Tanda Tangan (Nama)" required>
            <textarea name="pesan" class="luxury-input" placeholder="Tuliskan pesan berharga Anda..." rows="2" required></textarea>
            <div>
                <button type="submit" class="btn-stamp" id="desktopSubmitBtn">STEMPEL KEHADIRAN</button>
                
                <!-- Tuas Segel Emas (Mobile Swipe) -->
                <div class="swipe-seal-container" id="swipeSealContainer">
                    <div class="swipe-fill" id="swipeFill"></div>
                    <div class="swipe-text" id="swipeText">GESER UNTUK MENYEGEL <i class="fa-solid fa-arrow-right" style="margin-left:10px;"></i></div>
                    <div class="swipe-knob" id="swipeKnob"><i class="fa-solid fa-fingerprint"></i></div>
                </div>
            </div>
        </form>
        <?php else: ?>
        <div style="text-align:center; padding:40px 20px; background:var(--glass-bg); backdrop-filter:blur(20px); border:1px solid var(--glass-border); border-radius:16px;">
            <i class="fa-solid fa-lock" style="font-size:2rem; color:rgba(212,175,55,0.4); margin-bottom:15px;"></i>
            <p style="color:var(--text-secondary); margin-bottom:20px;">Masuk ke portal untuk menandatangani buku tamu.</p>
            <a href="/login" style="color:#d4af37; text-decoration:none; font-weight:700; letter-spacing:2px; text-transform:uppercase; font-size:0.85rem;"><i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang</a>
        </div>
        <?php endif; ?>

        <?php if(!empty($buku_tamu)): ?>
        <div style="margin-top: 50px;">
            <h3 style="font-family:'Playfair Display', serif; color:var(--gold-main, #d4af37); margin-bottom:20px; font-size:1.2rem; letter-spacing:2px; text-align:center;">Jejak Terkini</h3>
            <div style="display:flex; flex-direction:column; gap:15px; max-width:600px; margin:0 auto;">
                <?php foreach($buku_tamu as $bt): ?>
                    <div style="background:rgba(255,255,255,0.03); border-left:3px solid var(--gold-main, #d4af37); padding:15px 20px; border-radius:4px; position:relative;">
                        <div style="font-family:'Playfair Display', serif; color:#fff; font-weight:bold; font-size:1.1rem; margin-bottom:5px;"><?= esc($bt['nama']) ?></div>
                        <div style="color:var(--text-primary, #ccc); font-size:0.9rem; font-style:italic; line-height:1.5;">"<?= esc($bt['pesan']) ?>"</div>
                        <div style="position:absolute; top:15px; right:20px; font-size:0.7rem; color:var(--text-muted, #5e7a6b);"><?= date('d M Y, H:i', strtotime($bt['created_at'])) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align:center; margin-top:20px;">
                <a href="/buku-tamu" style="color:var(--text-secondary, #8899a6); text-decoration:none; font-size:0.8rem; letter-spacing:1px; text-transform:uppercase; transition:0.3s;" onmouseover="this.style.color='#d4af37'" onmouseout="this.style.color='var(--text-secondary)'">Lihat Seluruh Catatan <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
        <?php endif; ?>
    </section>


    <!-- ================= BIRTHDAY TOAST NOTIFICATION (HANYA MEMBER) ================= -->
    <?php if(session()->get('logged_in') && !empty($birthday_users)): ?>
    <div id="bdayToast" style="position:fixed; bottom:40px; left:50%; transform:translateX(-50%) translateY(150px); width:90%; max-width:400px; background:var(--glass-bg); backdrop-filter:blur(30px); border:1px solid rgba(212,175,55,0.4); border-radius:16px; padding:20px; box-shadow:0 20px 50px rgba(0,0,0,0.5); z-index:99999; display:flex; align-items:center; gap:15px; opacity:0; transition:0.8s cubic-bezier(0.16,1,0.3,1);">
        <div style="width:50px; height:50px; background:rgba(212,175,55,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#d4af37; font-size:1.5rem; flex-shrink:0;">
            <i class="fa-solid fa-cake-candles"></i>
        </div>
        <div style="flex:1;">
            <div style="font-family:'Playfair Display',serif; color:#d4af37; font-weight:700; font-size:1.1rem; margin-bottom:5px;">Notifikasi Ulang Tahun</div>
            <div style="color:var(--text-primary); font-size:0.85rem; line-height:1.4;">
                Hari ini adalah ulang tahun <strong><?= esc($birthday_users[0]['nama_panggilan'] ?: $birthday_users[0]['nama_lengkap']) ?></strong><?= count($birthday_users) > 1 ? ' dan ' . (count($birthday_users)-1) . ' entitas lainnya' : '' ?>. <br>
                <a href="/birthday" style="color:#d4af37; text-decoration:none; font-weight:bold; margin-top:5px; display:inline-block;">Kirim Ucapan <i class="fa-solid fa-arrow-right-long" style="margin-left:5px;"></i></a>
            </div>
        </div>
        <button onclick="closeBdayToast()" style="background:transparent; border:none; color:var(--text-secondary); cursor:pointer; padding:5px;"><i class="fa-solid fa-times"></i></button>
    </div>

    <?php endif; ?>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script src="/assets/js/beranda.js" defer></script>
<?= $this->endSection() ?>