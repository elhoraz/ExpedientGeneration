<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sovereign Gateway</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; background: #050505; color: #d4af37; font-family: 'Space Mono', monospace; display: flex; justify-content: center; align-items: center; height: 100vh; overflow: hidden; }
        .modal { background: rgba(10,10,10,0.9); border: 1px solid #d4af37; padding: 40px 30px; border-radius: 16px; text-align: center; box-shadow: 0 0 40px rgba(212,175,55,0.15); width: 90%; max-width: 400px; }
        h2 { margin-top: 0; font-size: 18px; letter-spacing: 2px; text-transform: uppercase; border-bottom: 1px solid rgba(212,175,55,0.3); padding-bottom: 15px; margin-bottom: 30px; }
        .btn { display: block; width: 100%; padding: 18px; margin: 15px 0; background: transparent; color: #d4af37; border: 1px solid #d4af37; border-radius: 8px; font-weight: bold; font-size: 13px; cursor: pointer; transition: 0.3s; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; }
        .btn:hover, .btn:active { background: #d4af37; color: #000; box-shadow: 0 0 20px rgba(212,175,55,0.5); }
        .id-target { font-size: 10px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="modal">
        <h2>Pilih Protokol Akses</h2>
        
        <a href="<?= base_url('ar_hologram/' . $user['id']) ?>" class="btn">
            [1] Inisialisasi AR Hologram
        </a>
        
        <a href="<?= base_url('download_vcard/' . $user['id']) ?>" class="btn" style="border-color:#555; color:#aaa;">
            [2] Ekstrak Data Kontak
        </a>

        <div class="id-target">TARGET: EXP-<?= sprintf('%03d', $user['id']) ?></div>
    </div>
</body>
</html>