<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Area Terbatas | Expedient Generation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600&display=swap');
        
        body {
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #050505 0%, #1a1a1a 100%);
            font-family: 'Inter', sans-serif;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .container {
            text-align: center;
            padding: 3rem;
            border: 1px solid rgba(212, 175, 55, 0.2);
            background: rgba(13, 13, 13, 0.8);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), inset 0 0 20px rgba(212, 175, 55, 0.05);
            backdrop-filter: blur(10px);
            max-width: 600px;
            width: 90%;
            position: relative;
        }
        .container::before {
            content: '';
            position: absolute;
            top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(45deg, #d4af37, transparent, #ffd700);
            z-index: -1;
            border-radius: 16px;
            opacity: 0.3;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            margin: 0;
            color: #d4af37;
            text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
            letter-spacing: 2px;
        }
        p.subtitle {
            font-size: 1.2rem;
            color: #aaa;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            background: transparent;
            color: #d4af37;
            border: 1px solid #d4af37;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: #d4af37;
            color: #050505;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }
        .debug-info {
            margin-top: 2rem;
            font-family: monospace;
            font-size: 0.8rem;
            color: #ff6b6b;
            text-align: left;
            background: rgba(0,0,0,0.5);
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p class="subtitle">
            <?php if (ENVIRONMENT !== 'production') : ?>
                <?= nl2br(esc($message)) ?>
            <?php else : ?>
                Kordinat yang Anda cari tidak ditemukan dalam database sistem. Silakan kembali ke jalur aman.
            <?php endif; ?>
        </p>
        <a href="<?= base_url() ?>" class="btn-back">Kembali ke Radar</a>
    </div>
</body>
</html>
