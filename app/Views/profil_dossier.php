<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dossier - <?= esc($user['nama_panggilan']) ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700;900&family=Space+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --pure-gold: #ffd700; --dark-gold: #d4af37; --bg-noir: #050505; --glass-bg: rgba(20, 20, 20, 0.6); --glass-border: rgba(212, 175, 55, 0.2); }
        body { background-color: var(--bg-noir); color: #fff; font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; overflow-x: hidden; }
        /* ... (Gunakan style profil yang saya berikan sebelumnya) ... */
    </style>
</head>
<body>
    <div id="hacker-loader">
        <div class="loader-text" id="decrypt-text">INITIALIZING...</div>
        <div class="progress-bar"><div class="progress-fill" id="progress"></div></div>
    </div>

    <div class="mobile-container" id="main-content">
        <div class="header">
            <a href="<?= base_url('fitur') ?>" style="color:var(--dark-gold); text-decoration:none; font-size:12px; letter-spacing:2px;">EXIT</a>
            <div style="font-family:'Space Mono'; font-size:10px; color:#666;">SECURE VERIFIED</div>
        </div>
        <div class="dossier-card" id="tilt-card">
            <div class="avatar-container">
                <img src="<?= !empty($user['foto_profil']) ? base_url('uploads/profil/'.$user['foto_profil']) : 'https://via.placeholder.com/150/050505/d4af37?text=EXP' ?>" class="avatar">
            </div>

            <div class="title-badge">VVIP PLATINUM</div>
            
            <div class="name" data-value="<?= esc($user['nama_lengkap']) ?>"><?= esc($user['nama_lengkap']) ?></div>
            
            <div style="color:var(--dark-gold); font-family:'Space Mono'; margin-top:10px;">EXP-<?= sprintf('%03d', $user['id']) ?></div>
            
            <p style="margin-top:20px; font-style:italic; color:#888;">
                "<?= esc($user['motivasi_hidup'] ?? 'Merangkai baris kode, membangun fondasi masa depan.') ?>"
            </p>

            <div class="actions">
                <a href="https://instagram.com/<?= esc($user['akun_ig'] ?? '') ?>" target="_blank" class="btn-action btn-primary">
                    <i class="fa-brands fa-instagram"></i> CONNECT
                </a>
                <a href="<?= base_url('download_vcard/' . $user['id']) ?>" class="btn-action btn-secondary">
                    <i class="fa-solid fa-address-card"></i> SAVE
                </a>
            </div>
        </div>
    </div>
    </body>
</html>