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
    <button class="btn-radar btn-radar-gold" id="btnSyncLocation"><i class="fa-solid fa-location-crosshairs"></i> Perbarui Domisili</button>
    <button class="btn-radar btn-radar-glass" id="btnAutoTour"><i class="fa-solid fa-plane-departure"></i> Jelajahi Jaringan</button>
    <button class="btn-radar btn-radar-glass" id="btnGhostMode" title="Sembunyikan lokasi presisi"><i class="fa-solid fa-ghost"></i> Ghost Mode</button>
    <div class="sync-status" id="syncStatus"></div>
    <!-- 6. MAP DROPDOWN -->
    <div class="map-dropdown-wrap">
        <button class="btn-radar btn-radar-glass" id="btnMapMenu"><i class="fa-solid fa-layer-group"></i> Pilih Peta</button>
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
