<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Expedient Generation</title>
    
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="theme-color" content="#030504">
    <meta name="description" content="Museum Galeri Digital VVIP & Arsip Direktori Expedient Generation.">
    <meta property="og:title" content="<?= $this->renderSection('title') ?> - Expedient Generation">
    <meta property="og:description" content="Akses portal eksklusif peninggalan dan jejak langkah Expedient.">
    <meta property="og:image" content="<?= base_url('images/logo-utuh.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">

    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/logo-utuh.png">
    
    <!-- PWA iOS Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Expedient">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/design-system.css">

    <script>
        const savedTheme = localStorage.getItem('expedient_theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <link rel="stylesheet" href="/css/template.css">
    <?= $this->renderSection('styles') ?>

</head>
<body>

    <div class="film-grain"></div>
    <div class="cursor-dot" id="cursorDot"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <?php 
        $loaders = ['v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7', 'v8', 'v9', 'v10'];
        $selectedLoader = $loaders[array_rand($loaders)];
    ?>

    <div id="loadingScreen" class="load-<?= $selectedLoader ?>">
        <div class="loader-logo-wrap">
            <?php if($selectedLoader == 'v1'): ?><div class="pure-ripple"></div><div class="pure-ripple"></div>
            <?php elseif($selectedLoader == 'v2'): ?><div class="cosmos-orbit orbit-1"></div><div class="cosmos-orbit orbit-2"></div>
            <?php elseif($selectedLoader == 'v5'): ?><div class="lunar-eclipse"></div>
            <?php elseif($selectedLoader == 'v6'): ?><div class="solar-crown"></div>
            <?php elseif($selectedLoader == 'v7'): ?><div class="sacred-lattice"></div>
            <?php elseif($selectedLoader == 'v8'): ?><div class="prismatic-pulse"></div>
            <?php elseif($selectedLoader == 'v9'): ?><div class="flowing-veils"><div class="veil"></div><div class="veil"></div><div class="veil"></div><div class="veil"></div><div class="veil"></div></div>
            <?php elseif($selectedLoader == 'v10'): ?><div class="stellar-orbit"><div class="stellar-dot"></div></div><div class="stellar-orbit reverse"><div class="stellar-dot"></div></div>
            <?php endif; ?>
            <?= cms_image('global_loader_logo', '/images/logo-utuh.png', 'loader-logo', 'alt="Loading Expedient"') ?>
        </div>
        <?php if($selectedLoader == 'v3'): ?><div class="gilded-reveal"><?= cms_text('global_loader_text', 'EXPEDIENT GENERATION') ?></div><?php endif; ?>
    </div>

    <div class="aurora-container">
        <div class="aurora-blob blob-1"></div><div class="aurora-blob blob-2"></div><div class="aurora-blob blob-3"></div>
    </div>
    <canvas id="particles-js"></canvas>

    <div id="aegisToast" class="aegis-toast">
        <i class="fa-solid fa-compass-drafting aegis-icon"></i>
        <div class="aegis-content">
            <span class="aegis-title">Entitas Terdeteksi Masuk</span>
            <strong id="radarName" class="aegis-name">Seseorang</strong>
        </div>
    </div>

    <?php $isLoggedIn = session()->get('logged_in'); ?>

    <button class="menu-toggle hover-trigger" id="btnMenuOpen" title="Panggil Panel">
        <span class="line"></span>
        <span class="line short"></span>
    </button>

    <nav class="sidebar" id="sidebarNav">
        <a href="javascript:void(0)" class="nav-item hover-trigger" id="btnMenuClose" data-tooltip="Sembunyikan Panel" onclick="hapticNav()">
            <i class="fa-solid fa-compress"></i>
        </a>
        <a href="/beranda" class="nav-item hover-trigger <?= (uri_string() == 'beranda') ? 'active' : '' ?>" data-tooltip="Grand Exhibition" onclick="hapticNav()"><i class="fa-solid fa-landmark"></i></a>
        <a href="/direktori" class="nav-item hover-trigger <?= (uri_string() == 'direktori') ? 'active' : '' ?>" data-tooltip="The Registry" onclick="hapticNav()"><i class="fa-solid fa-address-book"></i></a>
        <a href="/galeri" class="nav-item hover-trigger <?= (uri_string() == 'galeri') ? 'active' : '' ?>" data-tooltip="The Vault" onclick="hapticNav()"><i class="fa-solid fa-film"></i></a>
        <?php if ($isLoggedIn): ?>
        <a href="/radar" class="nav-item hover-trigger <?= (uri_string() == 'radar' || uri_string() == 'radar/flat') ? 'active' : '' ?>" data-tooltip="Peta Persebaran" onclick="hapticNav()"><i class="fa-solid fa-earth-asia"></i></a>
        <a href="/syndicate" class="nav-item hover-trigger <?= (uri_string() == 'syndicate') ? 'active' : '' ?>" data-tooltip="The Council" onclick="hapticNav()"><i class="fa-solid fa-chess-knight"></i></a>
        <?php $fiturPages = ['fitur','oracle','enigma','genesis','celestial','majlis','tarbiyah','baitul-maal','wasiat','multazam','kontemplasi','divine', 'nexus']; ?>
        <a href="/fitur" class="nav-item hover-trigger <?= in_array(uri_string(), $fiturPages) ? 'active' : '' ?>" data-tooltip="Fitur Eksekutif" onclick="hapticNav()"><i class="fa-solid fa-gem"></i></a>
        <div style="flex-grow: 1;" class="nav-spacer"></div>
        <a href="/profil" class="nav-item hover-trigger <?= (uri_string() == 'profil') ? 'active' : '' ?>" data-tooltip="Profil Saya" onclick="hapticNav()"><i class="fa-solid fa-user-astronaut"></i></a>
        <?php else: ?>
        <div style="flex-grow: 1;" class="nav-spacer"></div>
        <a href="/login" class="nav-item hover-trigger" data-tooltip="Masuk ke Portal" onclick="hapticNav()"><i class="fa-solid fa-right-to-bracket"></i></a>
        <?php endif; ?>
    </nav>

    <?php if ($isLoggedIn): ?>

    <!-- ================= NOTIFICATION DROPDOWN ================= -->
    <div id="notifDropdown" class="notif-dropdown">
        <div style="padding:15px 20px; border-bottom:1px solid rgba(212,175,55,0.2); font-family:'Playfair Display',serif; color:#d4af37; font-size:1.1rem; font-weight:700;">
            Pemberitahuan
        </div>
        <div id="notifList" style="max-height:300px; overflow-y:auto; padding:10px;">
            <div style="text-align:center; padding:20px; color:var(--text-secondary); font-size:0.85rem;">Tidak ada pesan baru.</div>
        </div>
    </div>
    <?php endif; ?>

    <button class="theme-widget hover-trigger" id="btnTheme" title="Ganti Mode">
        <div class="icon-orb"><i class="fa-solid fa-moon" id="toggleIcon"></i></div>
        <span class="widget-text" id="themeText">Malam</span>
    </button>

    <?php if ($isLoggedIn): ?>
    <button class="notif-widget hover-trigger" id="btnNotifWidget" title="Pesan Masuk" onclick="hapticNav(); toggleNotif()">
        <div class="icon-orb"><i class="fa-solid fa-bell"></i></div>
        <span id="notifBadge" style="display:none; position:absolute; top:2px; right:2px; width:12px; height:12px; background:#d4af37; border-radius:50%; box-shadow:0 0 10px #d4af37;"></span>
    </button>

    <a href="/chat" class="chat-widget hover-trigger" id="btnChatWidget" title="Executive Chat" onclick="hapticNav()">
        <div class="icon-orb"><i class="fa-solid fa-comment-dots"></i></div>
        <span id="chatBadge" style="display:none; position:absolute; top:2px; right:2px; width:12px; height:12px; background:#d4af37; border-radius:50%; box-shadow:0 0 10px #d4af37;"></span>
    </a>
    <?php else: ?>
    <!-- Guest: tombol login & register -->
    <a href="/login" class="guest-auth-btn hover-trigger" id="guestLoginBtn" title="Masuk ke Portal" style="
        position: fixed; top: 30px; right: 160px; z-index: 100;
        background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(212,175,55,0.05));
        backdrop-filter: blur(20px); border: 1px solid rgba(212,175,55,0.5);
        border-radius: 40px; padding: 8px 22px;
        color: #d4af37; font-size: 0.75rem; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        text-decoration: none; transition: 0.4s;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    ">
        <i class="fa-solid fa-right-to-bracket"></i> Masuk
    </a>
    <?php endif; ?>

    <main class="main-wrapper">
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        window.ExpedientConfig = {
            userId: <?= session()->get('user_id') ?? 'null' ?>,
            pusher: {
                key: '<?= env('PUSHER_APP_KEY') ?>',
                cluster: '<?= env('PUSHER_APP_CLUSTER', 'ap1') ?>'
            }
        };

        // CI4 FLASHDATA CATCHER
        <?php 
            $successMsg = session()->getFlashdata('success') ?? session()->getFlashdata('pesan');
            $errorMsg = session()->getFlashdata('error');
            $flashMsg = $successMsg ?? $errorMsg;
            $isError = !empty($errorMsg);
            
            if ($flashMsg) : 
        ?>
            document.addEventListener("DOMContentLoaded", () => {
                const aegisToastSys = document.getElementById('aegisToast');
                const radarNameSys = document.getElementById('radarName');
                if(aegisToastSys && radarNameSys) {
                    document.querySelector('.aegis-title').innerText = "<?= $isError ? 'Peringatan Sistem' : 'Informasi Sistem' ?>";
                    radarNameSys.innerText = "<?= esc($flashMsg, 'js') ?>";
                    window.aegisLink = '#';
                    
                    <?php if($isError): ?>
                        aegisToastSys.style.borderLeft = "4px solid #8b0000";
                        document.querySelector('.aegis-title').style.color = "#8b0000";
                        document.querySelector('.aegis-icon').style.color = "#8b0000";
                    <?php else: ?>
                        aegisToastSys.style.borderLeft = "4px solid #d4af37";
                        document.querySelector('.aegis-title').style.color = "var(--text-secondary)";
                        document.querySelector('.aegis-icon').style.color = "#d4af37";
                    <?php endif; ?>

                    aegisToastSys.classList.add('show');
                    if (navigator.vibrate) navigator.vibrate([50, 50, 50]);
                    setTimeout(() => aegisToastSys.classList.remove('show'), 6000);
                }
            });
        <?php endif; ?>
    </script>
    <script src="/js/template.js"></script>
    <script src="/js/particles.js"></script>
    <?php if ($isLoggedIn): ?>
    <script src="/vendor/pusher/pusher.min.js"></script>
    <script src="/js/pusher-client.js"></script>
    <?php endif; ?>
    <?= $this->renderSection('scripts') ?>

</body>
</html>