<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Sovereign Dossier - Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ================= 1. CORE VARIABLES ================= */
    :root {
        --gold-premium: #d4af37;
        --gold-light: #f3e5ab;
        --danger-elegant: #d9455f;
        --success-elegant: #2bb97c;
        --input-bg: transparent;
        --input-border: var(--glass-border);
    }
    
    :root[data-theme="light"] {
        --gold-premium: #b89018; /* Slightly darker gold for readability on light */
    }

    /* ================= 2. GLOBAL PROFIL WRAPPER ================= */
    .profil-wrapper {
        position: relative; 
        width: 100%; 
        min-height: 100vh;
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center;
        padding: clamp(100px, 15vh, 150px) 5% 10vh; 
        z-index: 10;
        color: var(--text-primary);
        transition: color 0.5s ease;
    }

    /* ================= 3. FASE 1: BIOMETRIC GATEWAY (LEICA / FOCUS STYLE) ================= */
    .auth-vault {
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center;
        width: 100%; 
        max-width: 500px; 
        transition: all 1.2s var(--awwwards-ease); 
        z-index: 50;
        position: absolute; 
        top: 50%; 
        left: 50%; 
        transform: translate(-50%, -50%);
    }

    .retina-container {
        position: relative; 
        width: 320px; 
        height: 320px; 
        display: flex; 
        justify-content: center; 
        align-items: center;
        margin-bottom: 50px;
    }

    /* Outer Focus Ring (Static) */
    .focus-ring {
        position: absolute;
        inset: 0;
        border: 1px solid var(--glass-border);
        border-radius: 50%;
        box-shadow: inset 0 0 40px var(--glass-bg), 0 20px 60px rgba(0,0,0,0.3);
        z-index: 1;
        transition: border-color 0.8s ease, box-shadow 0.8s ease;
    }
    
    :root[data-theme="light"] .focus-ring {
        box-shadow: inset 0 0 40px var(--glass-bg), 0 20px 60px rgba(0,0,0,0.05);
    }

    /* The Leica-style brackets */
    .bracket {
        position: absolute;
        width: 40px;
        height: 40px;
        border: 2px solid transparent;
        z-index: 20;
        transition: transform 0.5s var(--awwwards-ease), border-color 0.5s;
    }
    .bracket-tl { top: 25px; left: 25px; border-top-color: var(--gold-premium); border-left-color: var(--gold-premium); }
    .bracket-tr { top: 25px; right: 25px; border-top-color: var(--gold-premium); border-right-color: var(--gold-premium); }
    .bracket-bl { bottom: 25px; left: 25px; border-bottom-color: var(--gold-premium); border-left-color: var(--gold-premium); }
    .bracket-br { bottom: 25px; right: 25px; border-bottom-color: var(--gold-premium); border-right-color: var(--gold-premium); }

    .scanning .bracket-tl { transform: translate(-10px, -10px); }
    .scanning .bracket-tr { transform: translate(10px, -10px); }
    .scanning .bracket-bl { transform: translate(-10px, 10px); }
    .scanning .bracket-br { transform: translate(10px, 10px); }

    /* The actual Camera Viewport */
    .camera-frame {
        position: absolute; 
        width: 250px; 
        height: 250px; 
        border-radius: 50%; 
        overflow: hidden;
        z-index: 10; 
        background: #050505; /* Always dark for camera */
    }

    .camera-frame::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: radial-gradient(circle at 50% 50%, transparent 60%, rgba(0,0,0,0.8) 100%);
        pointer-events: none;
    }

    .camera-feed { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transform: scaleX(-1); 
        filter: contrast(1.1) saturate(1.1) grayscale(0.1); 
    }

    /* Dust/Gold Particle overlay on camera */
    .lens-dust {
        position: absolute;
        inset: 0;
        z-index: 15;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.15'/%3E%3C/svg%3E");
        pointer-events: none;
        mix-blend-mode: color-dodge;
    }

    /* The luxurious scan line */
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 1px;
        background: var(--gold-premium);
        opacity: 0;
        z-index: 16;
        box-shadow: 0 0 20px 2px var(--gold-premium);
        pointer-events: none;
    }

    .liveness-indicator { 
        display: flex; 
        align-items: center; 
        gap: 25px; 
        margin-bottom: 35px; 
    }
    
    .live-node { 
        width: 5px; 
        height: 5px; 
        border-radius: 50%; 
        background: var(--glass-border); 
        transition: 0.6s var(--awwwards-ease); 
    }
    
    .live-node.active { 
        background: var(--gold-premium); 
        box-shadow: 0 0 15px var(--gold-premium); 
        transform: scale(2);
    }
    
    .live-node.done { 
        background: var(--text-primary); 
    }

    .status-display {
        text-align: center;
        min-width: 320px;
        padding: 20px;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
    }

    .status-title { 
        font-family: 'Inter', sans-serif; 
        font-size: 0.65rem; 
        color: var(--text-secondary); 
        letter-spacing: 4px; 
        text-transform: uppercase; 
        margin-bottom: 15px; 
    }

    .status-value { 
        font-family: 'Playfair Display', serif; 
        font-size: 1.4rem; 
        font-weight: 500; 
        color: var(--text-primary); 
        letter-spacing: 1px; 
        min-height: 35px; /* Prevent jitter */
    }

    /* ================= 4. FASE 2: SOVEREIGN DOSSIER ================= */
    .control-panel {
        display: none; 
        width: 100%; 
        max-width: 1150px;
        flex-direction: column; 
        opacity: 0;
    }

    .nav-actions {
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 50px;
    }
    
    .action-btn {
        background: var(--glass-bg); 
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        padding: 14px 30px; 
        border-radius: 40px; 
        color: var(--text-primary); 
        text-decoration: none; 
        font-size: 0.75rem; 
        letter-spacing: 2px; 
        text-transform: uppercase; 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        transition: all 0.5s var(--awwwards-ease); 
        font-family: 'Inter', sans-serif;
        box-shadow: var(--glass-shadow);
        overflow: hidden;
        position: relative;
    }
    
    .action-btn::before {
        content: ''; position: absolute; inset: 0; background: var(--text-primary); transform: scaleY(0); transform-origin: bottom; transition: transform 0.5s var(--awwwards-ease); z-index: -1;
    }

    .action-btn:hover { 
        border-color: var(--text-primary); 
        color: var(--bg-main);
    }
    
    .action-btn:hover::before {
        transform: scaleY(1);
    }
    
    .btn-danger::before { background: var(--danger-elegant); }
    .btn-danger:hover { border-color: var(--danger-elegant); color: #fff; }

    .dashboard-header { 
        text-align: center; 
        margin-bottom: 70px; 
    }
    
    .dashboard-title { 
        font-family: 'Playfair Display', serif; 
        color: var(--text-primary); 
        font-size: clamp(3rem, 5vw, 4.5rem); 
        margin: 0; 
        font-weight: 500;
        letter-spacing: 2px; 
        line-height: 1.1;
    }
    
    .dashboard-subtitle { 
        color: var(--gold-premium); 
        font-size: 0.85rem; 
        letter-spacing: 6px; 
        text-transform: uppercase; 
        margin-top: 20px; 
        font-weight: 400;
    }

    .profil-grid { 
        display: grid; 
        grid-template-columns: 1.5fr 1fr; 
        gap: 40px; 
    }
    @media (max-width: 900px) { .profil-grid { grid-template-columns: 1fr; } }

    .premium-panel {
        background: var(--glass-bg); 
        backdrop-filter: var(--glass-blur); 
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        border-radius: 30px; 
        padding: 50px; 
        position: relative; 
        box-shadow: var(--glass-shadow);
        transition: transform 0.3s ease;
    }
    
    /* Subtle parallax tilt effect container */
    .tilt-container { transform-style: preserve-3d; perspective: 1000px; }

    .panel-title { 
        font-family: 'Playfair Display', serif; 
        color: var(--text-primary); 
        font-size: 1.8rem; 
        margin-bottom: 45px; 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        font-weight: 400;
    }
    
    .panel-title i { 
        color: var(--gold-premium); 
        font-size: 1.3rem;
    }

    /* Custom Form Elements - Liquid Line Animation */
    .form-group { margin-bottom: 40px; position: relative; }
    
    .form-label { 
        position: absolute;
        top: 15px;
        left: 0;
        font-size: 0.85rem; 
        color: var(--text-secondary); 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        transition: all 0.4s var(--awwwards-ease);
        pointer-events: none;
    }
    
    .form-input {
        width: 100%; 
        background: transparent; 
        border: none;
        border-bottom: 1px solid var(--glass-border);
        color: var(--text-primary); 
        padding: 15px 0 10px 0; 
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem; 
        font-weight: 400;
        transition: all 0.4s ease;
    }
    
    .form-input:focus { outline: none; }
    
    .liquid-line {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: var(--gold-premium);
        transition: all 0.6s var(--awwwards-ease);
        transform: translateX(-50%);
    }

    .form-input:focus ~ .liquid-line, 
    .form-input:not(:placeholder-shown) ~ .liquid-line {
        width: 100%;
    }

    /* Floating label trigger */
    .form-input:focus ~ .form-label,
    .form-input:not(:placeholder-shown) ~ .form-label {
        top: -15px;
        font-size: 0.65rem;
        color: var(--gold-premium);
    }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    /* Photo Upload Area - Magnetic Hover */
    .photo-upload-wrapper { 
        display: flex; 
        align-items: center; 
        gap: 35px; 
        margin-bottom: 60px; 
    }
    
    .magnetic-avatar {
        position: relative;
        width: 120px; 
        height: 120px; 
        border-radius: 50%; 
        border: 2px solid var(--glass-border);
        padding: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.3s ease;
    }

    .avatar-preview {
        width: 100%; 
        height: 100%; 
        border-radius: 50%; 
        object-fit: cover; 
    }
    
    .magnetic-btn-wrap {
        display: inline-block;
        padding: 20px;
        margin-left: -20px; /* offset padding */
    }

    .upload-btn-ui {
        background: var(--text-primary); 
        color: var(--bg-main);
        padding: 14px 28px; 
        border-radius: 40px; 
        font-size: 0.75rem; 
        letter-spacing: 2px; 
        cursor: pointer; 
        transition: transform 0.2s linear; 
        display: inline-flex; 
        align-items: center; 
        gap: 10px;
        text-transform: uppercase;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .upload-btn-ui:hover { transform: scale(1.05); }

    .btn-submit {
        position: relative;
        width: 100%; 
        background: var(--text-primary);
        border: none; 
        color: var(--bg-main); 
        padding: 22px; 
        border-radius: 16px; 
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem; 
        font-weight: 600; 
        letter-spacing: 3px; 
        text-transform: uppercase; 
        cursor: pointer;
        display: flex; 
        justify-content: center; 
        align-items: center; 
        gap: 15px; 
        transition: all 0.5s var(--awwwards-ease); 
        margin-top: 40px;
        overflow: hidden;
    }
    
    .btn-submit::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: translateX(-100%); transition: 0.6s ease;
    }

    .btn-submit:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 20px 40px rgba(0,0,0,0.2); 
    }
    
    .btn-submit:hover::after { transform: translateX(100%); }

    /* Biometric Box */
    .bio-status-box {
        background: var(--glass-bg); 
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border); 
        border-radius: 20px; 
        padding: 35px 30px; 
        margin-bottom: 40px; 
        text-align: center;
        transition: border-color 0.4s, box-shadow 0.4s;
    }
    
    .bio-status-box:hover {
        border-color: var(--gold-premium);
        box-shadow: 0 10px 30px rgba(212,175,55,0.1);
    }
    
    .bio-status-box i { 
        font-size: 2.2rem; 
        color: var(--gold-premium); 
        margin-bottom: 20px; 
    }
    
    .bio-status-title { 
        color: var(--text-primary); 
        font-family: 'Inter', sans-serif; 
        font-size: 0.85rem; 
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 12px; 
    }
    
    .bio-status-desc { 
        font-size: 0.8rem; 
        color: var(--text-secondary); 
        line-height: 1.8; 
    }

    .btn-passkey {
        width: 100%; 
        background: transparent; 
        border: 1px solid var(--glass-border); 
        color: var(--text-primary); 
        padding: 20px; 
        border-radius: 16px; 
        font-family: 'Inter', sans-serif; 
        font-size: 0.75rem; 
        font-weight: 600;
        letter-spacing: 2px; 
        text-transform: uppercase; 
        cursor: pointer; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 15px; 
        transition: all 0.5s var(--awwwards-ease);
    }
    
    .btn-passkey:hover { 
        border-color: var(--gold-premium); 
        color: var(--gold-premium);
        background: rgba(212,175,55,0.05);
        transform: translateY(-3px);
    }

    /* System Messages */
    .sys-msg {
        padding: 20px 30px;
        border-radius: 16px;
        margin-bottom: 50px;
        font-size: 0.85rem;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
    }
    
    .sys-msg.success { border-left: 4px solid var(--success-elegant); color: var(--text-primary); }
    .sys-msg.error { border-left: 4px solid var(--danger-elegant); color: var(--text-primary); }

    textarea.form-input { resize: none; overflow: hidden; min-height: 40px; }
</style>
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
            <div class="status-title">Protokol Keamanan VVIP</div>
            <div class="status-value" id="statusText">Mengkalibrasi optik...</div>
        </div>
    </div>

    <!-- ================= FASE 2: SOVEREIGN DOSSIER ================= -->
    <div class="control-panel" id="controlPanel">
        
        <div class="nav-actions stagger-item">
            <a href="/beranda" class="action-btn cursor-bind"><i class="fa-solid fa-arrow-left-long"></i> Kembali ke Pangkalan</a>
            <a href="/logout" class="action-btn btn-danger cursor-bind"><i class="fa-solid fa-power-off"></i> Putuskan Sesi</a>
        </div>

        <div class="dashboard-header stagger-item">
            <h1 class="dashboard-title">Sovereign Dossier</h1>
            <p class="dashboard-subtitle">Manajemen Kredensial Eksklusif</p>
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
            statusText.innerHTML = `<span style="font-size:0.9rem; color:var(--text-secondary);">Sistem keamanan mendeteksi anomali. Akses Sovereign dikunci. Harap kembali ke Pangkalan.</span><br><br><a href="/beranda" class="action-btn" style="display:inline-flex; width:auto; justify-content:center; border-color:var(--text-primary); color:var(--text-primary); margin: 0 auto;">Kembali</a>`;
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

        const res = await fetch('/biometric/register-options');
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

        const verifyRes = await fetch('/biometric/register-verify', {
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
