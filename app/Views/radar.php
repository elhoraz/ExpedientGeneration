<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Global Radar - 42nd Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ================= RADAR STAGE (DESKTOP) ================= */
    .radar-container { position: relative; width: 100vw; height: 100vh; overflow: hidden; background: transparent; margin-top: -80px; }
    
    #globeViz {
        position: absolute; top: 0; right: -15vw; 
        width: 100%; height: 100%; z-index: 1; cursor: grab;
    }
    #globeViz:active { cursor: grabbing; }

    /* ================= LUXURY HUD (DESKTOP) ================= */
    .radar-hud { position: absolute; top: 120px; left: 50px; z-index: 20; pointer-events: none; }
    .hud-title { font-family: 'Playfair Display', serif; font-size: 3.5rem; color: var(--text-primary); font-weight: 900; letter-spacing: 2px; margin-bottom: 5px; text-shadow: 0 10px 30px rgba(0,0,0,0.8); }
    .hud-subtitle { font-family: 'Courier New', monospace; font-size: 1rem; color: #d4af37; letter-spacing: 8px; text-transform: uppercase; font-weight: 600; }
    .stats-panel { margin-top: 40px; display: flex; gap: 30px; }
    .stat-box { background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-border); border-left: 3px solid #d4af37; padding: 20px 30px; border-radius: 8px; box-shadow: var(--glass-shadow); }
    .stat-num { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #d4af37; font-weight: 700; line-height: 1; }
    .stat-label { font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 3px; margin-top: 5px; }

    /* ================= CONTROLS & BUTTONS (DESKTOP) ================= */
    .scanner-controls { position: absolute; bottom: 50px; left: 50px; z-index: 30; pointer-events: auto; display: flex; flex-direction: column; gap: 15px; }
    
    .btn-scan {
        background: rgba(212, 175, 55, 0.1); backdrop-filter: var(--glass-blur); border: 1px solid rgba(212, 175, 55, 0.4); color: #d4af37;
        padding: 18px 40px; border-radius: 50px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 4px; 
        cursor: pointer; transition: all 0.4s ease; display: flex; align-items: center; justify-content: center; gap: 15px; box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }
    .btn-scan:hover { background: #d4af37; color: #000; box-shadow: 0 20px 40px rgba(212, 175, 55, 0.4); transform: translateY(-3px); }
    .btn-scan .ping-dot { width: 8px; height: 8px; background: currentColor; border-radius: 50%; position: relative; }
    .btn-scan .ping-dot::after { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 1px solid currentColor; animation: radarPing 1.5s infinite; }
    @keyframes radarPing { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(3); opacity: 0; } }

    .btn-flat {
        background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); color: var(--text-primary);
        padding: 15px 40px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 3px; 
        cursor: pointer; transition: all 0.4s ease; display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-flat:hover { background: rgba(255, 255, 255, 0.15); border-color: #fff; transform: translateY(-2px); }
    .scan-status { font-family: 'Courier New', monospace; font-size: 0.75rem; color: #00ff88; letter-spacing: 2px; opacity: 0; transition: 0.4s; text-align: center; }

    /* ================= TOOLTIP GLOBE ================= */
    .scene-tooltip { background: rgba(10, 15, 12, 0.85) !important; backdrop-filter: blur(10px) !important; border: 1px solid #d4af37 !important; border-radius: 8px !important; padding: 15px 20px !important; color: #fff !important; font-family: 'Inter', sans-serif !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; pointer-events: none; }
    .tt-name { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #d4af37; margin-bottom: 5px; }
    .tt-loc { font-size: 0.8rem; color: #aaa; letter-spacing: 1px; text-transform: uppercase; }

    /* ================= NEW: SCANNER PROTOCOL FX ================= */
    .scanner-overlay {
        position: fixed; inset: 0; z-index: 9998; pointer-events: none; opacity: 0; display: none;
        background: radial-gradient(circle at center, transparent 0%, rgba(212,175,55,0.05) 100%);
    }
    .scanner-line {
        position: absolute; top: -10px; left: 0; width: 100%; height: 2px; background: #d4af37;
        box-shadow: 0 0 20px 5px rgba(212, 175, 55, 0.6), 0 50px 100px 20px rgba(212, 175, 55, 0.2);
    }
    .flash-bang {
        position: fixed; inset: 0; background: #fff; z-index: 9999; opacity: 0; pointer-events: none; display: none;
    }

    /* ================= PERBAIKAN MOBILE ================= */
    @media (max-width: 768px) {
        #globeViz { right: 0; top: 0; width: 100vw; height: 100vh; } 
        .radar-hud { top: 70px; left: 15px; right: 15px; width: auto; text-align: center; }
        .hud-title { font-size: 2rem; } 
        .hud-subtitle { font-size: 0.75rem; letter-spacing: 4px; }
        .stats-panel { margin-top: 20px; justify-content: center; gap: 10px; }
        .stat-box { padding: 10px 15px; } 
        .stat-num { font-size: 1.5rem; }
        .stat-label { font-size: 0.6rem; letter-spacing: 2px; }
        .scanner-controls { bottom: 25px; left: 15px; right: 15px; width: auto; gap: 10px; }
        .btn-scan { padding: 12px 15px; font-size: 0.75rem; letter-spacing: 2px; }
        .btn-flat { padding: 10px 15px; font-size: 0.7rem; letter-spacing: 2px; }
        .scan-status { margin-top: 5px; font-size: 0.65rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="radar-container">
    <div id="globeViz"></div>

    <div class="scanner-overlay" id="scannerOverlay">
        <div class="scanner-line" id="scannerLine"></div>
    </div>
    <div class="flash-bang" id="flashBang"></div>

    <div class="radar-hud">
        <h1 class="hud-title">Global Radar</h1>
        <div class="hud-subtitle">Sektor Pemantauan Geografis</div>
        <div class="stats-panel">
            <div class="stat-box">
                <div class="stat-num" id="totalAgents"><?= count($alumni_nodes) > 0 ? count($alumni_nodes) - 1 : 0 ?></div>
                <div class="stat-label">Entitas Aktif</div>
            </div>
            <div class="stat-box" style="border-left-color: #00ff88;">
                <div class="stat-num" id="livePingCount">ON</div>
                <div class="stat-label">Sistem Pelacakan</div>
            </div>
        </div>
    </div>

    <div class="scanner-controls">
        <button class="btn-scan hover-trigger" id="btnScanGPS">
            <i class="fa-solid fa-satellite-dish"></i> Sinkronisasi Lokasi Saya
            <div class="ping-dot"></div>
        </button>
        <div class="scan-status" id="scanStatus">Menunggu otorisasi GPS...</div>
        
        <button class="btn-flat hover-trigger" id="btnGoFlat">
            <i class="fa-solid fa-map"></i> Beralih ke Peta Datar (2D)
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/globe.gl"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script> <script>
    document.addEventListener("DOMContentLoaded", () => {
        
        let alumniData = <?= json_encode($alumni_nodes) ?>;
        let centerNode = alumniData.find(n => n.type === 'center');
        let agentNodes = alumniData.filter(n => n.type === 'agent');

        let arcData = agentNodes.map(agent => ({
            startLat: centerNode.lat, startLng: centerNode.lng,
            endLat: agent.lat, endLng: agent.lng,
            color: ['rgba(212,175,55,0.0)', 'rgba(212,175,55,1)'] // Gradasi untuk efek ekor komet
        }));

        const isMobile = window.innerWidth <= 768;
        const defaultAltitude = isMobile ? 3.5 : 2.2;
        const elem = document.getElementById('globeViz');
        
        const getGlobeTexture = () => document.documentElement.getAttribute('data-theme') === 'light' 
            ? '//unpkg.com/three-globe/example/img/earth-day.jpg' 
            : '//unpkg.com/three-globe/example/img/earth-night.jpg';

        const getAtmosphereColor = () => document.documentElement.getAttribute('data-theme') === 'light' 
            ? '#b08d85' // Elegan soft gold/brown untuk tema terang
            : '#d4af37'; // Pure Syndicate Gold untuk gelap

        // INISIALISASI BUMI BERSAMA ATMOSFER & COMET TRAILS
        const world = Globe()(elem)
            .globeImageUrl(getGlobeTexture())
            .bumpImageUrl('//unpkg.com/three-globe/example/img/earth-topology.png')
            .backgroundColor('rgba(0,0,0,0)') 
            .showAtmosphere(true)           // IDEA 1: The Syndicate Glow
            .atmosphereColor(getAtmosphereColor())
            .atmosphereAltitude(0.15)
            .arcsData(arcData)
            .arcColor('color')
            .arcDashLength(0.15)            // IDEA 3: Komet lebih pendek & padat
            .arcDashGap(2)                  // Jarak antar komet
            .arcDashInitialGap(() => Math.random() * 5)
            .arcDashAnimateTime(2000)       // Kecepatan meluncur komet
            .arcStroke(1.2)
            .ringsData(alumniData)
            .ringColor(d => d.type === 'center' ? t => `rgba(0,255,136,${1-t})` : t => `rgba(212,175,55,${1-t})`)
            .ringMaxRadius(d => d.type === 'center' ? 5 : 3)
            .ringPropagationSpeed(d => d.type === 'center' ? 2 : 1)
            .ringRepeatPeriod(d => d.type === 'center' ? 800 : 1500)
            .labelsData(alumniData)
            .labelLat(d => d.lat)
            .labelLng(d => d.lng)
            .labelText(d => '') 
            .labelSize(1.5)
            .labelDotRadius(0.5)
            .labelColor(d => d.type === 'center' ? '#00ff88' : '#d4af37')
            .labelResolution(2)
            .labelLabel(d => `
                <div class="tt-name">${d.name}</div>
                <div class="tt-loc"><i class="fa-solid fa-location-dot"></i> ${d.city}</div>
                <div style="font-size:0.7rem; color:#777; margin-top:5px; font-family:monospace;">
                    LAT: ${d.lat.toFixed(4)} | LNG: ${d.lng.toFixed(4)}
                </div>
            `);

        // IDEA 2: CINEMATIC DEPLOYMENT (Intro Kamera Dari Dekat Menjauh)
        if(centerNode) {
            // Mulai menatap lurus ke titik pusat Arrisalah
            world.pointOfView({ lat: centerNode.lat, lng: centerNode.lng, altitude: 0.1 });
            
            // Berikan jeda sejenak untuk rendering, lalu Zoom-Out spektakuler
            setTimeout(() => {
                world.pointOfView({ lat: centerNode.lat - (isMobile ? 0 : 10), lng: centerNode.lng, altitude: defaultAltitude }, 4000);
            }, 800);
        }
        
        world.controls().autoRotate = true;
        world.controls().autoRotateSpeed = 0.5;
        world.controls().minDistance = 10; 

        window.addEventListener('resize', () => {
            world.width([window.innerWidth]);
            world.height([window.innerHeight]);
        });

        // SENSOR MUTASI TEMA
        const themeObserver = new MutationObserver(() => {
            world.globeImageUrl(getGlobeTexture()); 
            world.atmosphereColor(getAtmosphereColor());
        });
        themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

        // Tombol Peta Datar
        document.getElementById('btnGoFlat').addEventListener('click', () => {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            setTimeout(() => { window.location.href = '<?= base_url('radar/flat') ?>'; }, 500);
        });

        // =========================================================
        // AUDIO ENGINE UNTUK RADAR SCAN
        // =========================================================
        let audioCtx;
        const initAudio = () => {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
        };

        const playSonarSweep = () => {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(400, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 2.0);
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, audioCtx.currentTime + 0.5);
            gain.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 2.0);
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 2.0);
        };

        const playTargetLock = () => {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(1500, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(2500, audioCtx.currentTime + 0.1);
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.8);
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.8);
        };

        // =========================================================
        // IDEA 4: PROTOKOL TARGET LOCK (GPS SCANNER FX)
        // =========================================================
        const btnScan = document.getElementById('btnScanGPS');
        const scanStatus = document.getElementById('scanStatus');
        const totalAgentsCounter = document.getElementById('totalAgents');
        const scannerOverlay = document.getElementById('scannerOverlay');
        const scannerLine = document.getElementById('scannerLine');
        const flashBang = document.getElementById('flashBang');
        let isScanning = false;

        btnScan.addEventListener('click', () => {
            if(isScanning) return;
            isScanning = true;

            if (navigator.vibrate) navigator.vibrate(20);
            btnScan.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mendeteksi Satelit...';
            scanStatus.style.opacity = 1;
            scanStatus.innerText = "Meminta Otorisasi Akses Lokasi Perangkat...";
            scanStatus.style.color = "#d4af37";

            // Mainkan Animasi Scanner Hologram & Audio Sonar
            playSonarSweep();
            scannerOverlay.style.display = 'block';
            gsap.to(scannerOverlay, { opacity: 1, duration: 0.3 });
            
            // Loop laser scanner dari atas ke bawah
            let scanAnim = gsap.fromTo(scannerLine, 
                { y: -10 }, 
                { y: window.innerHeight, duration: 1.5, ease: "linear", repeat: -1 }
            );

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        scanStatus.innerText = "Menerjemahkan Koordinat Geografis...";
                        
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}&zoom=10&addressdetails=1`)
                        .then(res => res.json())
                        .then(geoData => {
                            const realCity = geoData.address.city || geoData.address.town || geoData.address.county || geoData.address.state || "Lokasi Satelit";
                            
                            fetch('<?= base_url('radar/update-location') ?>', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                body: JSON.stringify({ lat: userLat, lng: userLng })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.status === 'success') {
                                    
                                    // Matikan Scanner
                                    scanAnim.kill();
                                    gsap.to(scannerOverlay, { opacity: 0, duration: 0.3, onComplete: () => scannerOverlay.style.display = 'none' });

                                    // Efek Flash & Audio Sukses
                                    playTargetLock();
                                    if (navigator.vibrate) navigator.vibrate([50, 100, 50]);
                                    flashBang.style.display = 'block';
                                    gsap.fromTo(flashBang, { opacity: 1 }, { opacity: 0, duration: 1.5, ease: "power3.out", onComplete: () => flashBang.style.display = 'none' });

                                    scanStatus.innerText = `TARGET TERVERIFIKASI DI: ${realCity.toUpperCase()}`;
                                    scanStatus.style.color = "#00ff88";
                                    btnScan.innerHTML = '<i class="fa-solid fa-check-double"></i> ' + realCity;
                                    btnScan.style.background = 'rgba(0, 255, 136, 0.2)';
                                    btnScan.style.borderColor = '#00ff88';
                                    btnScan.style.color = '#fff';
                                    btnScan.style.boxShadow = '0 10px 30px rgba(0, 255, 136, 0.4)';

                                    world.controls().autoRotate = false;
                                    
                                    // Zoom sangat tajam ala intelijen (Altitude 0.4)
                                    world.pointOfView({ lat: userLat, lng: userLng, altitude: 0.4 }, 3000);

                                    const newMeNode = { name: "Anda (Perangkat Ini)", city: realCity, lat: userLat, lng: userLng, type: 'agent' };
                                    const newArc = { startLat: centerNode.lat, startLng: centerNode.lng, endLat: userLat, endLng: userLng, color: ['rgba(212,175,55,0.0)', 'rgba(0, 255, 136, 1)'] };

                                    world.labelsData([...world.labelsData(), newMeNode]);
                                    world.ringsData([...world.ringsData(), newMeNode]);
                                    world.arcsData([...world.arcsData(), newArc]);
                                    
                                    let currentTotal = parseInt(totalAgentsCounter.innerText);
                                    totalAgentsCounter.innerText = currentTotal + 1;
                                    
                                    // Membiarkan button terkunci setelah berhasil
                                }
                            });
                        });
                    },
                    (error) => {
                        scanAnim.kill();
                        gsap.to(scannerOverlay, { opacity: 0, duration: 0.3, onComplete: () => scannerOverlay.style.display = 'none' });
                        scanStatus.innerText = "AKSES DITOLAK ATAU SATELIT TIDAK DITEMUKAN.";
                        scanStatus.style.color = "#ff3366";
                        btnScan.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Akses Ditolak';
                        isScanning = false; // Boleh coba lagi
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            }
        });
    });
</script>
<?= $this->endSection() ?>