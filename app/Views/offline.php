<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Koneksi Terputus - Expedient</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #030504;
            --text-primary: #f5f5f7;
            --text-secondary: #86868b;
            --gold-premium: #d4af37;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            background-image: radial-gradient(circle at 50% 0%, rgba(212,175,55,0.05) 0%, transparent 70%);
        }
        .container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }
        .logo {
            width: 80px;
            margin-bottom: 30px;
            filter: drop-shadow(0 0 20px rgba(212,175,55,0.3));
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--gold-premium);
            margin: 0 0 15px 0;
            letter-spacing: 2px;
        }
        p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 0 0 40px 0;
        }
        .btn-retry {
            background: transparent;
            color: var(--gold-premium);
            border: 1px solid var(--gold-premium);
            padding: 12px 30px;
            border-radius: 30px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-retry:hover {
            background: var(--gold-premium);
            color: #000;
            box-shadow: 0 0 20px rgba(212,175,55,0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/images/logo-utuh.png" alt="Expedient Logo" class="logo">
        <h1>Koneksi Terputus</h1>
        <p>Jaringan menuju Arsip Eksekutif sedang mengalami gangguan. Sistem Sovereign tidak dapat memverifikasi identitas Anda saat ini. Menunggu sinyal pulih.</p>
        <button class="btn-retry" onclick="window.location.reload()">Coba Hubungkan Kembali</button>
    </div>
</body>
</html>
