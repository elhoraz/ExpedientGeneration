<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title) ?> - Expedient Generation</title>
    
    <meta name="theme-color" content="#030504">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-base: #010302;
            --bg-radial: #030d08;
            --glass-surface: rgba(4, 10, 7, 0.6);
            --glass-border: rgba(0, 255, 136, 0.15);
            --glass-shadow: 0 40px 80px rgba(0,0,0,0.9);
            --text-main: #f0f5f2;
            --text-muted: #5e7a6b;
            --gold-liquid: linear-gradient(135deg, #d4af37 0%, #fff2cd 40%, #aa771c 60%, #d4af37 100%);
            --emerald-liquid: linear-gradient(135deg, #00ff88, #008844);
            --input-bg: rgba(0, 0, 0, 0.5);
            --input-focus: rgba(0, 255, 136, 0.1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--bg-base);
            background-image: radial-gradient(circle at 50% 0%, var(--bg-radial), transparent 80%);
            color: var(--text-main);
            width: 100vw; height: 100vh;
            display: flex; justify-content: center; align-items: center;
            overflow: hidden;
        }

        .auth-prism {
            width: 100%; max-width: 400px; position: relative;
            background: var(--glass-surface);
            backdrop-filter: blur(40px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .icon-lock { font-size: 3rem; color: #d4af37; margin-bottom: 20px; text-shadow: 0 0 20px rgba(212,175,55,0.5); }

        .title-holo {
            font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700;
            background: var(--gold-liquid); background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .subtitle-spec { font-size: 0.8rem; color: var(--text-muted); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 30px; }

        .input-group { position: relative; margin-bottom: 25px; text-align: left; }
        
        .input-control {
            width: 100%; padding: 12px; background: var(--input-bg);
            border: none; border-bottom: 1px solid rgba(255,255,255,0.05);
            color: var(--text-main); font-size: 1rem; outline: none; transition: 0.4s;
            border-radius: 6px 6px 0 0;
        }
        
        .input-neon-line {
            position: absolute; bottom: 0; left: 50%; width: 0; height: 2px;
            background: var(--emerald-liquid); transition: 0.5s; transform: translateX(-50%);
        }

        .input-control:focus { background: var(--input-focus); }
        .input-control:focus ~ .input-neon-line { width: 100%; box-shadow: 0 -2px 10px rgba(0, 255, 136, 0.4); }

        .btn-prime {
            width: 100%; padding: 14px; border-radius: 12px; border: none;
            background: var(--gold-liquid); background-size: 200% auto;
            color: #05090a; font-weight: 800; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 2px;
            cursor: pointer; transition: 0.3s;
        }
        .btn-prime:hover { box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); transform: translateY(-2px); }

        .alert { background: rgba(255, 51, 102, 0.1); border: 1px solid rgba(255, 51, 102, 0.3); color: #ff3366; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; }
        
        .back-link { display: block; margin-top: 20px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: 0.3s; }
        .back-link:hover { color: #d4af37; }
    </style>
</head>
<body>

    <div class="auth-prism">
        <i class="fa-solid fa-user-shield icon-lock"></i>
        <h1 class="title-holo">Akses Terbatas</h1>
        <div class="subtitle-spec">Otorisasi Administrator Diperlukan</div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert"><i class="fa-solid fa-triangle-exclamation"></i> <?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('admin/unlock') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="input-group">
                <input type="password" name="password" class="input-control" required placeholder="Kata Sandi Master">
                <div class="input-neon-line"></div>
            </div>
            
            <button type="submit" class="btn-prime"><i class="fa-solid fa-unlock-keyhole"></i> Verifikasi</button>
        </form>

        <a href="<?= base_url('beranda') ?>" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
    </div>

</body>
</html>
