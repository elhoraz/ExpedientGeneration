<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Alumni - Expedient</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; background-color: #000; overflow: hidden; font-family: 'Inter', sans-serif; }
        #mapFlat { width: 100vw; height: 100vh; z-index: 1; }
        .leaflet-control-attribution { display: none !important; }

        /* ================= TOMBOL KEMBALI ================= */
        .btn-back {
            position: absolute; top: 30px; left: 30px; z-index: 999;
            background: rgba(10, 15, 12, 0.7); backdrop-filter: blur(15px);
            border: 1px solid rgba(212, 175, 55, 0.5); color: #d4af37;
            padding: 15px 30px; border-radius: 50px; font-size: 0.9rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 3px; cursor: pointer;
            transition: all 0.4s ease; display: flex; align-items: center; gap: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-decoration: none;
        }
        .btn-back:hover { background: #d4af37; color: #000; transform: translateY(-3px); }

        /* ================= MARKER RADAR ================= */
        .marker-center {
            width: 24px !important; height: 24px !important; margin-left: -12px; margin-top: -12px;
            background-color: #00ff88; border-radius: 50%; box-shadow: 0 0 20px #00ff88, 0 0 40px #00ff88;
            animation: pulseCenter 2s infinite; border: 2px solid #fff;
        }
        @keyframes pulseCenter { 0% { box-shadow: 0 0 0 0 rgba(0,255,136, 0.7); } 70% { box-shadow: 0 0 0 20px rgba(0,255,136, 0); } 100% { box-shadow: 0 0 0 0 rgba(0,255,136, 0); } }

        .marker-agent {
            width: 16px !important; height: 16px !important; margin-left: -8px; margin-top: -8px;
            background-color: #d4af37; border-radius: 50%; box-shadow: 0 0 15px #d4af37;
            animation: pulseAgent 2s infinite; border: 2px solid #fff;
        }
        @keyframes pulseAgent { 0% { box-shadow: 0 0 0 0 rgba(212,175,55, 0.7); } 70% { box-shadow: 0 0 0 15px rgba(212,175,55, 0); } 100% { box-shadow: 0 0 0 0 rgba(212,175,55, 0); } }

        /* ================= POPUP KUSTOM ================= */
        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background: rgba(10, 15, 12, 0.9) !important; backdrop-filter: blur(10px) !important;
            border: 1px solid #d4af37 !important; color: #fff !important; box-shadow: 0 10px 30px rgba(0,0,0,0.8) !important;
        }
        .leaflet-popup-content { margin: 15px 20px; }
        .leaflet-popup-close-button { color: #fff !important; top: 5px !important; right: 10px !important; }
        .tt-name { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: #d4af37; margin-bottom: 5px; padding-right: 15px;}
        .tt-loc { font-size: 0.8rem; color: #aaa; letter-spacing: 1px; text-transform: uppercase; }

        /* ================= LOADING OVERLAY ================= */
        #loadingOverlay {
            position: fixed; inset: 0; background: #000; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
            color: #d4af37; font-family: 'Courier New', monospace; font-size: 1.2rem; letter-spacing: 5px;
            transition: opacity 1s ease; text-align: center;
        }

        /* ================= OPTIMASI HP (RESPONSIVE MOBILE) ================= */
        @media (max-width: 768px) {
            /* Perkecil dan geser tombol kembali agar tidak nabrak notch kamera HP */
            .btn-back {
                top: 15px; left: 15px;
                padding: 10px 20px; font-size: 0.75rem; letter-spacing: 2px;
                gap: 10px;
            }
            
            /* Perkecil teks loading agar tidak terpotong di layar sempit */
            #loadingOverlay { font-size: 0.9rem; letter-spacing: 3px; padding: 0 20px; }
            
            /* Sesuaikan popup agar tidak kepanjangan di layar HP */
            .leaflet-popup-content { margin: 10px 15px; }
            .tt-name { font-size: 1.1rem; }
            .tt-loc { font-size: 0.7rem; }
        }
    </style>
</head>
<body>

    <div id="loadingOverlay">Memuat Peta Satelit...</div>

    <a href="<?= base_url('radar') ?>" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Globe
    </a>

    <div id="mapFlat"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            let alumniData = <?= json_encode($alumni_nodes) ?>;
            let centerNode = alumniData.find(n => n.type === 'center');
            
            const map = L.map('mapFlat', { zoomControl: false }).setView([centerNode ? centerNode.lat : -2.5, centerNode ? centerNode.lng : 118], 5);

            // =======================================================
            // RETASAN SATELIT GOOGLE (Zoom Super Detail)
            // =======================================================
            L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 22, 
                attribution: 'Expedient Heritage'
            }).addTo(map);

            map.whenReady(() => {
                setTimeout(() => {
                    const overlay = document.getElementById('loadingOverlay');
                    overlay.style.opacity = '0';
                    setTimeout(() => overlay.remove(), 1000);
                }, 800);
            });

            const iconCenter = L.divIcon({ className: 'marker-center' });
            const iconAgent = L.divIcon({ className: 'marker-agent' });

            alumniData.forEach(node => {
                const popupHtml = `
                    <div class="tt-name">${node.name}</div>
                    <div class="tt-loc"><i class="fa-solid fa-location-dot"></i> ${node.city}</div>
                    <div style="font-size:0.7rem; color:#777; margin-top:5px; font-family:monospace;">
                        LAT: ${parseFloat(node.lat).toFixed(4)}<br>LNG: ${parseFloat(node.lng).toFixed(4)}
                    </div>
                `;
                
                const iconToUse = node.type === 'center' ? iconCenter : iconAgent;
                const marker = L.marker([node.lat, node.lng], { icon: iconToUse })
                                .addTo(map)
                                .bindPopup(popupHtml);

                marker.on('click', function() {
                    map.flyTo([node.lat, node.lng], 20, {
                        animate: true,
                        duration: 3 
                    });
                });
            });
        });
    </script>
</body>
</html>