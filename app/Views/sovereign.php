<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Sovereign ID - <?= esc($user['nama_panggilan']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* HANYA container canvas yang transparan, biarkan body mengikuti template bawaan kapten */
    .main-wrapper { overflow: hidden !important; }
    
    #canvas-container {
        width: 100%; height: 100%; display: block;
        position: absolute; top: 0; left: 0; z-index: 10;
        background: transparent !important;
        pointer-events: auto;
    }

    .ux-overlay {
        position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);
        display: flex; flex-direction: column; align-items: center; gap: 12px;
        z-index: 11; pointer-events: none; transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .ux-overlay.hidden { opacity: 0; transform: translate(-50%, 20px); pointer-events: none; }
    
    .ux-icon {
        color: #d4af37; font-size: 24px;
        animation: dragSimulate 2.5s infinite cubic-bezier(0.4, 0, 0.2, 1);
        filter: drop-shadow(0 0 10px rgba(212,175,55,0.6));
    }
    .ux-text {
        color: #d4af37; font-size: 11px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 4px; text-shadow: 0 0 10px rgba(0,0,0,0.8); text-align: center;
    }

    .kta-tooltip {
        position: absolute; top: 65%; left: 15%; transform: translateY(-50%);
        color: rgba(255,255,255,0.7); font-size: 10px; font-weight: 600;
        letter-spacing: 3px; text-transform: uppercase; pointer-events: none;
        display: flex; flex-direction: column; align-items: center; gap: 8px; z-index: 11;
        opacity: 0.8; animation: pulseFade 3s infinite;
    }
    .kta-tooltip i { font-size: 16px; color: #d4af37; }

    @keyframes dragSimulate {
        0% { transform: translateY(-10px) scale(0.9); opacity: 0; }
        20% { transform: translateY(0px) scale(1); opacity: 1; }
        70% { transform: translateY(30px) scale(1); opacity: 1; }
        100% { transform: translateY(40px) scale(0.9); opacity: 0; }
    }
    @keyframes pulseFade { 0%, 100% { opacity: 0.3; transform: translateY(-50%) scale(0.95); } 50% { opacity: 1; transform: translateY(-50%) scale(1.05); } }

    #kta-focus-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        z-index: 8; opacity: 0; pointer-events: none; transition: opacity 0.5s ease;
    }
    body.kta-active #kta-focus-overlay, body.main-active #kta-focus-overlay { opacity: 1; pointer-events: auto; }
    
    .kta-close-btn {
        position: absolute; top: 30px; right: 30px; color: #fff; font-size: 30px;
        cursor: pointer; opacity: 0; transition: opacity 0.5s ease; z-index: 12; pointer-events: none;
    }
    body.kta-active .kta-close-btn, body.main-active .kta-close-btn { opacity: 1; pointer-events: auto; }
    .kta-close-btn:hover { color: #d4af37; transform: scale(1.1); }

    .kta-helper-text {
        position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
        color: #d4af37; font-size: 11px; font-weight: 500; letter-spacing: 4px; text-transform: uppercase;
        opacity: 0; transition: opacity 0.5s ease; z-index: 12; pointer-events: none;
    }
    body.kta-active .kta-helper-text, body.main-active .kta-helper-text { opacity: 1; animation: blinkHelper 2s infinite; }
    @keyframes blinkHelper { 0%, 100% { opacity: 0.4; } 50% { opacity: 1; } }

    body.is-grabbing .cursor-ring {
        width: 30px !important; height: 30px !important; 
        background: rgba(212,175,55,0.3) !important; 
        border-color: #d4af37 !important;
    }
    body.is-grabbing .cursor-dot { opacity: 0; }
    
    @media (max-width: 768px) {
        .kta-tooltip { top: 75% !important; left: 10% !important; }
        .ux-overlay { bottom: 80px; }
    }

    #fontLoader { position: fixed; inset: 0; z-index: 99999; background: var(--bg-main); display: flex; justify-content: center; align-items: center; color: #d4af37; font-family: 'Courier New', monospace; letter-spacing: 3px; font-size: 0.8rem; transition: opacity 0.5s; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="fontLoader">MENYIAPKAN IDENTITAS...</div>

<div class="ux-overlay" id="uxOverlay">
    <i class="fa-solid fa-hand-pointer ux-icon"></i>
    <span class="ux-text">Tarik ID Card <br><span style="font-size:9px; opacity:0.7">Klik 2x untuk Zoom | KTA melayang: Klik 1x</span></span>
</div>

<div class="kta-tooltip" id="ktaTooltip">
    <i class="fa-solid fa-expand"></i>
    <span>Akses KTA</span>
</div>

<div id="kta-focus-overlay"></div>
<div class="kta-helper-text" id="helperText">Geser untuk memutar Kartu</div>
<i class="fa-solid fa-xmark kta-close-btn" id="btnCloseKta"></i>

<div id="canvas-container"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="importmap">
    {
        "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
            "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
        }
    }
</script>

<script type="module">
    import * as THREE from 'three';
    import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

    document.fonts.ready.then(() => {
        document.getElementById('fontLoader').style.opacity = '0';
        setTimeout(() => document.getElementById('fontLoader').style.display = 'none', 500);
        init3D();
    });

    function init3D() {
        const expedientData = {
            nama: "<?= esc($user['nama_lengkap']) ?>",
            jabatan: "SOVEREIGN ENTITY",
            nomor_id: "EXP-<?= sprintf('%03d', $user['id']) ?>",
            exp: "VALID THRU FOREVER",
            foto_url: "<?= $foto_profil ?>" // Sudah diperbaiki foldernya via controller
        };

        const GOLD = '#d4af37';
        const DARK_BG = '#050505'; 

        const container = document.getElementById('canvas-container');
        const scene = new THREE.Scene();
        
        const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 100);
        camera.position.set(0, 0, 28); 

        // Renderer Transparan Murni 
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setClearColor( 0x000000, 0 ); 
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.0; 
        container.appendChild(renderer.domElement);

        const pmremGenerator = new THREE.PMREMGenerator(renderer);
        scene.environment = pmremGenerator.fromScene(new RoomEnvironment(), 0.04).texture;

        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const audioCtx = new AudioContext();
        function playSnapSound() {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(800, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(150, audioCtx.currentTime + 0.1);
            gainNode.gain.setValueAtTime(0.15, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
            osc.connect(gainNode); gainNode.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + 0.1);
        }

        // Pencahayaan yang tidak akan membuat tulisan memudar
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5); 
        scene.add(ambientLight);

        const mainLight = new THREE.SpotLight(0xffffff, 60); 
        mainLight.position.set(10, 20, 30);
        mainLight.angle = Math.PI / 4;
        mainLight.penumbra = 0.5;
        mainLight.castShadow = true;
        scene.add(mainLight);

        const rimLight = new THREE.PointLight(GOLD, 30, 20); 
        rimLight.position.set(-8, -5, 5);
        scene.add(rimLight);

        const cameraLight = new THREE.PointLight(0xffffff, 20, 50);
        camera.add(cameraLight);
        scene.add(camera); 

        // Perbaikan: Kurangi bintik putih agar mode malam benar-benar gelap
        function drawBrushedMetalMain(ctx, w, h) {
            ctx.fillStyle = DARK_BG; ctx.fillRect(0, 0, w, h);
            ctx.globalAlpha = 0.03;
            for(let i=0; i<w; i+=2) {
                ctx.fillStyle = Math.random() > 0.5 ? '#222222' : '#000000'; // Ganti white jadi dark grey
                ctx.fillRect(i, 0, 1, h);
            }
            ctx.globalAlpha = 1.0;
            const grd = ctx.createRadialGradient(w/2, h/2, 200, w/2, h/2, w);
            grd.addColorStop(0, 'transparent'); grd.addColorStop(1, 'rgba(0,0,0,0.9)');
            ctx.fillStyle = grd; ctx.fillRect(0, 0, w, h);
        }

        function createFrontTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 1624;
            const ctx = canvas.getContext('2d');
            
            if (isBump) { ctx.fillStyle = '#000000'; ctx.fillRect(0, 0, canvas.width, canvas.height); } 
            else { drawBrushedMetalMain(ctx, canvas.width, canvas.height); }

            ctx.fillStyle = isBump ? '#888888' : GOLD;
            ctx.fillRect(40, 0, 12, canvas.height); ctx.fillRect(60, 0, 2, canvas.height);

            ctx.save(); ctx.translate(140, 1500); ctx.rotate(-Math.PI / 2);
            ctx.fillStyle = isBump ? '#ffffff' : 'rgba(212,175,55,0.15)'; 
            ctx.font = '900 130px "Playfair Display", serif'; ctx.letterSpacing = '20px';
            ctx.fillText('EXPEDIENT', 0, 0); ctx.restore();

            ctx.fillStyle = isBump ? '#eeeeee' : '#facc15'; ctx.beginPath(); ctx.roundRect(160, 160, 120, 100, 15); ctx.fill();
            ctx.strokeStyle = isBump ? '#ffffff' : '#b45309'; ctx.lineWidth = 3; ctx.beginPath(); ctx.roundRect(160, 160, 120, 100, 15); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(160, 210); ctx.lineTo(280, 210); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(220, 160); ctx.lineTo(220, 260); ctx.stroke();
            ctx.beginPath(); ctx.arc(220, 210, 20, 0, Math.PI*2); ctx.stroke();

            if (isBump) {
                ctx.fillStyle = '#ffffff'; ctx.beginPath(); ctx.arc(820, 200, 70, 0, Math.PI*2); ctx.fill();
                ctx.fillStyle = '#000000'; ctx.beginPath(); ctx.arc(820, 200, 65, 0, Math.PI*2); ctx.fill();
                ctx.fillStyle = '#ffffff';
            } else {
                const hGrad = ctx.createLinearGradient(700, 100, 900, 300);
                hGrad.addColorStop(0, '#d4af37'); hGrad.addColorStop(0.5, '#fff'); hGrad.addColorStop(1, '#d4af37');
                ctx.fillStyle = hGrad; ctx.beginPath(); ctx.arc(820, 200, 70, 0, Math.PI*2); ctx.fill();
                ctx.fillStyle = DARK_BG; ctx.beginPath(); ctx.arc(820, 200, 65, 0, Math.PI*2); ctx.fill();
                ctx.fillStyle = GOLD;
            }
            ctx.font = 'bold 50px "Playfair Display", serif'; ctx.textAlign = 'center'; ctx.fillText('VVIP', 820, 215); ctx.textAlign = 'left';

            const photoX = 160; const photoY = 400; const photoW = 760; const photoH = 650;
            ctx.strokeStyle = isBump ? '#888888' : 'rgba(212,175,55,0.5)'; ctx.lineWidth = 2; ctx.strokeRect(photoX, photoY, photoW, photoH);
            
            if (!isBump) {
                const glass = ctx.createLinearGradient(photoX, photoY, photoX+photoW, photoY+photoH);
                glass.addColorStop(0, 'rgba(255,255,255,0.05)'); glass.addColorStop(1, 'rgba(0,0,0,0.5)');
                ctx.fillStyle = glass; ctx.fillRect(photoX, photoY, photoW, photoH);
            }
            
            ctx.strokeStyle = isBump ? '#ffffff' : GOLD; ctx.lineWidth = 4; const size = 30;
            ctx.beginPath(); ctx.moveTo(photoX, photoY+size); ctx.lineTo(photoX, photoY); ctx.lineTo(photoX+size, photoY); ctx.stroke(); 
            ctx.beginPath(); ctx.moveTo(photoX+photoW-size, photoY); ctx.lineTo(photoX+photoW, photoY); ctx.lineTo(photoX+photoW, photoY+size); ctx.stroke(); 

            // Siluet Default
            if (!isBump) {
                ctx.fillStyle = '#1a2228';
                ctx.beginPath(); ctx.arc(540, 650, 120, 0, Math.PI*2); ctx.fill();
                ctx.beginPath(); ctx.arc(540, 1050, 280, Math.PI, 0); ctx.fill();
            }

            ctx.fillStyle = isBump ? '#ffffff' : '#ffffff'; ctx.font = 'bold 65px "Playfair Display", serif';
            ctx.fillText(expedientData.nama.toUpperCase(), 160, 1180);
            
            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.font = '600 30px "Inter", sans-serif'; ctx.letterSpacing = '5px';
            ctx.fillText(expedientData.jabatan.toUpperCase(), 160, 1240);

            ctx.fillStyle = isBump ? '#888888' : 'rgba(212,175,55,0.3)'; ctx.fillRect(160, 1300, 760, 2);

            ctx.fillStyle = isBump ? '#ffffff' : '#8b9ba8'; ctx.font = '400 28px monospace'; ctx.letterSpacing = '2px';
            ctx.fillText('ID: ' + expedientData.nomor_id, 160, 1380);
            ctx.fillText(expedientData.exp, 160, 1430);

            if (!isBump) {
                ctx.fillStyle = '#ffffff';
                for(let i=0; i<30; i++) {
                    const bw = Math.random() * 8 + 2; ctx.fillRect(720 + (i*8), 1350, bw, 80);
                }
            }

            const texture = new THREE.CanvasTexture(canvas);
            if (!isBump) texture.colorSpace = THREE.SRGBColorSpace; 
            texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
            return texture;
        }

        function createBackTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 1624;
            const ctx = canvas.getContext('2d');
            
            if (isBump) { ctx.fillStyle = '#000000'; ctx.fillRect(0, 0, canvas.width, canvas.height); } 
            else { drawBrushedMetalMain(ctx, canvas.width, canvas.height); }

            ctx.fillStyle = isBump ? '#111111' : '#000000'; ctx.fillRect(0, 150, 1024, 250);
            ctx.strokeStyle = isBump ? '#444444' : '#222'; ctx.lineWidth = 5; ctx.strokeRect(0, 150, 1024, 250);

            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.textAlign = 'center'; ctx.font = 'bold 45px "Playfair Display", serif';
            ctx.fillText('THE REGISTRY DIRECTIVE', 512, 550);

            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.fillRect(400, 580, 224, 2);

            ctx.fillStyle = isBump ? '#aaaaaa' : '#8b9ba8'; ctx.font = '300 28px "Inter", sans-serif';
            const lines = [
                "Properti VVIP Eksklusif Expedient Generation.",
                "Kartu ini menyimpan data terenkripsi untuk",
                "akses tanpa batas ke dalam ekosistem The Vault.",
                "Penyalahgunaan akan dikenakan sanksi dewan."
            ];
            lines.forEach((line, i) => ctx.fillText(line, 512, 680 + (i * 45)));

            ctx.strokeStyle = isBump ? '#ffffff' : GOLD; ctx.lineWidth = 8; ctx.strokeRect(342, 1030, 340, 340);
            ctx.fillStyle = isBump ? '#ffffff' : GOLD; ctx.fillRect(320, 1010, 40, 10); ctx.fillRect(320, 1010, 10, 40); 

            ctx.fillStyle = isBump ? '#aaaaaa' : '#444'; ctx.font = '400 20px monospace';
            ctx.fillText('SCAN FOR OMNIPRESENCE VERIFICATION', 512, 1420);

            const texture = new THREE.CanvasTexture(canvas);
            if (!isBump) texture.colorSpace = THREE.SRGBColorSpace;
            return texture;
        }

        function createKTAFrontTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 640;
            const ctx = canvas.getContext('2d');
            
            ctx.fillStyle = isBump ? '#000000' : '#050505'; ctx.fillRect(0,0,1024,640);
            ctx.strokeStyle = isBump ? '#444444' : '#151515'; ctx.lineWidth = 4;
            for(let i=-200; i<1200; i+=60) { ctx.beginPath(); ctx.moveTo(i, 0); ctx.lineTo(i+400, 640); ctx.stroke(); }

            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.beginPath(); ctx.roundRect(100, 300, 100, 80, 10); ctx.fill();
            ctx.strokeStyle = isBump ? '#ffffff' : '#b45309'; ctx.lineWidth = 2; ctx.beginPath(); ctx.roundRect(100, 300, 100, 80, 10); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(100, 340); ctx.lineTo(200, 340); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(150, 300); ctx.lineTo(150, 380); ctx.stroke();

            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.font = 'bold 36px "Playfair Display", serif'; ctx.letterSpacing = '10px';
            ctx.fillText('EXPEDIENT', 100, 150);
            ctx.fillStyle = isBump ? '#aaaaaa' : '#666'; ctx.font = '22px monospace'; ctx.letterSpacing = '5px';
            ctx.fillText('VVIP ACCESS PLATINUM', 100, 200);

            // Siluet KTA Default
            if (!isBump) {
                ctx.fillStyle = 'rgba(212, 175, 55, 0.05)'; ctx.beginPath(); ctx.arc(800, 320, 250, 0, Math.PI*2); ctx.fill();
                ctx.beginPath(); ctx.arc(800, 320, 245, 0, Math.PI*2); ctx.stroke();
            } else {
                ctx.fillStyle = '#222'; ctx.beginPath(); ctx.arc(800, 320, 250, 0, Math.PI*2); ctx.fill();
                ctx.strokeStyle = '#555'; ctx.beginPath(); ctx.arc(800, 320, 245, 0, Math.PI*2); ctx.stroke();
            }

            ctx.fillStyle = isBump ? '#ffffff' : '#ffffff'; ctx.font = 'bold 45px "Inter", sans-serif'; ctx.letterSpacing = '3px';
            ctx.fillText(expedientData.nama.toUpperCase(), 100, 500);
            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.font = '30px monospace';
            ctx.fillText(expedientData.nomor_id, 100, 550);

            const tex = new THREE.CanvasTexture(canvas);
            if (!isBump) tex.colorSpace = THREE.SRGBColorSpace; return tex;
        }

        function createKTABackTexture(isBump = false) {
            const canvas = document.createElement('canvas'); canvas.width = 1024; canvas.height = 640;
            const ctx = canvas.getContext('2d');
            
            if (isBump) { ctx.fillStyle = '#000000'; ctx.fillRect(0,0,1024,640); } 
            else { drawBrushedMetalMain(ctx, 1024, 640); }
            
            ctx.fillStyle = isBump ? '#111111' : '#000000'; ctx.fillRect(0, 100, 1024, 120);
            ctx.fillStyle = isBump ? '#ffffff' : '#d4af37'; ctx.font = 'bold 30px "Playfair Display", serif'; ctx.textAlign = 'left';
            ctx.fillText('THE VAULT AUTHORIZATION', 80, 320);

            ctx.fillStyle = isBump ? '#aaaaaa' : '#666'; ctx.font = '22px "Inter", sans-serif';
            ctx.fillText('If found, return immediately to the Expedient Council.', 80, 380);
            ctx.fillText('Unauthorized use will be prosecuted.', 80, 420);

            const tex = new THREE.CanvasTexture(canvas);
            if (!isBump) tex.colorSpace = THREE.SRGBColorSpace; return tex;
        }

        const texFront = createFrontTexture(false); const bumpFront = createFrontTexture(true); 
        const texBack = createBackTexture(false); const bumpBack = createBackTexture(true);   

        const cardMaterialProps = { roughness: 0.15, metalness: 0.3, clearcoat: 1.0, clearcoatRoughness: 0.1 };
        const edgeMaterial = new THREE.MeshPhysicalMaterial({ color: 0x111111, roughness: 0.2, metalness: 0.8 });
        
        const materials = [
            edgeMaterial, edgeMaterial, edgeMaterial, edgeMaterial,
            new THREE.MeshPhysicalMaterial({ map: texFront, bumpMap: bumpFront, bumpScale: 0.015, ...cardMaterialProps }),
            new THREE.MeshPhysicalMaterial({ map: texBack, bumpMap: bumpBack, bumpScale: 0.015, ...cardMaterialProps })
        ];

        const cardWidth = 5.4; const cardHeight = 8.6; const cardDepth = 0.12; 
        const idCard = new THREE.Mesh(new THREE.BoxGeometry(cardWidth, cardHeight, cardDepth), materials);
        idCard.castShadow = true; idCard.position.set(0, 15, 0); scene.add(idCard);

        const clipGeo = new THREE.CylinderGeometry(0.3, 0.3, 0.4, 32); clipGeo.rotateZ(Math.PI / 2);
        const clipMat = new THREE.MeshStandardMaterial({ color: 0xc5a059, metalness: 1.0, roughness: 0.2 });
        const metalClip = new THREE.Mesh(clipGeo, clipMat);
        metalClip.position.set(0, cardHeight / 2 + 0.1, 0); metalClip.castShadow = true;
        idCard.add(metalClip);

        const anchorPos = new THREE.Vector3(0, 17.5, 0);
        const stringLength = 17; 
        const restPos = new THREE.Vector3(0, anchorPos.y - stringLength, 0); 
        
        let lanyardMesh = null;
        const lanyardMat = new THREE.MeshStandardMaterial({ color: 0x111111, roughness: 0.9, metalness: 0.1 });

        function updateLanyardGeometry() {
            const clipGlobalPos = new THREE.Vector3(0, cardHeight/2 + 0.3, 0);
            idCard.localToWorld(clipGlobalPos);
            const dist = anchorPos.distanceTo(clipGlobalPos);
            const sag = Math.max(0, stringLength - dist) * 0.5; 
            
            const zOffset = isMainActive ? -3 : -1; 
            const control1 = new THREE.Vector3(anchorPos.x, anchorPos.y - (stringLength * 0.3) - sag, anchorPos.z + zOffset);
            const control2 = new THREE.Vector3(clipGlobalPos.x, clipGlobalPos.y + (stringLength * 0.3) + sag, clipGlobalPos.z + zOffset);
            
            const curve = new THREE.CubicBezierCurve3(anchorPos, control1, control2, clipGlobalPos);
            const tubeGeo = new THREE.TubeGeometry(curve, 40, 0.12, 8, false);

            if (lanyardMesh) {
                lanyardMesh.geometry.dispose(); 
                lanyardMesh.geometry = tubeGeo;
            } else {
                lanyardMesh = new THREE.Mesh(tubeGeo, lanyardMat);
                lanyardMesh.castShadow = true; 
                scene.add(lanyardMesh);
            }
        }

        const ktaWidth = 5.4; const ktaHeight = 3.4; const ktaDepth = 0.08; 
        const ktaGeo = new THREE.BoxGeometry(ktaWidth, ktaHeight, ktaDepth);
        
        const kFrontTex = createKTAFrontTexture(false); const kFrontBump = createKTAFrontTexture(true);
        const kBackTex = createKTABackTexture(false);   const kBackBump = createKTABackTexture(true);

        const ktaFrontMat = new THREE.MeshPhysicalMaterial({ map: kFrontTex, bumpMap: kFrontBump, bumpScale: 0.015, roughness: 0.1, metalness: 0.6, clearcoat: 1.0 });
        const ktaBackMat = new THREE.MeshPhysicalMaterial({ map: kBackTex, bumpMap: kBackBump, bumpScale: 0.015, roughness: 0.2, metalness: 0.8, clearcoat: 0.5 });
        const ktaEdgeMat = new THREE.MeshStandardMaterial({ color: 0xd4af37, metalness: 1.0, roughness: 0.2 }); 
        
        const ktaMatArray = [ktaEdgeMat, ktaEdgeMat, ktaEdgeMat, ktaEdgeMat, ktaFrontMat, ktaBackMat];
        const ktaMesh = new THREE.Mesh(ktaGeo, ktaMatArray);
        ktaMesh.castShadow = true;
        scene.add(ktaMesh);

        const ktaRestPos = new THREE.Vector3(-8, 0, -2); 
        ktaMesh.position.copy(ktaRestPos);

        // ==========================================
        // DYNAMIC ASSETS LOADER (QR & FOTO PROFIL)
        // ==========================================
        
        // 1. Load QR Code
        const qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode(base_url('profil/') . esc($user['id'])) ?>";
        const qrImg = new Image();
        qrImg.crossOrigin = "Anonymous";
        qrImg.src = qrImageUrl;
        qrImg.onload = () => {
            if(texBack && texBack.image) {
                const ctxBack = texBack.image.getContext('2d');
                ctxBack.drawImage(qrImg, 362, 1050, 300, 300);
                texBack.needsUpdate = true;
            }
            if(kBackTex && kBackTex.image) {
                const ctxKta = kBackTex.image.getContext('2d');
                ctxKta.drawImage(qrImg, 750, 320, 200, 200);
                kBackTex.needsUpdate = true;
            }
        };

        // 2. Load Foto Profil User
        if (expedientData.foto_url !== '') {
            const profileImg = new Image();
            profileImg.crossOrigin = "Anonymous";
            profileImg.src = expedientData.foto_url;
            profileImg.onload = () => {
                
                // LUKIS FOTO DI MAIN CARD
                if(texFront && texFront.image) {
                    const ctxFront = texFront.image.getContext('2d');
                    const photoX = 160; const photoY = 400; const photoW = 760; const photoH = 650;
                    
                    ctxFront.save();
                    ctxFront.beginPath();
                    ctxFront.rect(photoX, photoY, photoW, photoH);
                    ctxFront.clip();
                    
                    const scale = Math.max(photoW / profileImg.width, photoH / profileImg.height);
                    const drawW = profileImg.width * scale;
                    const drawH = profileImg.height * scale;
                    const drawX = photoX + (photoW - drawW) / 2;
                    const drawY = photoY + (photoH - drawH) / 2;

                    ctxFront.drawImage(profileImg, drawX, drawY, drawW, drawH);
                    
                    const glass = ctxFront.createLinearGradient(photoX, photoY, photoX+photoW, photoY+photoH);
                    glass.addColorStop(0, 'rgba(255,255,255,0.05)'); glass.addColorStop(1, 'rgba(0,0,0,0.5)');
                    ctxFront.fillStyle = glass;
                    ctxFront.fillRect(photoX, photoY, photoW, photoH);
                    
                    ctxFront.restore();
                    
                    ctxFront.strokeStyle = GOLD; ctxFront.lineWidth = 4; const size = 30;
                    ctxFront.beginPath(); ctxFront.moveTo(photoX, photoY+size); ctxFront.lineTo(photoX, photoY); ctxFront.lineTo(photoX+size, photoY); ctxFront.stroke(); 
                    ctxFront.beginPath(); ctxFront.moveTo(photoX+photoW-size, photoY); ctxFront.lineTo(photoX+photoW, photoY); ctxFront.lineTo(photoX+photoW, photoY+size); ctxFront.stroke();
                    
                    texFront.needsUpdate = true;
                }

                // LUKIS FOTO DI KTA
                if(kFrontTex && kFrontTex.image) {
                    const ctxKta = kFrontTex.image.getContext('2d');
                    
                    ctxKta.save();
                    ctxKta.beginPath();
                    ctxKta.arc(800, 320, 245, 0, Math.PI*2);
                    ctxKta.clip();
                    
                    const targetSize = 500; 
                    const scaleKta = Math.max(targetSize / profileImg.width, targetSize / profileImg.height);
                    const drawWKta = profileImg.width * scaleKta;
                    const drawHKta = profileImg.height * scaleKta;
                    const drawXKta = 800 - drawWKta / 2;
                    const drawYKta = 320 - drawHKta / 2;

                    ctxKta.drawImage(profileImg, drawXKta, drawYKta, drawWKta, drawHKta);
                    
                    ctxKta.lineWidth = 4;
                    ctxKta.strokeStyle = 'rgba(212, 175, 55, 0.5)';
                    ctxKta.beginPath();
                    ctxKta.arc(800, 320, 245, 0, Math.PI*2);
                    ctxKta.stroke();
                    
                    ctxKta.restore();
                    kFrontTex.needsUpdate = true;
                }
            };
        }


        const raycaster = new THREE.Raycaster();
        const mouse = new THREE.Vector2();
        
        let targetCameraX = 0; let targetCameraY = 0;
        let isKtaActive = false; const ktaViewPos = new THREE.Vector3(0, 0, 16); 
        let ktaTargetRotX = 0; let ktaTargetRotY = 0; let ktaDragDist = 0; 
        
        let isMainActive = false; const mainViewPos = new THREE.Vector3(0, 0, 16); 
        let mainTargetRotX = 0; let mainTargetRotY = 0;
        let lastClickTime = 0; 

        const btnCloseKta = document.getElementById('btnCloseKta');

        function toggleKTA() {
            isKtaActive = !isKtaActive;
            if(isKtaActive) {
                ktaTargetRotX = 0; ktaTargetRotY = 0; 
                document.body.classList.add('kta-active');
                const currentRot = new THREE.Euler().copy(ktaMesh.rotation);
                ktaMesh.rotation.set(0, currentRot.y, 0); 
                playSnapSound();
            } else {
                document.body.classList.remove('kta-active');
                playSnapSound();
            }
        }

        function toggleMainCard() {
            isMainActive = !isMainActive;
            if(isMainActive) {
                mainTargetRotX = 0; 
                let currentRotY = idCard.rotation.y % (Math.PI * 2);
                if (currentRotY > Math.PI) currentRotY -= Math.PI * 2;
                if (currentRotY < -Math.PI) currentRotY += Math.PI * 2;
                mainTargetRotY = currentRotY;
                document.body.classList.add('main-active');
                playSnapSound();
            } else {
                document.body.classList.remove('main-active');
                velocity.set(0,0,0);
                targetRotY = 0;
                playSnapSound();
            }
        }

        function updateMouseRaycast(event) {
            const rect = container.getBoundingClientRect();
            let clientX = event.clientX; let clientY = event.clientY;
            if(event.touches && event.touches.length > 0) {
                clientX = event.touches[0].clientX; clientY = event.touches[0].clientY;
            }
            mouse.x = ((clientX - rect.left) / rect.width) * 2 - 1;
            mouse.y = -((clientY - rect.top) / rect.height) * 2 + 1;
            raycaster.setFromCamera(mouse, camera);
            return { cx: clientX, cy: clientY };
        }

        container.addEventListener('click', (event) => {
            const currentTime = new Date().getTime();
            const timeDiff = currentTime - lastClickTime;
            const isDoubleClick = (timeDiff < 300 && timeDiff > 0);
            lastClickTime = currentTime;

            if(isDragging && !isKtaActive && !isMainActive) return;
            
            if (isKtaActive && ktaDragDist < 5 && !isDoubleClick) { toggleKTA(); return; }
            if (isMainActive && ktaDragDist < 5 && !isDoubleClick) { toggleMainCard(); return; }
            if (isDoubleClick && isMainActive) { toggleMainCard(); return; }

            updateMouseRaycast(event);

            if (isDoubleClick && !isKtaActive && !isMainActive) {
                const intersectsMain = raycaster.intersectObject(idCard, true);
                if (intersectsMain.length > 0) { toggleMainCard(); return; }
            }

            const intersects = raycaster.intersectObject(ktaMesh);
            if(intersects.length > 0 && !isKtaActive && !isMainActive && !isDoubleClick) {
                toggleKTA();
                document.getElementById('ktaTooltip').style.display = 'none'; 
            }
        });

        btnCloseKta.addEventListener('click', () => { 
            if(isKtaActive) toggleKTA(); 
            if(isMainActive) toggleMainCard();
        });

        let isDragging = false; let hasInteracted = false; 
        let targetPos = new THREE.Vector3().copy(restPos);
        let targetRotY = 0; let targetRotX = 0; let targetRotZ = 0;
        let velocity = new THREE.Vector3(0,0,0);
        const springK = 0.08; const damping = 0.82;   
        let previousMouse = {x: 0, y: 0};

        function onPointerDown(event) {
            ktaDragDist = 0; 
            const coords = updateMouseRaycast(event);
            
            if(isKtaActive || isMainActive) {
                isDragging = true;
                document.body.classList.add('is-grabbing'); 
                previousMouse = {x: coords.cx, y: coords.cy};
                return;
            }
            
            if(raycaster.intersectObject(ktaMesh).length > 0) return;

            isDragging = true;
            document.body.classList.add('is-grabbing'); 
            
            if (!hasInteracted) {
                document.getElementById('uxOverlay').classList.add('hidden');
                hasInteracted = true;
                if (audioCtx.state === 'suspended') audioCtx.resume();
            }

            previousMouse = {x: coords.cx, y: coords.cy};
        }

        function onPointerMove(event) {
            const rect = container.getBoundingClientRect();
            let cx = event.clientX; let cy = event.clientY;
            if(event.touches && event.touches.length > 0) {
                cx = event.touches[0].clientX; cy = event.touches[0].clientY;
            }
            
            const normX = ((cx - rect.left) / rect.width) * 2 - 1;
            const normY = -((cy - rect.top) / rect.height) * 2 + 1;
            
            targetCameraX = normX * 2; targetCameraY = normY * 2;

            if (isDragging) ktaDragDist += Math.abs(cx - previousMouse.x) + Math.abs(cy - previousMouse.y);

            if (isKtaActive && isDragging) {
                ktaTargetRotY += (cx - previousMouse.x) * 0.01;
                ktaTargetRotX += (cy - previousMouse.y) * 0.01;
                previousMouse = {x: cx, y: cy}; return; 
            }
            
            if (isMainActive && isDragging) {
                mainTargetRotY += (cx - previousMouse.x) * 0.01;
                mainTargetRotX += (cy - previousMouse.y) * 0.01;
                previousMouse = {x: cx, y: cy}; return; 
            }

            if (!isDragging || isKtaActive || isMainActive) return;

            targetPos.x = normX * 14; targetPos.y = normY * 14; targetPos.z = 3; 
            targetRotY += (cx - previousMouse.x) * 0.015;
            previousMouse = {x: cx, y: cy};
        }

        function onPointerUp() {
            if(isDragging) {
                isDragging = false;
                document.body.classList.remove('is-grabbing'); 
                if (!isKtaActive && !isMainActive) { targetPos.copy(restPos); playSnapSound(); }
            }
        }

        container.addEventListener('mousedown', onPointerDown); 
        window.addEventListener('mousemove', onPointerMove); 
        window.addEventListener('mouseup', onPointerUp);
        container.addEventListener('touchstart', onPointerDown, {passive: false}); 
        window.addEventListener('touchmove', onPointerMove, {passive: false}); 
        window.addEventListener('touchend', onPointerUp);

        // RENDER LOOP MURNI
        function animate() {
            requestAnimationFrame(animate);
            const time = Date.now() * 0.001;

            if (!isMainActive && !isKtaActive) {
                camera.position.x += (targetCameraX - camera.position.x) * 0.05;
                camera.position.y += (targetCameraY - camera.position.y) * 0.05;
                camera.lookAt(0, 0, 0); 
            } else {
                camera.position.x += (0 - camera.position.x) * 0.1;
                camera.position.y += (0 - camera.position.y) * 0.1;
                camera.lookAt(0, 0, 0);
            }

            if (isMainActive) {
                idCard.position.lerp(mainViewPos, 0.08);
                const targetQuat = new THREE.Quaternion().setFromEuler(new THREE.Euler(mainTargetRotX, mainTargetRotY, 0));
                idCard.quaternion.slerp(targetQuat, 0.1);
            } else {
                const force = new THREE.Vector3().subVectors(targetPos, idCard.position).multiplyScalar(springK);
                velocity.add(force); velocity.multiplyScalar(damping); 
                idCard.position.add(velocity);

                targetRotX = velocity.y * 0.05; targetRotZ = -velocity.x * 0.05; 
                idCard.rotation.x += (targetRotX - idCard.rotation.x) * 0.2;
                idCard.rotation.y += (targetRotY - idCard.rotation.y) * 0.15;
                idCard.rotation.z += (targetRotZ - idCard.rotation.z) * 0.2;
            }

            if(isKtaActive) {
                ktaMesh.position.lerp(ktaViewPos, 0.08);
                const targetQuat = new THREE.Quaternion().setFromEuler(new THREE.Euler(ktaTargetRotX, ktaTargetRotY, 0));
                ktaMesh.quaternion.slerp(targetQuat, 0.1);
            } else {
                const floatY = Math.sin(time) * 0.5;
                ktaMesh.position.lerp(new THREE.Vector3(ktaRestPos.x, ktaRestPos.y + floatY, ktaRestPos.z), 0.1);
                ktaMesh.rotation.y += 0.008;
                ktaMesh.rotation.x += (0.2 - ktaMesh.rotation.x) * 0.05;
                ktaMesh.rotation.z += (0.1 - ktaMesh.rotation.z) * 0.05;
            }

            updateLanyardGeometry();
            renderer.render(scene, camera); 
        }
        animate();

        const resizeObserver = new ResizeObserver(entries => {
            for (let entry of entries) {
                const width = entry.contentRect.width; 
                const height = entry.contentRect.height;
                renderer.setSize(width, height);
                camera.aspect = width / height;
                camera.fov = width < 600 ? 65 : 45;
                if(width < 600) { ktaRestPos.set(-4, -5, -4); } else { ktaRestPos.set(-8, 0, -2); }
                camera.updateProjectionMatrix();
            }
        });
        resizeObserver.observe(container);

        // PENCAHAYAAN YANG LEBIH HALUS AGAR TEXT TIDAK PUDAR
        function update3DLighting() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if(currentTheme === 'light') {
                ambientLight.intensity = 0.7; // Diturunkan drastis agar tidak pudar
                mainLight.intensity = 50; 
                renderer.toneMappingExposure = 0.9;
            } else {
                ambientLight.intensity = 0.4; 
                mainLight.intensity = 40; 
                renderer.toneMappingExposure = 0.8;
            }
        }
        
        const themeObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => { if (mutation.attributeName === 'data-theme') update3DLighting(); });
        });
        themeObserver.observe(document.documentElement, { attributes: true });
        update3DLighting();
    }
</script>
<?= $this->endSection() ?>