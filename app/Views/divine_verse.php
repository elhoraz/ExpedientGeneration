<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kalam Ilahi - Pesan Harian</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --gold: #d4af37; --dark: #020202; --green: #1b5e20; --green-glow: rgba(27,94,32,0.3); }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--dark); color: #fff; font-family: 'Inter', sans-serif; overflow: hidden; user-select: none; height: 100vh; width: 100vw; }

        .btn-back { position: fixed; top: 30px; left: 30px; z-index: 200; display: flex; align-items: center; gap: 10px; padding: 10px 20px; background: rgba(0,0,0,0.6); border: 1px solid rgba(212,175,55,0.3); border-radius: 8px; color: var(--gold); font-size: 11px; font-weight: 600; letter-spacing: 3px; text-decoration: none; text-transform: uppercase; backdrop-filter: blur(10px); transition: all 0.3s ease; }
        .btn-back:hover { transform: translateX(-5px); box-shadow: 0 0 20px rgba(212,175,55,0.5); color: #fff; }

        /* Islamic Geometric BG */
        .geo-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.04; }
        .geo-bg svg { width: 100%; height: 100%; }

        /* Ambient */
        .ambient { position: fixed; inset: 0; z-index: 1; pointer-events: none; }
        .ambient::before { content: ''; position: absolute; width: 60vw; height: 60vw; top: 50%; left: 50%; transform: translate(-50%, -50%); background: radial-gradient(circle, rgba(27,94,32,0.08) 0%, transparent 70%); animation: ambPulse 5s infinite alternate; }
        @keyframes ambPulse { 0% { transform: translate(-50%,-50%) scale(1); } 100% { transform: translate(-50%,-50%) scale(1.4); } }

        .particle { position: fixed; width: 2px; height: 2px; background: var(--gold); border-radius: 50%; pointer-events: none; z-index: 2; opacity: 0; animation: floatP linear infinite; }
        @keyframes floatP { 0% { opacity:0; transform: translateY(100vh) scale(0); } 10% { opacity:0.6; } 90% { opacity:0.6; } 100% { opacity:0; transform: translateY(-10vh) scale(1); } }

        .divine-wrapper { position: relative; z-index: 10; width: 100vw; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 25px; padding: 20px; }

        .page-title { font-family: 'Playfair Display', serif; font-size: clamp(1.3rem, 3.5vw, 2.2rem); color: var(--gold); letter-spacing: 6px; text-transform: uppercase; text-align: center; opacity: 0; transform: translateY(-20px); }
        .page-sub { font-size: 0.75rem; color: #7b8e9b; letter-spacing: 4px; text-align: center; opacity: 0; transform: translateY(-10px); }

        /* Verse Card */
        .verse-card { width: clamp(300px, 85vw, 600px); background: rgba(5,10,8,0.6); backdrop-filter: blur(20px); border: 1px solid rgba(212,175,55,0.2); border-radius: 20px; padding: clamp(30px, 6vw, 50px); text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.8); opacity: 0; transform: translateY(30px) scale(0.95); transition: border-color 1s; position: relative; overflow: hidden; }
        .verse-card::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: conic-gradient(from 0deg, transparent 0%, rgba(27,94,32,0.05) 25%, transparent 50%, rgba(212,175,55,0.03) 75%, transparent 100%); animation: rotateBg 20s linear infinite; pointer-events: none; }
        @keyframes rotateBg { 100% { transform: rotate(360deg); } }

        .bismillah { font-family: 'Amiri', serif; font-size: clamp(1.5rem, 4vw, 2.2rem); color: var(--gold); margin-bottom: 20px; opacity: 0; direction: rtl; filter: drop-shadow(0 0 10px rgba(212,175,55,0.3)); }

        .ayat-arabic { font-family: 'Amiri', serif; font-size: clamp(1.6rem, 5vw, 2.8rem); color: #fff; line-height: 2; direction: rtl; margin-bottom: 25px; opacity: 0; transform: translateY(15px); text-shadow: 0 0 20px rgba(255,255,255,0.1); }

        .divider { width: 60px; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent); margin: 0 auto 20px; opacity: 0; }

        .ayat-latin { font-style: italic; font-size: clamp(0.75rem, 2vw, 0.9rem); color: #8b9ba8; line-height: 1.8; margin-bottom: 15px; opacity: 0; transform: translateY(10px); }

        .ayat-meaning { font-size: clamp(0.8rem, 2vw, 0.95rem); color: #c0c8cf; line-height: 1.8; margin-bottom: 20px; opacity: 0; transform: translateY(10px); }

        .ayat-source { font-family: 'Courier New', monospace; font-size: 0.7rem; color: var(--gold); letter-spacing: 3px; text-transform: uppercase; opacity: 0; }

        .btn-reveal { padding: 15px 45px; background: rgba(27,94,32,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(27,94,32,0.5); color: #4caf50; font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 4px; border-radius: 50px; cursor: pointer; transition: all 0.4s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.5); opacity: 0; transform: translateY(20px); }
        .btn-reveal:hover { background: #1b5e20; color: #fff; box-shadow: 0 15px 40px rgba(27,94,32,0.4); transform: translateY(-3px); }

        .btn-new { background: transparent; border: none; color: rgba(255,255,255,0.4); font-size: 0.7rem; letter-spacing: 3px; text-transform: uppercase; cursor: pointer; text-decoration: underline; transition: 0.3s; display: none; margin-top: 10px; }
        .btn-new:hover { color: #fff; }

        @media (max-width: 768px) { .btn-back { top: 20px; left: 20px; padding: 8px 15px; font-size: 10px; } }
    </style>
</head>
<body>
    <div class="geo-bg">
        <svg viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs><pattern id="isl" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <polygon points="10,0 20,5 20,15 10,20 0,15 0,5" fill="none" stroke="rgba(212,175,55,0.5)" stroke-width="0.3"/>
                <circle cx="10" cy="10" r="2" fill="none" stroke="rgba(27,94,32,0.5)" stroke-width="0.2"/>
            </pattern></defs>
            <rect width="100" height="100" fill="url(#isl)"/>
        </svg>
    </div>
    <div class="ambient"></div>
    <div id="particleField"></div>

    <a href="/fitur" class="btn-back"><i class="fa-solid fa-chevron-left"></i> Kembali</a>

    <div class="divine-wrapper">
        <h1 class="page-title" id="pageTitle">Kalam Ilahi</h1>
        <p class="page-sub" id="pageSub">Terima pesan suci yang ditakdirkan untuk Anda hari ini</p>

        <div class="verse-card" id="verseCard">
            <div class="bismillah" id="bismillah">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</div>
            <div class="ayat-arabic" id="ayatArabic"></div>
            <div class="divider" id="divider"></div>
            <div class="ayat-latin" id="ayatLatin"></div>
            <div class="ayat-meaning" id="ayatMeaning"></div>
            <div class="ayat-source" id="ayatSource"></div>
        </div>

        <button class="btn-reveal" id="btnReveal"><i class="fa-solid fa-star-and-crescent"></i>&nbsp; Terima Kalam</button>
        <button class="btn-new" id="btnNew">Terima Kalam Lain</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const verses = <?= json_encode($verses) ?>;


        // Particles
        const pf = document.getElementById('particleField');
        for (let i = 0; i < 25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random()*100+'vw';
            p.style.animationDuration = (Math.random()*8+6)+'s';
            p.style.animationDelay = (Math.random()*10)+'s';
            pf.appendChild(p);
        }

        // Entrance
        const tl = gsap.timeline();
        tl.to('#pageTitle', { opacity:1, y:0, duration:1, ease:"power3.out" }, 0.3)
          .to('#pageSub', { opacity:1, y:0, duration:1, ease:"power3.out" }, 0.6)
          .to('#verseCard', { opacity:1, y:0, scale:1, duration:1, ease:"power3.out" }, 0.8)
          .to('#btnReveal', { opacity:1, y:0, duration:0.8, ease:"power3.out" }, 1.2);

        function revealVerse() {
            const v = verses[Math.floor(Math.random() * verses.length)];
            document.getElementById('ayatArabic').innerText = v.ar;
            document.getElementById('ayatLatin').innerText = '"' + v.lt + '"';
            document.getElementById('ayatMeaning').innerText = v.mn;
            document.getElementById('ayatSource').innerText = v.src;

            document.getElementById('btnReveal').style.display = 'none';
            if (navigator.vibrate) navigator.vibrate([30, 50, 30]);

            const reveal = gsap.timeline();
            reveal.to('#bismillah', { opacity:1, duration:1, ease:"power3.out" }, 0)
                  .to('#ayatArabic', { opacity:1, y:0, duration:1.2, ease:"power3.out" }, 0.5)
                  .to('#divider', { opacity:1, width:60, duration:0.8, ease:"power3.out" }, 1.2)
                  .to('#ayatLatin', { opacity:1, y:0, duration:0.8, ease:"power3.out" }, 1.5)
                  .to('#ayatMeaning', { opacity:1, y:0, duration:0.8, ease:"power3.out" }, 1.8)
                  .to('#ayatSource', { opacity:1, duration:0.6, ease:"power3.out" }, 2.2)
                  .then(() => {
                      document.getElementById('btnNew').style.display = 'inline-block';
                      gsap.from('#btnNew', { opacity:0, y:10, duration:0.5 });
                  });
        }

        function resetVerse() {
            gsap.set(['#bismillah','#ayatArabic','#divider','#ayatLatin','#ayatMeaning','#ayatSource'], { opacity:0 });
            gsap.set(['#ayatArabic','#ayatLatin','#ayatMeaning'], { y:15 });
            gsap.set('#divider', { width:0 });
            document.getElementById('btnNew').style.display = 'none';
            document.getElementById('btnReveal').style.display = 'inline-block';
        }

        document.getElementById('btnReveal').addEventListener('click', revealVerse);
        document.getElementById('btnNew').addEventListener('click', () => { resetVerse(); setTimeout(revealVerse, 300); });
    });
    </script>
</body>
</html>
