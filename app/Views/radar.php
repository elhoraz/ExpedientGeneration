<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Global Radar - 42nd Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="/css/radar.css">
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
        <div class="hud-subtitle">Persebaran Alumni di Seluruh Dunia</div>
        <div class="stats-panel">
            <div class="stat-box">
                <div class="stat-num" id="totalAgents"><?= count($alumni_nodes) > 0 ? count($alumni_nodes) - 1 : 0 ?></div>
                <div class="stat-label">Alumni Tercatat</div>
            </div>
            <div class="stat-box" style="border-left-color: #00ff88;">
                <div class="stat-num" id="livePingCount">ON</div>
                <div class="stat-label">Status Peta</div>
            </div>
        </div>
    </div>

    <div class="scanner-controls">
        <button class="btn-scan hover-trigger" id="btnScanGPS">
            <i class="fa-solid fa-satellite-dish"></i> Sinkronisasi Lokasi Saya
            <div class="ping-dot"></div>
        </button>
        <div class="scan-status" id="scanStatus">Menunggu izin lokasi...</div>
        
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
            ? '#b08d85' 
            : '#d4af37'; 

        // INISIALISASI BUMI BERSAMA ATMOSFER & COMET TRAILS
        const world = Globe()(elem)
            .globeImageUrl(getGlobeTexture())
            .bumpImageUrl('//unpkg.com/three-globe/example/img/earth-topology.png')
            .backgroundColor('rgba(0,0,0,0)') 
            .showAtmosphere(true)           
            .atmosphereColor(getAtmosphereColor())
            .atmosphereAltitude(0.15)
            .arcsData(arcData)
            .arcColor('color')
            .arcDashLength(0.15)            
            .arcDashGap(2)                  
            .arcDashInitialGap(() => Math.random() * 5)
            .arcDashAnimateTime(2000)       
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

        if(centerNode) {
            world.pointOfView({ lat: centerNode.lat, lng: centerNode.lng, altitude: 0.1 });
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
        // PROSES SINKRONISASI LOKASI
        // =========================================================
        const btnScan = document.getElementById('btnScanGPS');
        const scanStatus = document.getElementById('scanStatus');
        const totalAgentsCounter = document.getElementById('totalAgents');
        const scannerOverlay = document.getElementById('scannerOverlay');
        const scannerLine = document.getElementById('scannerLine');
        let isScanning = false;

        const stopScanner = (message, isError = true) => {
            scanStatus.innerText = message;
            scanStatus.style.color = isError ? "#ff3366" : "#00ff88";
            btnScan.innerHTML = isError ? '<i class="fa-solid fa-triangle-exclamation"></i> Proses Gagal' : '<i class="fa-solid fa-check-double"></i> Tersinkronisasi';
            gsap.to(scannerOverlay, { opacity: 0, duration: 0.3, onComplete: () => scannerOverlay.style.display = 'none' });
            isScanning = false;
        };

        btnScan.addEventListener('click', () => {
            if(isScanning) return;
            isScanning = true;

            if (navigator.vibrate) navigator.vibrate(20);
            btnScan.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mencari Lokasi...';
            scanStatus.style.opacity = 1;
            scanStatus.innerText = "Meminta Izin Akses Lokasi Perangkat...";
            scanStatus.style.color = "#d4af37";

            scannerOverlay.style.display = 'block';
            gsap.to(scannerOverlay, { opacity: 1, duration: 0.3 });
            
            let scanAnim = gsap.fromTo(scannerLine, 
                { y: -10 }, 
                { y: window.innerHeight, duration: 1.5, ease: "linear", repeat: -1 }
            );

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        scanStatus.innerText = "Menyelaraskan Titik Koordinat...";
                        
                        // Menambahkan email agar patuh aturan Nominatim API dan tidak kena ban
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLat}&lon=${userLng}&zoom=10&addressdetails=1&email=<?= $nominatim_email ?? 'admin@expedient.com' ?>`)
                        .then(res => res.json())
                        .then(geoData => {
                            const realCity = geoData.address.city || geoData.address.town || geoData.address.county || geoData.address.state || "Lokasi Anda";
                            
                            // MENGGUNAKAN CSRF TOKEN UNTUK KEAMANAN
                            fetch('<?= base_url('api/location/update') ?>', {
                                method: 'POST',
                                headers: { 
                                    'Content-Type': 'application/json', 
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>' 
                                },
                                body: JSON.stringify({ lat: userLat, lng: userLng })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.status === 'success') {
                                    scanAnim.kill();
                                    stopScanner(`LOKASI DITEMUKAN DI: ${realCity.toUpperCase()}`, false);
                                    
                                    if (navigator.vibrate) navigator.vibrate([50, 100, 50]);

                                    btnScan.innerHTML = '<i class="fa-solid fa-location-dot"></i> ' + realCity;
                                    btnScan.style.background = 'rgba(0, 255, 136, 0.2)';
                                    btnScan.style.borderColor = '#00ff88';
                                    btnScan.style.color = '#fff';
                                    btnScan.style.boxShadow = '0 10px 30px rgba(0, 255, 136, 0.4)';

                                    world.controls().autoRotate = false;
                                    world.pointOfView({ lat: userLat, lng: userLng, altitude: 0.4 }, 3000);

                                    const newMeNode = { name: "Anda (Perangkat Ini)", city: realCity, lat: userLat, lng: userLng, type: 'agent' };
                                    const newArc = { startLat: centerNode.lat, startLng: centerNode.lng, endLat: userLat, endLng: userLng, color: ['rgba(212,175,55,0.0)', 'rgba(0, 255, 136, 1)'] };

                                    world.labelsData([...world.labelsData(), newMeNode]);
                                    world.ringsData([...world.ringsData(), newMeNode]);
                                    world.arcsData([...world.arcsData(), newArc]);
                                    
                                    let currentTotal = parseInt(totalAgentsCounter.innerText);
                                    totalAgentsCounter.innerText = currentTotal + 1;
                                } else {
                                    scanAnim.kill();
                                    stopScanner("GAGAL MENYIMPAN KE DATABASE LOKAL.", true);
                                }
                            })
                            .catch(err => {
                                scanAnim.kill();
                                stopScanner("SERVER INTERNAL TIDAK MERESPONS.", true);
                            });
                        })
                        .catch(err => {
                            scanAnim.kill();
                            stopScanner("API PETA (NOMINATIM) SIBUK / ERROR.", true);
                        });
                    },
                    (error) => {
                        scanAnim.kill();
                        stopScanner("IZIN DITOLAK ATAU GPS TIDAK AKTIF.", true);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                stopScanner("BROWSER TIDAK MENDUKUNG GEOLOCATION.", true);
            }
        });
    });
</script>
<?= $this->endSection() ?>