<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sovereign Cinematic AR - <?= esc($user['nama_panggilan'] ?? 'VIP') ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        body, html { margin: 0; padding: 0; width: 100vw; height: 100vh; background-color: #000; overflow: hidden; font-family: 'Inter', sans-serif; user-select: none; -webkit-user-select: none; touch-action: none; }
        
        #webcam { position: absolute; inset: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: 1; }
        .vault-vignette { position: absolute; inset: 0; pointer-events: none; z-index: 2; background: radial-gradient(circle at center, transparent 15%, rgba(0,0,0,0.9) 100%); }
        #canvas-container { position: absolute; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 10; outline: none; cursor: grab; }
        #canvas-container:active { cursor: grabbing; }

        .btn-vault-back { position: absolute; top: 25px; left: 25px; z-index: 100; display: flex; align-items: center; gap: 10px; padding: 10px 18px; background: rgba(0,0,0,0.7); border: 1px solid rgba(212,175,55,0.4); border-radius: 8px; color: #d4af37; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-decoration: none; text-transform: uppercase; backdrop-filter: blur(10px); transition: 0.3s; pointer-events: auto; }
        .btn-vault-back:hover { background: rgba(212,175,55,0.2); color: #fff; }
        
        .ar-controls { position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); z-index: 100; display: flex; gap: 15px; background: rgba(0,0,0,0.6); padding: 8px; border-radius: 50px; border: 1px solid rgba(212,175,55,0.3); backdrop-filter: blur(10px); pointer-events: auto; }
        .btn-switch { background: transparent; border: none; color: rgba(255,255,255,0.5); font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1px; padding: 10px 20px; border-radius: 30px; cursor: pointer; transition: 0.4s; }
        .btn-switch.active { background: #d4af37; color: #000; box-shadow: 0 0 15px rgba(212,175,55,0.4); }

        .tutorial-text { position: absolute; top: 40%; left: 0; width: 100%; text-align: center; color: rgba(212,175,55,0.8); font-family: 'Courier New', monospace; font-size: 11px; letter-spacing: 3px; z-index: 15; pointer-events: none; opacity: 1; transition: opacity 1s; text-shadow: 0 2px 10px #000; }

        #preloader { position: fixed; inset: 0; z-index: 99999; background: #050505; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: opacity 0.8s ease; }
        .loader-ring { width: 50px; height: 50px; border: 2px solid rgba(212,175,55,0.1); border-top-color: #ffd700; border-radius: 50%; animation: spinLoader 1s linear infinite; margin-bottom: 20px; }
        #loadingText { color: #ffd700; font-family: 'Courier New', monospace; letter-spacing: 4px; font-size: 11px; }
        @keyframes spinLoader { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="preloader">
        <div class="loader-ring"></div>
        <span id="loadingText">Mempersiapkan AR...</span>
    </div>

    <video id="webcam" autoplay playsinline></video>
    <div class="vault-vignette"></div>
    
    <div class="tutorial-text" id="tutorial">SWIPE TO ROTATE<br>DRAG ANYWHERE TO PULL</div>

    <div id="canvas-container"></div>

    <a href="<?= base_url('fitur') ?>" class="btn-vault-back">
        <i class="fa-solid fa-chevron-left"></i> EXIT AR
    </a>

    <div class="ar-controls">
        <button class="btn-switch active" id="btnID">ID CARD</button>
        <button class="btn-switch" id="btnKTA">KTA VIP</button>
    </div>

    <script type="importmap">
        { "imports": { "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js", "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/" } }
    </script>

    <script type="module">
        import * as THREE from 'three';
        import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';
        import { RoundedBoxGeometry } from 'three/addons/geometries/RoundedBoxGeometry.js';

        // --- DATA SINKRONISASI ---
        const expedientData = {
            nama: "<?= esc($user['nama_lengkap']) ?>",
            jabatan: "ANGGOTA VVIP",
            nomor_id: "EXP-<?= sprintf('%03d', $user['id']) ?>",
            exp: "VALID THRU FOREVER",
            foto_url: "<?= $foto_profil ?>"
        };

        const GOLD = '#d4af37';
        const PURE_GOLD = '#ffd700'; 
        const DARK_BG = '#050505';

        // MENGAKTIFKAN KAMERA BELAKANG MURNI
        const video = document.getElementById('webcam');
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => { video.srcObject = stream; })
                .catch(err => { console.warn("Kamera tidak dapat diakses", err); });
        }

        const container = document.getElementById('canvas-container');
        const scene = new THREE.Scene();

        const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 200);
        camera.position.set(0, 0, 24); 

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: "high-performance" });
        renderer.setClearColor( 0x000000, 0 ); 
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.1; 
        container.appendChild(renderer.domElement);

        const pmremGenerator = new THREE.PMREMGenerator(renderer);
        scene.environment = pmremGenerator.fromScene(new RoomEnvironment(), 0.04).texture;

        const ambientLight = new THREE.AmbientLight(0xffffff, 1.0); scene.add(ambientLight);
        const spotLight = new THREE.SpotLight(0xffeedd, 150); spotLight.position.set(10, 30, 25); scene.add(spotLight);
        const rimLight = new THREE.PointLight(PURE_GOLD, 80, 40); rimLight.position.set(-10, -5, 10); scene.add(rimLight);

        // ==========================================
        // 1. GENERATOR TEKSTUR (KUALITAS MAKSIMAL)
        // ==========================================
        function drawRealisticSmartChip(ctx, x, y, w, h, r, isBump) {
            if (!isBump) { const grad = ctx.createLinearGradient(x, y, x+w, y+h); grad.addColorStop(0, '#f9d976'); grad.addColorStop(0.5, '#d4af37'); grad.addColorStop(1, '#a67c00'); ctx.fillStyle = grad;
            } else { ctx.fillStyle = '#ffffff'; }
            ctx.beginPath(); ctx.roundRect(x, y, w, h, r); ctx.fill();
            ctx.strokeStyle = isBump ? '#000000' : 'rgba(80, 50, 0, 0.6)'; ctx.lineWidth = 3;
            ctx.beginPath(); ctx.roundRect(x+6, y+6, w-12, h-12, r-4); ctx.stroke();
            const cx = x+w/2, cy = y+h/2;
            ctx.beginPath(); ctx.ellipse(cx, cy, w*0.2, h*0.25, 0, 0, Math.PI*2); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(x+6, cy-15); ctx.lineTo(cx-w*0.2, cy-15); ctx.stroke(); ctx.beginPath(); ctx.moveTo(x+6, cy+15); ctx.lineTo(cx-w*0.2, cy+15); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(x+w-6, cy-15); ctx.lineTo(cx+w*0.2, cy-15); ctx.stroke(); ctx.beginPath(); ctx.moveTo(x+w-6, cy+15); ctx.lineTo(cx+w*0.2, cy+15); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(cx, y+6); ctx.lineTo(cx, cy-h*0.25); ctx.stroke(); ctx.beginPath(); ctx.moveTo(cx, y+h-6); ctx.lineTo(cx, cy+h*0.25); ctx.stroke();
        }

        function drawBrushedMetalMain(ctx, w, h) {
            ctx.fillStyle = DARK_BG; ctx.fillRect(0, 0, w, h); ctx.globalAlpha = 0.03;
            for(let i=0; i<w; i+=2) { ctx.fillStyle = Math.random() > 0.5 ? '#222222' : '#000000'; ctx.fillRect(i, 0, 1, h); }
            ctx.globalAlpha = 1.0; const grd = ctx.createRadialGradient(w/2, h/2, 200, w/2, h/2, w); grd.addColorStop(0, 'transparent'); grd.addColorStop(1, 'rgba(0,0,0,0.9)'); ctx.fillStyle = grd; ctx.fillRect(0, 0, w, h);
        }

        // PERBAIKAN: Memisahkan kalibrasi UV antara KTA dan ID Card
        function kalibrasiUV(texture, isBump, isKTA = false) {
            if (isKTA) {
                texture.flipY = true;  // KTA pakai Normal
                texture.wrapS = THREE.RepeatWrapping;
                texture.repeat.x = 1; 
            } else {
                texture.flipY = false; // ID Card pakai Mirror bawaan dari web utama
                texture.wrapS = THREE.RepeatWrapping;
                texture.repeat.x = -1; 
            }
            if (!isBump) texture.colorSpace = THREE.SRGBColorSpace; 
            texture.anisotropy = renderer.capabilities.getMaxAnisotropy(); 
            return texture;
        }

        function createFrontTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 1624; const ctx = canvas.getContext('2d');
            if (isBump) { ctx.fillStyle = '#000000'; ctx.fillRect(0, 0, canvas.width, canvas.height); } else { drawBrushedMetalMain(ctx, canvas.width, canvas.height); }
            ctx.fillStyle = isBump ? '#888888' : GOLD; ctx.fillRect(40, 0, 12, canvas.height); ctx.fillRect(60, 0, 2, canvas.height);
            ctx.save(); ctx.translate(140, 1500); ctx.rotate(-Math.PI / 2);
            ctx.fillStyle = isBump ? '#ffffff' : 'rgba(212,175,55,0.15)'; ctx.font = '900 130px "Playfair Display", serif'; ctx.fillText('EXPEDIENT', 0, 0); ctx.restore();
            drawRealisticSmartChip(ctx, 160, 160, 120, 100, 15, isBump);
            if (isBump) { ctx.fillStyle = '#ffffff'; ctx.beginPath(); ctx.arc(820, 200, 70, 0, Math.PI*2); ctx.fill(); ctx.fillStyle = '#000000'; ctx.beginPath(); ctx.arc(820, 200, 65, 0, Math.PI*2); ctx.fill(); ctx.fillStyle = '#ffffff';
            } else { const hGrad = ctx.createLinearGradient(700, 100, 900, 300); hGrad.addColorStop(0, '#d4af37'); hGrad.addColorStop(0.5, '#fff'); hGrad.addColorStop(1, '#d4af37'); ctx.fillStyle = hGrad; ctx.beginPath(); ctx.arc(820, 200, 70, 0, Math.PI*2); ctx.fill(); ctx.fillStyle = DARK_BG; ctx.beginPath(); ctx.arc(820, 200, 65, 0, Math.PI*2); ctx.fill(); ctx.fillStyle = GOLD; }
            ctx.font = 'bold 50px "Playfair Display", serif'; ctx.textAlign = 'center'; ctx.fillText('VVIP', 820, 215); ctx.textAlign = 'left';
            const photoX = 160; const photoY = 400; const photoW = 760; const photoH = 650;
            ctx.strokeStyle = isBump ? '#888888' : 'rgba(212,175,55,0.5)'; ctx.lineWidth = 2; ctx.strokeRect(photoX, photoY, photoW, photoH);
            if (!isBump) { const glass = ctx.createLinearGradient(photoX, photoY, photoX+photoW, photoY+photoH); glass.addColorStop(0, 'rgba(255,255,255,0.05)'); glass.addColorStop(1, 'rgba(0,0,0,0.5)'); ctx.fillStyle = glass; ctx.fillRect(photoX, photoY, photoW, photoH); }
            ctx.strokeStyle = isBump ? '#ffffff' : GOLD; ctx.lineWidth = 4; const size = 30; ctx.beginPath(); ctx.moveTo(photoX, photoY+size); ctx.lineTo(photoX, photoY); ctx.lineTo(photoX+size, photoY); ctx.stroke(); ctx.beginPath(); ctx.moveTo(photoX+photoW-size, photoY); ctx.lineTo(photoX+photoW, photoY); ctx.lineTo(photoX+photoW, photoY+size); ctx.stroke(); 
            if (isBump) { ctx.shadowColor = '#ffffff'; ctx.shadowBlur = 1; }
            ctx.fillStyle = isBump ? '#ffffff' : '#ffffff'; ctx.font = 'bold 65px "Playfair Display", serif'; ctx.fillText(expedientData.nama.toUpperCase(), 160, 1180, 760);
            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.font = '600 30px "Inter", sans-serif'; ctx.letterSpacing = '5px'; ctx.fillText(expedientData.jabatan.toUpperCase(), 160, 1240, 760);
            if (isBump) ctx.shadowBlur = 0; 
            ctx.fillStyle = isBump ? '#888888' : 'rgba(212,175,55,0.3)'; ctx.fillRect(160, 1300, 760, 2);
            if (isBump) { ctx.shadowColor = '#ffffff'; ctx.shadowBlur = 1; } 
            ctx.fillStyle = isBump ? '#ffffff' : '#8b9ba8'; ctx.font = '400 28px monospace'; ctx.letterSpacing = '2px'; ctx.fillText('ID: ' + expedientData.nomor_id, 160, 1380); ctx.fillText(expedientData.exp, 160, 1430);
            if (isBump) ctx.shadowBlur = 0; 
            
            return kalibrasiUV(new THREE.CanvasTexture(canvas), isBump, false); // isKTA = false
        }

        function createBackTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 1624; const ctx = canvas.getContext('2d');
            if (isBump) { ctx.fillStyle = '#000000'; ctx.fillRect(0, 0, canvas.width, canvas.height); } else { drawBrushedMetalMain(ctx, canvas.width, canvas.height); }
            ctx.fillStyle = isBump ? '#111111' : '#000000'; ctx.fillRect(0, 150, 1024, 250); ctx.strokeStyle = isBump ? '#444444' : '#222'; ctx.lineWidth = 5; ctx.strokeRect(0, 150, 1024, 250);
            if (isBump) { ctx.shadowColor = '#ffffff'; ctx.shadowBlur = 1; } 
            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.textAlign = 'center'; ctx.font = 'bold 45px "Playfair Display", serif'; ctx.fillText('CATATAN RESMI', 512, 550);
            if (isBump) ctx.shadowBlur = 0;
            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.fillRect(400, 580, 224, 2);
            ctx.fillStyle = isBump ? '#aaaaaa' : '#8b9ba8'; ctx.font = '300 28px "Inter", sans-serif';
            const lines = ["Properti VVIP Eksklusif Expedient Generation.", "Kartu ini menyimpan data terenkripsi untuk", "akses tanpa batas ke dalam ekosistem The Vault.", "Penyalahgunaan akan dikenakan sanksi dewan."];
            lines.forEach((line, i) => ctx.fillText(line, 512, 680 + (i * 45)));
            ctx.strokeStyle = isBump ? '#ffffff' : GOLD; ctx.lineWidth = 8; ctx.strokeRect(342, 1030, 340, 340); ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.fillRect(320, 1010, 40, 10); ctx.fillRect(320, 1010, 10, 40); 
            ctx.fillStyle = isBump ? '#aaaaaa' : '#444'; ctx.font = '400 20px monospace'; ctx.fillText('SCAN UNTUK VERIFIKASI', 512, 1420); 
            
            return kalibrasiUV(new THREE.CanvasTexture(canvas), isBump, false); // isKTA = false
        }

        function createKTAFrontTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 640; const ctx = canvas.getContext('2d');
            ctx.fillStyle = isBump ? '#000000' : '#050505'; ctx.fillRect(0,0,1024,640); ctx.strokeStyle = isBump ? '#444444' : '#151515'; ctx.lineWidth = 4;
            for(let i=-200; i<1200; i+=60) { ctx.beginPath(); ctx.moveTo(i, 0); ctx.lineTo(i+400, 640); ctx.stroke(); }
            drawRealisticSmartChip(ctx, 100, 50, 100, 80, 10, isBump);
            if (isBump) { ctx.shadowColor = '#ffffff'; ctx.shadowBlur = 1; }
            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.font = 'bold 36px "Playfair Display", serif'; ctx.letterSpacing = '10px'; ctx.fillText('EXPEDIENT', 100, 180);
            if (isBump) ctx.shadowBlur = 0;
            ctx.fillStyle = isBump ? '#aaaaaa' : '#666'; ctx.font = '22px monospace'; ctx.letterSpacing = '5px'; ctx.fillText('VVIP ACCESS PLATINUM', 100, 230);
            if (!isBump) { ctx.fillStyle = 'rgba(212, 175, 55, 0.05)'; ctx.beginPath(); ctx.arc(800, 320, 250, 0, Math.PI*2); ctx.fill(); ctx.beginPath(); ctx.arc(800, 320, 245, 0, Math.PI*2); ctx.stroke();
            } else { ctx.fillStyle = '#222'; ctx.beginPath(); ctx.arc(800, 320, 250, 0, Math.PI*2); ctx.fill(); ctx.strokeStyle = '#555'; ctx.beginPath(); ctx.arc(800, 320, 245, 0, Math.PI*2); ctx.stroke(); }
            if (isBump) { ctx.shadowColor = '#ffffff'; ctx.shadowBlur = 1; }
            ctx.fillStyle = isBump ? '#ffffff' : '#ffffff'; ctx.font = 'bold 45px "Inter", sans-serif'; ctx.letterSpacing = '3px'; ctx.fillText(expedientData.nama.toUpperCase(), 100, 530, 435);
            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.font = '30px monospace'; ctx.fillText(expedientData.nomor_id, 100, 580, 435); 
            if (isBump) ctx.shadowBlur = 0; 
            
            return kalibrasiUV(new THREE.CanvasTexture(canvas), isBump, true); // isKTA = true
        }

        function createKTABackTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 640; const ctx = canvas.getContext('2d');
            if (isBump) { ctx.fillStyle = '#000000'; ctx.fillRect(0,0,1024,640); } else { drawBrushedMetalMain(ctx, 1024, 640); }
            ctx.fillStyle = isBump ? '#111111' : '#000000'; ctx.fillRect(0, 100, 1024, 120);
            if (isBump) { ctx.shadowColor = '#ffffff'; ctx.shadowBlur = 1; }
            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.font = 'bold 30px "Playfair Display", serif'; ctx.textAlign = 'left'; ctx.fillText('OTORISASI KARTU', 80, 320);
            if (isBump) ctx.shadowBlur = 0;
            ctx.fillStyle = isBump ? '#aaaaaa' : '#666'; ctx.font = '22px "Inter", sans-serif'; ctx.fillText('If found, return immediately to the Expedient Council.', 80, 380); ctx.fillText('Unauthorized use will be prosecuted.', 80, 420);
            
            return kalibrasiUV(new THREE.CanvasTexture(canvas), isBump, true); // isKTA = true
        }

        const texFront = createFrontTexture(false); const bumpFront = createFrontTexture(true); 
        const texBack = createBackTexture(false); const bumpBack = createBackTexture(true);   
        const kFrontTex = createKTAFrontTexture(false); const kFrontBump = createKTAFrontTexture(true);
        const kBackTex = createKTABackTexture(false);   const kBackBump = createKTABackTexture(true);

        const cardMaterialProps = { roughness: 0.15, metalness: 0.6, clearcoat: 1.0, clearcoatRoughness: 0.1, bumpScale: 0.015 };
        const goldEdgeMaterial = new THREE.MeshStandardMaterial({ color: PURE_GOLD, metalness: 1.0, roughness: 0.15 });
        
        const materials = [goldEdgeMaterial, goldEdgeMaterial, goldEdgeMaterial, goldEdgeMaterial, 
            new THREE.MeshPhysicalMaterial({ map: texFront, bumpMap: bumpFront, ...cardMaterialProps }), new THREE.MeshPhysicalMaterial({ map: texBack, bumpMap: bumpBack, ...cardMaterialProps })];

        const ktaMatArray = [goldEdgeMaterial, goldEdgeMaterial, goldEdgeMaterial, goldEdgeMaterial, 
            new THREE.MeshPhysicalMaterial({ map: kFrontTex, bumpMap: kFrontBump, ...cardMaterialProps, roughness: 0.1, metalness: 0.6 }), new THREE.MeshPhysicalMaterial({ map: kBackTex, bumpMap: kBackBump, ...cardMaterialProps, roughness: 0.2, metalness: 0.8 })];

        // ==========================================
        // 2. OBJEK 3D (KARTU & TALI ELASTIS)
        // ==========================================
        const cardWidth = 5.4; const cardHeight = 8.6; const cardDepth = 0.12; 
        
        const idGroup = new THREE.Group();
        const idMesh = new THREE.Mesh(new RoundedBoxGeometry(cardWidth, cardHeight, cardDepth, 24, 0.3), materials);
        idMesh.position.set(0, -4.4, 0); 
        const metalClip = new THREE.Mesh(new THREE.CylinderGeometry(0.3, 0.3, 0.4, 32).rotateZ(Math.PI/2), goldEdgeMaterial);
        metalClip.position.set(0, 0, 0); 
        idGroup.add(idMesh); idGroup.add(metalClip);
        scene.add(idGroup);

        const ktaGroup = new THREE.Group();
        const ktaMesh = new THREE.Mesh(new RoundedBoxGeometry(8.6, 5.4, 0.08, 16, 0.3), ktaMatArray);
        ktaGroup.add(ktaMesh); 
        ktaGroup.visible = false;
        scene.add(ktaGroup);

        // --- SISTEM TALI (LANYARD) ---
        const lanyardMat = new THREE.MeshStandardMaterial({ color: 0x1a1a1a, roughness: 0.8 });
        let lanyardMesh = null;
        const lanyardAnchor = new THREE.Vector3(0, 15, -3); 

        function updateLanyard() {
            if(!idGroup.visible) { 
                if(lanyardMesh) lanyardMesh.visible = false; 
                return; 
            }
            
            const clipPos = new THREE.Vector3(0, 0, 0);
            idGroup.localToWorld(clipPos);

            let controlPoint = new THREE.Vector3();
            controlPoint.lerpVectors(lanyardAnchor, clipPos, 0.5);
            controlPoint.y -= 2 + (Math.abs(clipPos.y) * 0.2) + (Math.abs(clipPos.x) * 0.2); 

            const curve = new THREE.QuadraticBezierCurve3(lanyardAnchor, controlPoint, clipPos);
            const tubeGeo = new THREE.TubeGeometry(curve, 30, 0.08, 8, false);

            if (lanyardMesh) {
                lanyardMesh.geometry.dispose(); lanyardMesh.geometry = tubeGeo; lanyardMesh.visible = true;
            } else {
                lanyardMesh = new THREE.Mesh(tubeGeo, lanyardMat); scene.add(lanyardMesh);
            }
        }

        // ==========================================
        // 3. LOAD FOTO DATABASE & QR CODE
        // ==========================================
        const loadProfile = new Promise((resolve) => {
            if (expedientData.foto_url === '') return resolve(null);
            const img = new Image(); img.crossOrigin = "Anonymous"; img.src = expedientData.foto_url;
            img.onload = () => resolve(img); img.onerror = () => resolve(null);
        });

        const loadQR = new Promise((resolve) => {
            const qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode(base_url('scan/') . esc($user['id'])) ?>";
            const qrImg = new Image(); qrImg.crossOrigin = "Anonymous"; qrImg.src = qrImageUrl;
            qrImg.onload = () => resolve(qrImg); qrImg.onerror = () => resolve(null);
        });

        Promise.all([loadProfile, loadQR]).then(([profileImg, qrImg]) => {
            if (profileImg) {
                const ctxF = materials[4].map.image.getContext('2d');
                ctxF.save(); ctxF.beginPath(); ctxF.rect(160, 400, 760, 650); ctxF.clip();
                const sc = Math.max(760/profileImg.width, 650/profileImg.height); ctxF.drawImage(profileImg, 160 + (760 - profileImg.width*sc)/2, 400 + (650 - profileImg.height*sc)/2, profileImg.width*sc, profileImg.height*sc);
                ctxF.restore(); materials[4].map.needsUpdate = true;
                
                const ctxK = ktaMatArray[4].map.image.getContext('2d');
                ctxK.save(); ctxK.beginPath(); ctxK.arc(800, 320, 245, 0, Math.PI*2); ctxK.clip();
                const sck = Math.max(500/profileImg.width, 500/profileImg.height); ctxK.drawImage(profileImg, 800 - (profileImg.width*sck)/2, 320 - (profileImg.height*sck)/2, profileImg.width*sck, profileImg.height*sck);
                ctxK.restore(); ktaMatArray[4].map.needsUpdate = true;
            }

            if (qrImg) {
                // Gambar QR Code persis di kotak belakang ID Card
                const ctxB = materials[5].map.image.getContext('2d');
                ctxB.drawImage(qrImg, 362, 1050, 300, 300); 
                materials[5].map.needsUpdate = true;
                
                // Gambar QR Code di tengah posisi KTA Back
                const ctxKB = ktaMatArray[5].map.image.getContext('2d');
                ctxKB.drawImage(qrImg, 750, 220, 200, 200); 
                ktaMatArray[5].map.needsUpdate = true;
            }

            document.getElementById('preloader').style.opacity = '0';
            setTimeout(() => document.getElementById('preloader').style.display = 'none', 800);
        });


        // ==========================================
        // 4. KINEMATICS & SPRING PHYSICS (X & Y AXIS)
        // ==========================================
        let isDragging = false;
        let previousMouse = { x: 0, y: 0 };
        
        let targetRotX = 0; let targetRotY = 0;
        
        let defaultY = 3.0; 
        let currentY = defaultY; let targetY = defaultY; let velocityY = 0;
        let currentX = 0; let targetX = 0; let velocityX = 0; 
        
        const springK = 0.15; 
        const damping = 0.8;  
        
        let gyroRotX = 0; let gyroRotY = 0;

        container.addEventListener('pointerdown', (e) => {
            isDragging = true;
            previousMouse = { x: e.clientX, y: e.clientY };
            document.getElementById('tutorial').style.opacity = '0';
        });

        container.addEventListener('pointermove', (e) => {
            if (!isDragging) return;
            const deltaX = e.clientX - previousMouse.x;
            const deltaY = e.clientY - previousMouse.y;
            
            targetRotY += deltaX * 0.01;
            targetRotX += deltaY * 0.01;

            targetY -= deltaY * 0.04;
            targetX += deltaX * 0.04;

            if (targetY < -10) targetY = -10; 
            if (targetY > 10) targetY = 10;
            if (targetX < -8) targetX = -8;
            if (targetX > 8) targetX = 8;

            previousMouse = { x: e.clientX, y: e.clientY };
        });

        container.addEventListener('pointerup', () => {
            isDragging = false;
            targetRotX = 0; 
            targetY = defaultY; 
            targetX = 0;        
        });

        window.addEventListener("deviceorientation", (e) => {
            if (!e.gamma || !e.beta) return;
            gyroRotY = THREE.MathUtils.clamp(e.gamma * 0.02, -0.5, 0.5); 
            gyroRotX = THREE.MathUtils.clamp((e.beta - 45) * 0.02, -0.5, 0.5); 
        });

        document.getElementById('btnID').addEventListener('click', function() {
            this.classList.add('active'); document.getElementById('btnKTA').classList.remove('active');
            idGroup.visible = true; ktaGroup.visible = false;
            targetRotY = 0; 
            defaultY = 3.0; targetY = defaultY; targetX = 0;
        });
        document.getElementById('btnKTA').addEventListener('click', function() {
            this.classList.add('active'); document.getElementById('btnID').classList.remove('active');
            idGroup.visible = false; ktaGroup.visible = true;
            targetRotY = 0; 
            defaultY = 0.0; targetY = defaultY; targetX = 0;
        });

        // ==========================================
        // 5. RENDER LOOP
        // ==========================================
        function animate() {
            requestAnimationFrame(animate);
            
            const forceY = (targetY - currentY) * springK;
            velocityY += forceY; velocityY *= damping; currentY += velocityY;

            const forceX = (targetX - currentX) * springK;
            velocityX += forceX; velocityX *= damping; currentX += velocityX;

            if (idGroup.visible) {
                idGroup.position.y = currentY;
                idGroup.position.x = currentX; 
                idMesh.rotation.y += ((targetRotY + gyroRotY) - idMesh.rotation.y) * 0.1;
                idMesh.rotation.x += ((targetRotX + gyroRotX) - idMesh.rotation.x) * 0.1;
            } else if (ktaGroup.visible) {
                ktaGroup.position.y = currentY;
                ktaGroup.position.x = currentX; 
                ktaMesh.rotation.y += ((targetRotY + gyroRotY) - ktaMesh.rotation.y) * 0.1;
                ktaMesh.rotation.x += ((targetRotX + gyroRotX) - ktaMesh.rotation.x) * 0.1;
            }
            
            updateLanyard();

            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>
</body>
</html>