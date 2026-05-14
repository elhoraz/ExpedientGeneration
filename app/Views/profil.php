<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Profil Eksklusif - Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="/css/profil.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="profil-wrapper">

    <!-- ================= FASE 1: BIOMETRIC CONCIERGE ================= -->
    <div class="auth-vault" id="authVault">
        <div class="retina-container" id="retinaContainer">
            <div class="focus-ring" id="focusRing"></div>
            <!-- Leica Brackets -->
            <div class="bracket bracket-tl"></div>
            <div class="bracket bracket-tr"></div>
            <div class="bracket bracket-bl"></div>
            <div class="bracket bracket-br"></div>
            
            <div class="camera-frame">
                <video id="cameraFeed" class="camera-feed" autoplay playsinline muted></video>
                <div class="lens-dust"></div>
                <div class="scan-line" id="scanLine"></div>
            </div>
        </div>

        <div class="liveness-indicator">
            <div class="live-node" id="step1"></div>
            <div class="live-node" id="step2"></div>
            <div class="live-node" id="step3"></div>
        </div>

        <div class="status-display" id="statusBadge">
            <div class="status-title">Verifikasi Keamanan</div>
            <div class="status-value" id="statusText">Mengkalibrasi optik...</div>
        </div>
    </div>

    <!-- ================= FASE 2: PROFIL EKSKLUSIF ================= -->
    <div class="control-panel" id="controlPanel">
        
        <div class="nav-actions stagger-item">
            <a href="/beranda" class="action-btn cursor-bind"><i class="fa-solid fa-arrow-left-long"></i> Kembali ke Beranda</a>
            <a href="/chat" class="action-btn cursor-bind" style="background: rgba(212,175,55,0.1); border-color: rgba(212,175,55,0.3); color: #d4af37;"><i class="fa-solid fa-envelope"></i> Kotak Pesan</a>
            <a href="/logout" class="action-btn btn-danger cursor-bind"><i class="fa-solid fa-power-off"></i> Keluar</a>
        </div>

        <div class="dashboard-header stagger-item">
            <h1 class="dashboard-title">Profil Eksklusif</h1>
            <p class="dashboard-subtitle">Kelola Data Pribadi Anda</p>
            
            <div style="margin-top: 20px; display: inline-flex; align-items: center; background: rgba(0,0,0,0.3); padding: 8px 20px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.05); box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);">
                <div style="background: <?= $badge_color ?>; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; color: #fff; text-transform: uppercase; letter-spacing: 1px; margin-right: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <i class="fa-solid fa-crown" style="margin-right: 5px;"></i> <?= $gelar_kehormatan ?>
                </div>
                <span style="font-family: monospace; font-size: 1.1rem; color: #d4af37; font-weight: bold;">
                    <?= number_format($prestise_points) ?> <span style="font-size: 0.75rem; color: #888; letter-spacing: 1px; margin-left: 3px;">PRESTISE</span>
                </span>
            </div>
        </div>

        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="sys-msg success stagger-item">
                <i class="fa-solid fa-circle-check" style="color: var(--success-elegant); font-size: 1.2rem;"></i> 
                <?= session()->getFlashdata('pesan') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="sys-msg error stagger-item">
                <i class="fa-solid fa-triangle-exclamation" style="color: var(--danger-elegant); font-size: 1.2rem;"></i> 
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="profil-grid tilt-container">
            
            <!-- KOLOM KIRI: EDIT PROFIL -->
            <div class="premium-panel stagger-item parallax-card">
                <h2 class="panel-title"><i class="fa-regular fa-id-card"></i> Identitas Personal</h2>
                
                <form action="/profil/update" method="POST" id="formUpdateProfile">
                    <?= csrf_field() ?>
                    <input type="hidden" name="foto_profil_base64" id="fotoBase64Value">

                    <!-- Area Upload Foto -->
                    <div class="photo-upload-wrapper">
                        <div class="magnetic-avatar cursor-bind" id="magAvatar">
                            <img src="<?= !empty($user['foto_profil']) ? base_url('uploads/profiles/' . $user['foto_profil']) : 'https://ui-avatars.com/api/?name='.urlencode($user['nama_panggilan']).'&background=d4af37&color=000' ?>" class="avatar-preview" id="avatarPreview" alt="Profil">
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px;">Potret Resmi</div>
                            <div class="magnetic-btn-wrap" id="magBtnWrap">
                                <label class="upload-btn-ui cursor-bind" id="magBtn" for="inputFileImg">
                                    Pilih Potret
                                </label>
                            </div>
                            <input type="file" id="inputFileImg" accept="image/*" style="display:none;">
                            <div style="font-size: 0.65rem; color: var(--text-secondary); margin-top: 5px;">Maksimum resolusi HD disarankan.</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="nama_panggilan" class="form-input" id="inp_panggilan" placeholder=" " value="<?= esc($user['nama_panggilan']) ?>" required>
                            <label class="form-label" for="inp_panggilan">Nama Sandi / Panggilan</label>
                            <div class="liquid-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="number" name="no_whatsapp" class="form-input" id="inp_wa" placeholder=" " value="<?= esc($user['no_whatsapp']) ?>" required>
                            <label class="form-label" for="inp_wa">Nomor Kontak (WhatsApp)</label>
                            <div class="liquid-line"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="nama_lengkap" class="form-input" id="inp_lengkap" placeholder=" " value="<?= esc($user['nama_lengkap']) ?>" required>
                        <label class="form-label" for="inp_lengkap">Nama Lengkap Resmi</label>
                        <div class="liquid-line"></div>
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" class="form-input" id="inp_email" placeholder=" " value="<?= esc($user['email']) ?>" required>
                        <label class="form-label" for="inp_email">Alamat Surel Utama</label>
                        <div class="liquid-line"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="akun_ig" class="form-input" id="inp_ig" placeholder=" " value="<?= esc($user['akun_ig'] ?? '') ?>">
                            <label class="form-label" for="inp_ig">Instagram (Opsional)</label>
                            <div class="liquid-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="akun_tiktok" class="form-input" id="inp_tt" placeholder=" " value="<?= esc($user['akun_tiktok'] ?? '') ?>">
                            <label class="form-label" for="inp_tt">TikTok (Opsional)</label>
                            <div class="liquid-line"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="motivasi_hidup" class="form-input" id="inp_motivasi" placeholder=" " oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"><?= esc($user['motivasi_hidup'] ?? '') ?></textarea>
                        <label class="form-label" for="inp_motivasi">Visi & Motivasi</label>
                        <div class="liquid-line"></div>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="cita_cita" class="form-input" id="inp_cita" placeholder=" " value="<?= esc($user['cita_cita'] ?? '') ?>">
                        <label class="form-label" for="inp_cita">Target Pencapaian</label>
                        <div class="liquid-line"></div>
                    </div>

                    <button type="submit" class="btn-submit cursor-bind">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- KOLOM KANAN: PROTOKOL KEAMANAN -->
            <div class="premium-panel stagger-item parallax-card">
                <h2 class="panel-title"><i class="fa-solid fa-fingerprint"></i> Akses & Keamanan</h2>
                
                <div class="bio-status-box cursor-bind">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div class="bio-status-title">Keamanan Visual Aktif</div>
                    <div class="bio-status-desc">Wajah Anda menjadi kunci tunggal untuk membedah data Sovereign ini. Tingkat akurasi pemindaian telah dimaksimalkan.</div>
                </div>

                <div style="font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin: 40px 0 20px 0;">Kredensial Fisik (Passkey)</div>
                
                <?php if(!empty($user['webauthn_credential_id'])): ?>
                    <div style="color: var(--text-primary); font-size: 0.8rem; padding: 25px; border: 1px solid var(--glass-border); border-radius: 20px; text-align: center; line-height: 1.6;">
                        <i class="fa-solid fa-lock" style="font-size:1.5rem; margin-bottom:15px; color: var(--success-elegant);"></i><br>
                        Perangkat ini telah terkunci dan disahkan sebagai Token Keamanan Fisik.
                    </div>
                <?php else: ?>
                    <div id="bioOptionsBox">
                        <button class="btn-passkey cursor-bind" onclick="startBiometricEnrollment()">
                            <i class="fa-solid fa-key" style="margin-right: 5px;"></i> Autentikasi Perangkat Ini
                        </button>
                    </div>
                <?php endif; ?>
                
                <div id="bioStatus" style="margin-top: 20px; font-size: 0.75rem; color: var(--text-secondary); text-align: center; line-height: 1.6;">
                    Gunakan biometrik bawaan (Touch ID/Face ID) pada gawai Anda sebagai otentikasi lapis kedua tanpa sandi.
                </div>
                
                <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--glass-border); text-align: center;">
                    <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px;">Identitas Eksekutif</div>
                    <a href="/sovereign" class="cursor-bind" style="color:var(--gold-premium); text-decoration:none; font-size:0.85rem; display:inline-block; margin-top:15px; font-weight: 600; letter-spacing: 2px;"><i class="fa-solid fa-cube" style="margin-right: 8px;"></i> BUKA SOVEREIGN ID 5D</a>
                </div>
            </div>

        </div>
    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // === ELEMEN UI ===
    const videoElement = document.getElementById('cameraFeed');
    const statusText = document.getElementById('statusText');
    const authVault = document.getElementById('authVault');
    const controlPanel = document.getElementById('controlPanel');
    const focusRing = document.getElementById('focusRing');
    const scanLine = document.getElementById('scanLine');
    const retinaContainer = document.getElementById('retinaContainer');
    
    // HUD Nodes
    const s1 = document.getElementById('step1');
    const s2 = document.getElementById('step2');
    const s3 = document.getElementById('step3');

    // === STATE ===
    let streamRef = null;
    let timeoutRef = null;
    let isAuthenticating = false;
    let authCompleted = false;
    let currentStep = 'matching';
    let scanAnim = null;

    const dbFaceDataRaw = <?= empty($face_data_db) || $face_data_db === 'null' ? 'null' : $face_data_db ?>;
    let targetDescriptor = null;

    // === HELPER UI MEWAH ===
    const updateTextFade = (text, color) => {
        gsap.to(statusText, {
            opacity: 0,
            y: -10,
            duration: 0.3,
            onComplete: () => {
                statusText.innerText = text;
                if(color) statusText.style.color = color;
                gsap.fromTo(statusText, { y: 10 }, { opacity: 1, y: 0, duration: 0.4, ease: "power2.out" });
            }
        });
    };

    const updateHUD = (text, color, isScanning, step) => {
        updateTextFade(text, color);
        
        if (isScanning && !scanAnim) {
            retinaContainer.classList.add('scanning');
            gsap.set(scanLine, { opacity: 1 });
            scanAnim = gsap.to(scanLine, {
                top: "100%",
                duration: 2,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });
        } else if (!isScanning && scanAnim) {
            retinaContainer.classList.remove('scanning');
            scanAnim.kill();
            scanAnim = null;
            gsap.to(scanLine, { opacity: 0, duration: 0.3 });
        }

        if (color) {
            focusRing.style.borderColor = color;
            focusRing.style.boxShadow = `inset 0 0 40px var(--glass-bg), 0 0 50px ${color}30`;
        }

        if(step === 1) { s1.className = 'live-node active'; s2.className = 'live-node'; s3.className = 'live-node'; }
        if(step === 2) { s1.className = 'live-node done'; s2.className = 'live-node active'; s3.className = 'live-node'; }
        if(step === 3) { s1.className = 'live-node done'; s2.className = 'live-node done'; s3.className = 'live-node active'; }
        if(step === 'done') { s1.className = 'live-node done'; s2.className = 'live-node done'; s3.className = 'live-node done'; }
    };

    // Fungsi gagal total tanpa tombol bypass
    const triggerFatalError = (msg) => {
        authCompleted = true;
        updateHUD(msg, "var(--danger-elegant)", false, 0);
        setTimeout(() => {
            statusText.innerHTML = `<span style="font-size:0.9rem; color:var(--text-secondary);">Sistem keamanan mendeteksi anomali. Akses profil terkunci. Harap kembali ke Beranda.</span><br><br><a href="/beranda" class="action-btn" style="display:inline-flex; width:auto; justify-content:center; border-color:var(--text-primary); color:var(--text-primary); margin: 0 auto;">Kembali</a>`;
        }, 1500);
    };

    window.unlockControlPanel = () => {
        authCompleted = true;
        if (streamRef) streamRef.getTracks().forEach(track => track.stop());
        if (timeoutRef) clearTimeout(timeoutRef);

        gsap.to(authVault, {
            opacity: 0, scale: 1.1, filter: "blur(20px)", duration: 1.5, ease: "power3.inOut",
            onComplete: () => {
                authVault.style.display = "none";
                controlPanel.style.display = "flex";
                
                // Animasi Stagger GSAP Mewah untuk Form
                const tl = gsap.timeline();
                tl.to(controlPanel, { opacity: 1, duration: 0.5 })
                  .from(".stagger-item", { 
                      y: 40, 
                      opacity: 0, 
                      duration: 1, 
                      stagger: 0.15, 
                      ease: "power4.out" 
                  });
            }
        });
    };

    const startFaceVerification = async () => {
        if (dbFaceDataRaw === null) {
            triggerFatalError("Profil Biometrik Kosong");
            return;
        }

        targetDescriptor = new Float32Array(dbFaceDataRaw);
        updateHUD("Menyiapkan Sistem Cerdas...", "var(--gold-premium)", false, 0);

        try {
            const MODEL_URL = window.location.origin + '/assets/models';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
            ]);
        } catch (err) {
            return triggerFatalError("Gagal Menginisialisasi Modul");
        }

        updateHUD("Sinkronisasi Lensa...", "var(--gold-premium)", false, 0);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
            videoElement.srcObject = stream;
            streamRef = stream;
        } catch (err) {
            return triggerFatalError("Kamera Diblokir oleh Perangkat");
        }

        videoElement.onplay = () => {
            updateHUD("Menganalisis profil kedalaman...", "var(--gold-premium)", true, 1);
            
            const detectorOptions = new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.5 });
            
            const runDetection = async () => {
                if (authCompleted) return;
                if (isAuthenticating) {
                    timeoutRef = setTimeout(runDetection, 600);
                    return;
                }

                try {
                    const detection = await faceapi.detectSingleFace(videoElement, detectorOptions)
                                                   .withFaceLandmarks().withFaceDescriptor().withFaceExpressions();
                    
                    if (!detection) {
                        updateHUD("Mohon tatap tepat ke tengah lensa", "var(--gold-premium)", true, currentStep === 'matching'? 1 : (currentStep==='smiling'? 2:3));
                    } else {
                        if (currentStep === 'matching') {
                            const distance = faceapi.euclideanDistance(targetDescriptor, detection.descriptor);
                            if (distance < 0.5) {
                                currentStep = 'smiling';
                                isAuthenticating = true;
                                updateHUD("Identitas dikonfirmasi. Tunjukkan senyum Anda...", "var(--text-primary)", true, 2);
                                setTimeout(() => { isAuthenticating = false; }, 1200); 
                            } else {
                                updateHUD("Akses Ditolak: Wajah Tidak Dikenali", "var(--danger-elegant)", true, 1);
                            }
                        } 
                        else if (currentStep === 'smiling') {
                            if (detection.expressions.happy > 0.85) {
                                currentStep = 'turning';
                                isAuthenticating = true;
                                updateHUD("Sempurna. Tolehkan kepala Anda sedikit...", "var(--text-primary)", true, 3);
                                setTimeout(() => { isAuthenticating = false; }, 1500); 
                            } else {
                                updateHUD("Menunggu verifikasi ekspresi...", "var(--text-primary)", true, 2);
                            }
                        } 
                        else if (currentStep === 'turning') {
                            const landmarks = detection.landmarks;
                            const nose = landmarks.getNose()[0];
                            const leftEye = landmarks.getLeftEye()[0];
                            const rightEye = landmarks.getRightEye()[0];
                            
                            const distLeft = Math.abs(nose.x - leftEye.x);
                            const distRight = Math.abs(nose.x - rightEye.x);
                            
                            if (distRight !== 0) {
                                const ratio = distLeft / distRight;
                                if (ratio < 0.35 || ratio > 2.5) {
                                    authCompleted = true;
                                    updateHUD("Akses VVIP Diberikan", "var(--success-elegant)", false, 'done');
                                    setTimeout(unlockControlPanel, 1200);
                                    return; 
                                } else {
                                    updateHUD("Terus tolehkan perlahan...", "var(--text-primary)", true, 3);
                                }
                            }
                        }
                    }
                } catch(e) {
                    console.error("Face API Error:", e);
                }

                if (!authCompleted) timeoutRef = setTimeout(runDetection, 600);
            };

            runDetection();
        };
    };

    // === MAGNETIC HOVER ENGINE (INTERAKTIVITAS) ===
    const magBtnWrap = document.getElementById('magBtnWrap');
    const magBtn = document.getElementById('magBtn');
    const magAvatar = document.getElementById('magAvatar');

    const applyMagnetic = (wrap, el, strength) => {
        wrap.addEventListener('mousemove', (e) => {
            const rect = wrap.getBoundingClientRect();
            const x = (e.clientX - rect.left) - (rect.width / 2);
            const y = (e.clientY - rect.top) - (rect.height / 2);
            gsap.to(el, { x: x * strength, y: y * strength, duration: 0.4, ease: "power2.out" });
        });
        wrap.addEventListener('mouseleave', () => {
            gsap.to(el, { x: 0, y: 0, duration: 0.8, ease: "elastic.out(1, 0.3)" });
        });
    };

    if(window.matchMedia("(pointer: fine)").matches) {
        applyMagnetic(magBtnWrap, magBtn, 0.4);
        applyMagnetic(magAvatar, magAvatar.querySelector('img'), 0.2);

        // PARALLAX KARTU
        const tiltContainer = document.querySelector('.tilt-container');
        const cards = document.querySelectorAll('.parallax-card');
        
        tiltContainer.addEventListener('mousemove', (e) => {
            const rect = tiltContainer.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            const tiltX = (0.5 - y) * 10; // Max 10 deg
            const tiltY = (x - 0.5) * 10;
            
            cards.forEach(card => {
                gsap.to(card, {
                    rotateX: tiltX,
                    rotateY: tiltY,
                    duration: 0.5,
                    ease: "power2.out"
                });
            });
        });
        
        tiltContainer.addEventListener('mouseleave', () => {
            cards.forEach(card => {
                gsap.to(card, { rotateX: 0, rotateY: 0, duration: 1, ease: "elastic.out(1, 0.5)" });
            });
        });
    }

    // === FITUR UPLOAD FOTO PROFIL ===
    const fileInput = document.getElementById('inputFileImg');
    const previewImg = document.getElementById('avatarPreview');
    const base64Input = document.getElementById('fotoBase64Value');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const MAX_WIDTH = 500;
                const MAX_HEIGHT = 500;
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > MAX_WIDTH) { height *= MAX_WIDTH / width; width = MAX_WIDTH; }
                } else {
                    if (height > MAX_HEIGHT) { width *= MAX_HEIGHT / height; height = MAX_HEIGHT; }
                }

                canvas.width = width; canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                
                // Animasi ganti foto
                gsap.to(previewImg, { scale: 0.8, opacity: 0, duration: 0.3, onComplete: () => {
                    previewImg.src = dataUrl;
                    base64Input.value = dataUrl;
                    gsap.to(previewImg, { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(1.5)" });
                }});
            }
            img.src = event.target.result;
        }
        reader.readAsDataURL(file);
    });

    startFaceVerification();
});

// ================= LOGIKA WEBAUTHN / PASSKEY =================
function base64urlToBuffer(base64url) {
    if (!base64url) return new ArrayBuffer(0);
    const padding = '='.repeat((4 - base64url.length % 4) % 4);
    const base64 = (base64url + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) { outputArray[i] = rawData.charCodeAt(i); }
    return outputArray.buffer;
}

function bufferToBase64url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) { binary += String.fromCharCode(bytes[i]); }
    return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

async function startBiometricEnrollment() {
    const statusEl = document.getElementById('bioStatus');
    const optionsBox = document.getElementById('bioOptionsBox');
    
    if (navigator.vibrate) navigator.vibrate(50);

    try {
        gsap.to(optionsBox, { opacity: 0, height: 0, duration: 0.4, onComplete: () => optionsBox.style.display = 'none' });
        statusEl.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyiapkan protokol kriptografi...';
        statusEl.style.color = "var(--gold-premium)";

        const res = await fetch('/api/biometric/register-options');
        const opt = await res.json();
        if (opt.error) throw new Error(opt.error);

        const pkConfig = opt.publicKey ? opt.publicKey : opt;
        if (!pkConfig.challenge) throw new Error("Tantangan Kriptografi gagal dimuat.");

        pkConfig.challenge = base64urlToBuffer(pkConfig.challenge);
        pkConfig.user.id = base64urlToBuffer(pkConfig.user.id);
        if (pkConfig.excludeCredentials) {
            pkConfig.excludeCredentials.forEach(cred => { cred.id = base64urlToBuffer(cred.id); });
        }

        statusEl.innerText = "Silakan autentikasi menggunakan Touch ID / Face ID / PIN pada perangkat ini...";

        const credential = await navigator.credentials.create({ publicKey: pkConfig });
        statusEl.innerText = "Menyegel kunci keamanan...";

        const attestationData = {
            id: credential.id,
            rawId: bufferToBase64url(credential.rawId),
            type: credential.type,
            response: { clientDataJSON: bufferToBase64url(credential.response.clientDataJSON) }
        };

        const verifyRes = await fetch('/api/biometric/register-verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(attestationData)
        });

        const result = await verifyRes.json();

        if (result.status === 'success') {
            statusEl.innerHTML = '<i class="fa-solid fa-check"></i> Perangkat Berhasil Disahkan';
            statusEl.style.color = "var(--success-elegant)";
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
            setTimeout(() => window.location.reload(), 2000); 
        } else {
            throw new Error(result.error || 'Gagal menyegel kredensial.');
        }

    } catch (err) {
        console.error(err);
        optionsBox.style.display = 'block';
        gsap.to(optionsBox, { opacity: 1, height: 'auto', duration: 0.4 });
        statusEl.innerText = "Sertifikasi Dibatalkan: " + err.message;
        statusEl.style.color = "var(--danger-elegant)";
    }
}
</script>
<?= $this->endSection() ?>
