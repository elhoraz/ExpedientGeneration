<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Sistem Gangguan | Expedient Generation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600&display=swap');
        
        body {
            height: 100vh;
            margin: 0;
            background: #050505;
            font-family: 'Inter', sans-serif;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at center, #1a1a1a 0%, #050505 100%);
        }
        .vault-box {
            text-align: center;
            padding: 4rem;
            border: 1px solid rgba(255, 69, 58, 0.3);
            background: rgba(10, 0, 0, 0.8);
            border-radius: 8px;
            max-width: 500px;
            box-shadow: 0 0 30px rgba(255, 69, 58, 0.1);
        }
        .icon {
            font-size: 4rem;
            color: #ff453a;
            margin-bottom: 1rem;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin: 0 0 1rem 0;
            color: #fff;
            letter-spacing: 1px;
        }
        p {
            color: #999;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            padding: 10px 25px;
            background: transparent;
            color: #fff;
            border: 1px solid #555;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        .btn:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="vault-box">
        <div class="icon">⚠</div>
        <h1>Akses Terganggu</h1>
        <p>Protokol keamanan mendeteksi anomali pada sistem. Tim teknis Syndicate telah diberitahu untuk mengamankan jaringan.</p>
        <a href="<?= base_url() ?>" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
