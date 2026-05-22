<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Jaringan Silaturahmi<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>

/* ===== 2D MAP ===== */
#mapViz { position:fixed; inset:0; width:100vw; height:100vh; z-index:2; background:#000; }
.leaflet-control-zoom { border:none !important; }
.leaflet-control-zoom a { background:rgba(0,0,0,.6)!important; color:#d4af37!important; border-color:var(--glass-border)!important; backdrop-filter:blur(5px); }
.custom-leaflet-tooltip { background:transparent; border:none; box-shadow:none; padding:0; }

/* ===== GLOBE ===== */
#globeViz { position:fixed; inset:0; width:100vw; height:100vh; z-index:2; cursor:grab; }
#globeViz:active { cursor:grabbing; }
.main-wrapper { overflow:hidden !important; }

/* ===== CINEMATIC LOADING ===== */
#radarLoading { position:fixed; inset:0; z-index:9999; background:#030504; display:flex; flex-direction:column; justify-content:center; align-items:center; transition:opacity 1.5s ease; }
#radarLoading .rl-ring { width:80px; height:80px; border:2px solid rgba(212,175,55,0.15); border-top-color:#d4af37; border-radius:50%; animation:rlSpin 1s linear infinite; margin-bottom:30px; }
@keyframes rlSpin { to { transform:rotate(360deg); } }
#radarLoading .rl-txt { font-family:'Playfair Display',serif; font-size:1.4rem; color:#d4af37; letter-spacing:6px; text-transform:uppercase; animation:rlFade 2s ease-in-out infinite; }
@keyframes rlFade { 0%,100%{opacity:.4} 50%{opacity:1} }
#radarLoading .rl-sub { font-size:.72rem; color:var(--text-secondary); letter-spacing:3px; margin-top:10px; }
[data-theme="light"] #radarLoading { background:#f0f5f3; }

/* ===== STAR FIELD ===== */
#starField { position:fixed; inset:0; z-index:1; pointer-events:none; }
[data-theme="light"] #starField { display:none; }

/* ===== HUD ===== */
.radar-hud { position:fixed; top:30px; left:150px; z-index:50; pointer-events:none; }
.hud-title { font-family:'Playfair Display',serif; font-size:2rem; font-weight:900; color:var(--text-primary); text-shadow:0 6px 20px rgba(0,0,0,.9); }
.hud-subtitle { font-size:.72rem; color:#d4af37; letter-spacing:3px; text-transform:uppercase; margin-top:4px; font-weight:600; }
.stats-panel { margin-top:14px; display:flex; gap:12px; flex-wrap:wrap; }
.stat-box { background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); border-left:3px solid #d4af37; padding:8px 16px; border-radius:10px; box-shadow:var(--glass-shadow); }
.stat-num { font-family:'Playfair Display',serif; font-size:1.6rem; color:#d4af37; font-weight:700; line-height:1; }
.stat-label { font-size:.6rem; color:var(--text-secondary); text-transform:uppercase; letter-spacing:2px; margin-top:3px; }
.status-dot { display:inline-block; width:7px; height:7px; border-radius:50%; background:#d4af37; margin-left:6px; vertical-align:middle; box-shadow:0 0 10px rgba(212,175,55,.8); animation:sdPulse 2s infinite; }
@keyframes sdPulse { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ===== SEARCH BAR ===== */
.search-pill { position:fixed; top:30px; left:50%; transform:translateX(-50%); z-index:60; display:flex; align-items:center; gap:10px; background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); border-radius:50px; padding:8px 20px; box-shadow:var(--glass-shadow); width:320px; max-width:80vw; transition:border-color .3s; }
.search-pill:focus-within { border-color:rgba(212,175,55,.6); }
.search-pill i { color:#d4af37; font-size:.9rem; }
.search-pill input { background:none; border:none; outline:none; color:var(--text-primary); font-size:.82rem; font-weight:500; width:100%; font-family:'Inter',sans-serif; }
.search-pill input::placeholder { color:var(--text-secondary); }
.search-results { position:fixed; top:75px; left:50%; transform:translateX(-50%); z-index:60; background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); border-radius:14px; width:320px; max-width:80vw; max-height:250px; overflow-y:auto; display:none; box-shadow:var(--glass-shadow); }
.search-results.open { display:block; }
.sr-item { padding:12px 18px; cursor:pointer; border-bottom:1px solid rgba(255,255,255,.04); transition:background .2s; display:flex; align-items:center; gap:10px; }
.sr-item:hover { background:rgba(212,175,55,.08); }
.sr-item:last-child { border-bottom:none; }
.sr-name { font-size:.82rem; font-weight:600; color:var(--text-primary); }
.sr-city { font-size:.68rem; color:var(--text-secondary); }
.sr-avatar { width:30px; height:30px; border-radius:50%; object-fit:cover; border:1px solid rgba(212,175,55,.3); }

/* ===== CONTROLS (KANAN BAWAH) ===== */
.radar-controls { position:fixed; bottom:35px; right:35px; z-index:50; display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
.btn-radar { display:flex; align-items:center; gap:8px; backdrop-filter:var(--glass-blur)!important; border-radius:50px; font-size:.75rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; cursor:pointer; transition:all .4s; box-shadow:var(--glass-shadow); border:none; }
.btn-radar-gold { background:rgba(212,175,55,.1); border:1px solid rgba(212,175,55,.5); color:#d4af37; padding:10px 20px; }
.btn-radar-gold:hover:not(:disabled) { background:#d4af37; color:#000; transform:translateY(-2px); }
.btn-radar-gold:disabled { opacity:.5; cursor:wait; }
.btn-radar-glass { background:var(--glass-bg); border:1px solid var(--glass-border); color:var(--text-primary); padding:9px 18px; }
.btn-radar-glass:hover { background:rgba(255,255,255,.1); transform:translateY(-2px); }
.sync-status { font-size:.68rem; color:#d4af37; letter-spacing:1px; opacity:0; transition:opacity .4s; text-align:right; max-width:240px; }

/* ===== MAP DROPDOWN ===== */
.map-dropdown-wrap { position:relative; }
.map-dropdown { display:none; position:absolute; bottom:100%; right:0; margin-bottom:8px; background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); border-radius:14px; padding:8px; min-width:200px; box-shadow:var(--glass-shadow); }
.map-dropdown.open { display:block; }
.map-dropdown a { display:flex; align-items:center; gap:10px; padding:10px 14px; color:var(--text-secondary); font-size:.72rem; font-weight:600; letter-spacing:1px; text-decoration:none; border-radius:10px; transition:all .2s; text-transform:uppercase; }
.map-dropdown a:hover { background:rgba(212,175,55,.1); color:#d4af37; }
.map-dropdown a i { width:18px; text-align:center; font-size:.85rem; }

/* ===== INFO DRAWER ===== */
.info-drawer { position:fixed; top:0; right:-400px; width:380px; max-width:90vw; height:100vh; background:var(--glass-bg); backdrop-filter:blur(50px) saturate(180%); border-left:1px solid var(--glass-border); z-index:200; transition:right .5s cubic-bezier(.16,1,.3,1); overflow-y:auto; padding:0; }
.info-drawer.open { right:0; }
.id-close { position:absolute; top:20px; right:20px; background:none; border:none; color:var(--text-secondary); font-size:1.2rem; cursor:pointer; z-index:5; transition:color .2s; }
.id-close:hover { color:#d4af37; }
.id-header { padding:40px 30px 20px; text-align:center; border-bottom:1px solid rgba(255,255,255,.05); }
.id-avatar { width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid #d4af37; box-shadow:0 0 25px rgba(212,175,55,.3); margin-bottom:15px; }
.id-name { font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--text-primary); font-weight:700; }
.id-nick { font-size:.78rem; color:#d4af37; letter-spacing:2px; margin-top:3px; }
.id-body { padding:20px 30px; }
.id-row { display:flex; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid rgba(255,255,255,.04); }
.id-row i { color:#d4af37; width:20px; text-align:center; font-size:.9rem; }
.id-row-label { font-size:.68rem; color:var(--text-secondary); text-transform:uppercase; letter-spacing:1px; }
.id-row-val { font-size:.88rem; color:var(--text-primary); font-weight:600; margin-top:2px; }
.id-actions { padding:20px 30px; display:flex; flex-direction:column; gap:10px; }
.id-btn { display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; border-radius:50px; font-size:.78rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; text-decoration:none; transition:all .3s; cursor:pointer; border:none; }
.id-btn-wa { background:#25d366; color:#fff; }
.id-btn-wa:hover { background:#1da851; transform:translateY(-2px); }
.id-btn-profile { background:rgba(212,175,55,.12); border:1px solid rgba(212,175,55,.4); color:#d4af37; }
.id-btn-profile:hover { background:#d4af37; color:#000; }
.id-overlay { position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:199; display:none; }
.id-overlay.open { display:block; }

/* ===== FILTER PANEL (KIRI BAWAH) ===== */
.filter-panel { position:fixed; bottom:35px; left:150px; z-index:50; display:flex; gap:8px; flex-wrap:wrap; }
.filter-chip { background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); border-radius:50px; padding:7px 14px; font-size:.68rem; font-weight:700; color:var(--text-secondary); cursor:pointer; transition:all .3s; letter-spacing:1px; text-transform:uppercase; display:flex; align-items:center; gap:6px; }
.filter-chip:hover,.filter-chip.active { border-color:rgba(212,175,55,.5); color:#d4af37; background:rgba(212,175,55,.08); }
.filter-chip i { font-size:.75rem; }

/* ===== LEADERBOARD ===== */
.leaderboard { position:fixed; bottom:35px; left:150px; z-index:49; background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); border-radius:14px; padding:16px 20px; width:240px; box-shadow:var(--glass-shadow); display:none; }
.leaderboard.open { display:block; }
.lb-title { font-size:.65rem; color:#d4af37; letter-spacing:2px; text-transform:uppercase; font-weight:700; margin-bottom:10px; }
.lb-row { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
.lb-rank { font-family:'Playfair Display',serif; font-size:.85rem; color:#d4af37; font-weight:700; width:18px; }
.lb-city { font-size:.75rem; color:var(--text-primary); font-weight:600; flex:1; }
.lb-bar-wrap { width:60px; height:6px; background:rgba(255,255,255,.06); border-radius:4px; overflow:hidden; }
.lb-bar { height:100%; background:linear-gradient(90deg,#d4af37,#f0d060); border-radius:4px; transition:width 1s ease; }
.lb-count { font-size:.65rem; color:var(--text-secondary); width:20px; text-align:right; }

/* ===== TOUR OVERLAY ===== */
.tour-overlay { position:fixed; bottom:120px; left:50%; transform:translateX(-50%); z-index:55; text-align:center; pointer-events:none; opacity:0; transition:opacity .8s; }
.tour-overlay.show { opacity:1; }
.tour-name { font-family:'Playfair Display',serif; font-size:1.8rem; color:#d4af37; font-weight:700; text-shadow:0 4px 15px rgba(0,0,0,.8); }
.tour-city { font-size:.8rem; color:var(--text-secondary); letter-spacing:2px; margin-top:4px; }

/* ===== TOOLTIP ===== */
.globe-tooltip { background:rgba(5,10,8,.92)!important; backdrop-filter:blur(20px)!important; border:1px solid rgba(212,175,55,.3)!important; border-radius:14px!important; padding:14px 18px!important; pointer-events:none; }
.tt-name { font-family:'Playfair Display',serif; font-size:1.1rem; color:#d4af37; margin-bottom:4px; font-weight:700; }
.tt-loc { font-size:.75rem; color:#ccc; }

/* ===== DESKTOP SIDEBAR ADJUSTMENTS ===== */
.radar-hud, .filter-panel, .leaderboard { transition: left 0.8s var(--awwwards-ease), bottom 0.8s var(--awwwards-ease), top 0.8s var(--awwwards-ease); }
body.sidebar-closed .radar-hud,
body.sidebar-closed .filter-panel,
body.sidebar-closed .leaderboard { left: 40px; }

/* ===== MOBILE APP-LIKE EXPERIENCE ===== */
@media(max-width:768px) {
    /* Search Bar ke atas penuh */
    .search-pill { top:20px; width:calc(100vw - 40px); }
    .search-results { top:70px; width:calc(100vw - 40px); }

    /* HUD jadi pill horizontal kecil di bawah search */
    .radar-hud { top:75px; left:20px; right:20px; display:flex; justify-content:space-between; align-items:center; background:var(--glass-bg); backdrop-filter:var(--glass-blur); border:1px solid var(--glass-border); padding:10px 15px; border-radius:15px; }
    .hud-title { font-size:1.1rem; margin:0; }
    .hud-subtitle { display:none; }
    .stats-panel { margin-top:0; gap:10px; display:flex; align-items:center; }
    .stat-box { border:none; padding:0; background:transparent; box-shadow:none; text-align:right; display:flex; flex-direction:column; justify-content:center; }
    .stat-box:nth-child(2), .stat-box:nth-child(3) { display:none; }
    .stat-num { font-size:1.1rem; line-height:1; }
    .stat-label { font-size:.55rem; margin-top:2px; }

    /* Drawer Info dari bawah (Bottom Sheet) */
    .info-drawer { top:auto; bottom:-100vh; right:0; width:100vw; max-width:100vw; height:auto; max-height:85vh; border-left:none; border-top:1px solid var(--glass-border); border-radius:25px 25px 0 0; transition:bottom .5s cubic-bezier(.16,1,.3,1); }
    .info-drawer.open { right:0; bottom:0; z-index: 10000; }
    .id-close { top:15px; background:rgba(255,255,255,0.1); border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; }
    .id-header { padding:30px 20px 15px; }
    .id-avatar { width:70px; height:70px; }
    .id-name { font-size:1.3rem; }
    
    /* Control Bar bawah seperti Native App */
    .radar-controls { right:12px; left:12px; flex-direction:row; justify-content:space-between; gap:6px; background:var(--glass-bg); backdrop-filter:var(--glass-blur); padding:8px; border-radius:15px; border:1px solid var(--glass-border); transition: bottom 0.8s var(--awwwards-ease); }
    .btn-radar { font-size:.65rem; padding:8px 4px; border:none; box-shadow:none; flex:1; justify-content:center; white-space:nowrap; }
    .map-dropdown-wrap { flex:1; display:flex; }
    .map-dropdown-wrap .btn-radar { width:100%; flex:1; }
    .btn-radar-gold { background:rgba(212,175,55,.15); }
    .btn-radar-glass { background:transparent; }
    .sync-status { position:absolute; top:-25px; right:10px; text-align:right; }
    .hide-mobile { display:none; }
    
    /* Map Dropdown menu naik ke atas */
    .map-dropdown { bottom:120%; margin-bottom:15px; left:auto; right:0; min-width:160px; text-align:center; }
    
    /* Filter Panel dipindah ke atas map controls */
    .filter-panel { left:50%; transform:translateX(-50%); width:max-content; background:var(--glass-bg); backdrop-filter:var(--glass-blur); border-radius:50px; padding:5px; border:1px solid var(--glass-border); justify-content:center; transition: bottom 0.8s var(--awwwards-ease); }
    .filter-chip { padding:6px 12px; font-size:.6rem; border:none; background:transparent; }
    .filter-chip.active { background:rgba(212,175,55,.2); border-radius:50px; }

    /* Leaderboard di tengah layar sebagai Modal */
    .leaderboard { left:50%; transform:translate(-50%, -50%); bottom:auto; z-index:200; width:85vw; max-width:320px; transition: top 0.8s var(--awwwards-ease); }
    
    .tour-overlay { width:90%; transition: bottom 0.8s var(--awwwards-ease); }
    .tour-name { font-size:1.4rem; }

    /* --- PENGHINDARAN TABRAKAN DENGAN SIDEBAR MOBILE --- */
    
    /* 1. Jika Sidebar DIBUKA (Default Mobile) */
    body:not(.sidebar-closed) .radar-controls { bottom: 110px; } /* Di atas sidebar */
    body:not(.sidebar-closed) .filter-panel { bottom: 165px; }
    body:not(.sidebar-closed) .leaderboard { top: 40%; } /* Naik sedikit */
    body:not(.sidebar-closed) .tour-overlay { bottom: 210px; }
    body:not(.sidebar-closed) .aegis-toast { bottom: 110px !important; }

    /* 2. Jika Sidebar DITUTUP (Oleh User) */
    body.sidebar-closed .radar-controls { bottom: 45px; } /* Hindari tombol menu-toggle bulat di bawah */
    body.sidebar-closed .filter-panel { bottom: 100px; }
    body.sidebar-closed .leaderboard { top: 50%; } /* Ke tengah layar */
    body.sidebar-closed .tour-overlay { bottom: 145px; }
    body.sidebar-closed .aegis-toast { bottom: 45px !important; }
    
    /* Kembalikan offset left ke normal saat sidebar tutup di mobile, karena pakai margin auto/center */
    body.sidebar-closed .filter-panel, body.sidebar-closed .leaderboard { left: 50%; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- 1. CINEMATIC LOADING -->
<div id="radarLoading">
    <div class="rl-ring"></div>
    <div class="rl-txt">Jaringan Silaturahmi</div>
    <div class="rl-sub">Menghubungkan titik-titik persaudaraan...</div>
</div>

<!-- STAR FIELD -->
<canvas id="starField"></canvas>

<!-- GLOBE -->
<div id="mapViz"></div>

<!-- 3. SEARCH -->
<div class="search-pill">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="searchInput" placeholder="Cari nama alumni..." autocomplete="off">
</div>
<div class="search-results" id="searchResults"></div>

<!-- HUD -->
<div class="radar-hud">
    <div class="hud-title">Jaringan Silaturahmi <span class="status-dot"></span></div>
    <div class="hud-subtitle">Persebaran Alumni Global</div>
    <div class="stats-panel">
        <div class="stat-box"><div class="stat-num" id="sTotal">0</div><div class="stat-label">Total</div></div>
        <div class="stat-box"><div class="stat-num" id="sArea">0</div><div class="stat-label">Area</div></div>
        <div class="stat-box"><div class="stat-num" id="sFar">0</div><div class="stat-label">KM Terjauh</div></div>
    </div>
</div>

<!-- TOUR NAME OVERLAY -->
<div class="tour-overlay" id="tourOverlay">
    <div class="tour-name" id="tourName"></div>
    <div class="tour-city" id="tourCity"></div>
</div>

<!-- 8. FILTER -->
<div class="filter-panel" id="filterPanel">
    <div class="filter-chip active" data-filter="all"><i class="fa-solid fa-globe"></i> Semua</div>
    <div class="filter-chip" data-filter="L"><i class="fa-solid fa-mars"></i> Ikhwan</div>
    <div class="filter-chip" data-filter="P"><i class="fa-solid fa-venus"></i> Akhwat</div>
    <div class="filter-chip" data-filter="lb"><i class="fa-solid fa-trophy"></i> Leaderboard</div>
</div>

<!-- 12. LEADERBOARD -->
<div class="leaderboard" id="leaderboard">
    <div class="lb-title"><i class="fa-solid fa-trophy" style="margin-right:6px;"></i>Top 5 Kota</div>
    <div id="lbContent"></div>
</div>

<!-- CONTROLS -->
<div class="radar-controls">
    <button class="btn-radar btn-radar-gold" id="btnSyncLocation"><i class="fa-solid fa-location-crosshairs"></i> <span class="hide-mobile">Perbarui </span>Domisili</button>
    <button class="btn-radar btn-radar-glass" id="btnAutoTour"><i class="fa-solid fa-plane-departure"></i> <span class="hide-mobile">Jelajahi </span>Jaringan</button>
    <div class="sync-status" id="syncStatus"></div>
    <!-- 6. MAP DROPDOWN -->
    <div class="map-dropdown-wrap">
        <button class="btn-radar btn-radar-glass" id="btnMapMenu"><i class="fa-solid fa-layer-group"></i> <span class="hide-mobile">Pilih </span>Peta</button>
        <div class="map-dropdown" id="mapDropdown">
            <a href="/radar"><i class="fa-solid fa-earth-asia"></i> Globe 3D</a>
            <a href="/radar/flat"><i class="fa-solid fa-map"></i> Peta Datar</a>
            <a href="/radar/satellite"><i class="fa-solid fa-satellite"></i> Peta Satelit</a>
            <a href="/radar/terrain"><i class="fa-solid fa-mountain-sun"></i> Peta Terrain</a>
            <a href="/radar/dark"><i class="fa-solid fa-moon"></i> Peta Gelap</a>
            <a href="/radar/watercolor"><i class="fa-solid fa-map-location-dot"></i> Peta Google</a>
            <a href="/radar/classic"><i class="fa-solid fa-signs-post"></i> Peta Klasik</a>
        </div>
    </div>
</div>

<!-- 2. INFO DRAWER -->
<div class="id-overlay" id="idOverlay"></div>
<div class="info-drawer" id="infoDrawer">
    <button class="id-close" id="idClose"><i class="fa-solid fa-xmark"></i></button>
    <div class="id-header">
        <img id="idAvatar" class="id-avatar" src="/images/default-avatar.jpg" alt="">
        <div class="id-name" id="idName"></div>
        <div class="id-nick" id="idNick"></div>
    </div>
    <div class="id-body">
        <div class="id-row"><i class="fa-solid fa-location-dot"></i><div><div class="id-row-label">Domisili</div><div class="id-row-val" id="idCity"></div></div></div>
        <div class="id-row"><i class="fa-solid fa-venus-mars"></i><div><div class="id-row-label">Gender</div><div class="id-row-val" id="idGender"></div></div></div>
        <div class="id-row"><i class="fa-solid fa-ruler"></i><div><div class="id-row-label">Jarak dari Pondok</div><div class="id-row-val" id="idDist"></div></div></div>
    </div>
    <div class="id-actions">
        <a class="id-btn id-btn-wa" id="idWa" href="#" target="_blank"><i class="fa-brands fa-whatsapp"></i> Hubungi via WhatsApp</a>
        <a class="id-btn id-btn-profile" id="idProfile" href="#"><i class="fa-solid fa-user"></i> Lihat Profil</a>
    </div>
</div>

<script>
    window.__radarData = <?= json_encode($alumni_nodes) ?>;
    window.__mapStyle = 'terrain';
    window.__pusherKey = '<?= env('PUSHER_APP_KEY') ?>';
    window.__pusherCluster = '<?= env('PUSHER_APP_CLUSTER') ?>';
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/vendor/pusher/pusher.min.js"></script>
<script src="/assets/js/radar-2d.js"></script>
<?= $this->endSection() ?>
