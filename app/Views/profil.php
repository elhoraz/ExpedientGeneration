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
            <div class="status-title"><?= cms_text('profil_scan_title', 'Verifikasi Keamanan') ?></div>
            <div class="status-value" id="statusText"><?= cms_text('profil_scan_subtitle', 'Mengkalibrasi optik...') ?></div>
        </div>
    </div>

    <!-- ================= FASE 2: PROFIL EKSKLUSIF ================= -->
    <div class="control-panel" id="controlPanel">
        
        <div class="nav-actions stagger-item">
            <a href="/beranda" class="action-btn cursor-bind"><i class="fa-solid fa-arrow-left-long"></i> <?= cms_text('profil_btn_back', 'Kembali ke Beranda') ?></a>
            <a href="/chat" class="action-btn cursor-bind" style="background: rgba(212,175,55,0.1); border-color: rgba(212,175,55,0.3); color: #d4af37;"><i class="fa-solid fa-envelope"></i> <?= cms_text('profil_btn_msg', 'Kotak Pesan') ?></a>
            <a href="/logout" class="action-btn btn-danger cursor-bind"><i class="fa-solid fa-power-off"></i> <?= cms_text('profil_btn_logout', 'Keluar') ?></a>
        </div>

        <div class="dashboard-header stagger-item">
            <h1 class="dashboard-title"><?= cms_text('profil_title', 'Profil Eksklusif') ?></h1>
            <p class="dashboard-subtitle"><?= cms_text('profil_subtitle', 'Kelola Data Pribadi Anda') ?></p>
            
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
                <h2 class="panel-title"><i class="fa-regular fa-id-card"></i> <?= cms_text('profil_panel_id', 'Identitas Personal') ?></h2>
                
                <form action="/profil/update" method="POST" id="formUpdateProfile">
                    <?= csrf_field() ?>
                    <input type="hidden" name="foto_profil_base64" id="fotoBase64Value">

                    <!-- Area Upload Foto -->
                    <div class="photo-upload-wrapper">
                        <div class="magnetic-avatar cursor-bind" id="magAvatar">
                            <img src="<?= !empty($user['foto_profil']) ? base_url('uploads/profiles/' . $user['foto_profil']) : 'https://ui-avatars.com/api/?name='.urlencode($user['nama_panggilan']).'&background=d4af37&color=000' ?>" class="avatar-preview" id="avatarPreview" alt="Profil">
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px;"><?= cms_text('profil_lbl_potret', 'Potret Resmi') ?></div>
                            <div class="magnetic-btn-wrap" id="magBtnWrap">
                                <label class="upload-btn-ui cursor-bind" id="magBtn" for="inputFileImg">
                                    <?= cms_text('profil_btn_potret', 'Pilih Potret') ?>
                                </label>
                            </div>
                            <input type="file" id="inputFileImg" accept="image/*" style="display:none;">
                            <div style="font-size: 0.65rem; color: var(--text-secondary); margin-top: 5px;"><?= cms_text('profil_desc_potret', 'Maksimum resolusi HD disarankan.') ?></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="nama_panggilan" class="form-input" id="inp_panggilan" placeholder=" " value="<?= esc($user['nama_panggilan']) ?>" required>
                            <label class="form-label" for="inp_panggilan"><?= cms_text('profil_lbl_panggilan', 'Nama Sandi / Panggilan') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="number" name="no_whatsapp" class="form-input" id="inp_wa" placeholder=" " value="<?= esc($user['no_whatsapp']) ?>" required>
                            <label class="form-label" for="inp_wa"><?= cms_text('profil_lbl_wa', 'Nomor Kontak (WhatsApp)') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                    </div>

                    <!-- WA Notification Toggle -->
                    <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(37,211,102,0.05); border: 1px solid rgba(37,211,102,0.2); border-radius: 16px; padding: 16px 20px; margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(37,211,102,0.15); display: flex; align-items: center; justify-content: center; color: #25d366; font-size: 1rem; flex-shrink: 0;">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-primary); letter-spacing: 0.5px;">Notifikasi WhatsApp</div>
                                <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 2px;">Event baru, pengumuman, & alumni bergabung</div>
                            </div>
                        </div>
                        <label class="wa-toggle-switch" style="position: relative; display: inline-block; width: 48px; height: 26px; flex-shrink: 0; cursor: pointer;">
                            <input type="checkbox" name="wa_notif_opt_in" id="waToggle" value="1" style="opacity: 0; width: 0; height: 0;" <?= !empty($user['wa_notif_opt_in']) ? 'checked' : '' ?>>
                            <span style="position: absolute; inset: 0; background: <?= !empty($user['wa_notif_opt_in']) ? '#25d366' : 'rgba(255,255,255,0.1)' ?>; border-radius: 26px; transition: 0.3s; border: 1px solid rgba(255,255,255,0.1);" id="waToggleTrack">
                                <span style="position: absolute; content: ''; height: 20px; width: 20px; left: 3px; bottom: 2px; background: white; border-radius: 50%; transition: 0.3s; transform: <?= !empty($user['wa_notif_opt_in']) ? 'translateX(22px)' : 'translateX(0)' ?>;" id="waToggleThumb"></span>
                            </span>
                        </label>
                    </div>

                    <div class="form-group">
                        <input type="text" name="nama_lengkap" class="form-input" id="inp_lengkap" placeholder=" " value="<?= esc($user['nama_lengkap']) ?>" required>
                        <label class="form-label" for="inp_lengkap"><?= cms_text('profil_lbl_lengkap', 'Nama Lengkap Resmi') ?></label>
                        <div class="liquid-line"></div>
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" class="form-input" id="inp_email" placeholder=" " value="<?= esc($user['email']) ?>" required>
                        <label class="form-label" for="inp_email"><?= cms_text('profil_lbl_email', 'Alamat Surel Utama') ?></label>
                        <div class="liquid-line"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="akun_ig" class="form-input" id="inp_ig" placeholder=" " value="<?= esc($user['akun_ig'] ?? '') ?>">
                            <label class="form-label" for="inp_ig"><?= cms_text('profil_lbl_ig', 'Instagram (Opsional)') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="akun_tiktok" class="form-input" id="inp_tt" placeholder=" " value="<?= esc($user['akun_tiktok'] ?? '') ?>">
                            <label class="form-label" for="inp_tt"><?= cms_text('profil_lbl_tt', 'TikTok (Opsional)') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="motivasi_hidup" class="form-input" id="inp_motivasi" placeholder=" " oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"><?= esc($user['motivasi_hidup'] ?? '') ?></textarea>
                        <label class="form-label" for="inp_motivasi"><?= cms_text('profil_lbl_visi', 'Visi & Motivasi') ?></label>
                        <div class="liquid-line"></div>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="cita_cita" class="form-input" id="inp_cita" placeholder=" " value="<?= esc($user['cita_cita'] ?? '') ?>">
                        <label class="form-label" for="inp_cita"><?= cms_text('profil_lbl_target', 'Target Pencapaian') ?></label>
                        <div class="liquid-line"></div>
                    </div>

                    <button type="submit" class="btn-submit cursor-bind">
                        <?= cms_text('profil_btn_save', 'Simpan Perubahan') ?>
                    </button>
                </form>
            </div>

            <!-- KOLOM KANAN: PROTOKOL KEAMANAN -->
            <div class="premium-panel stagger-item parallax-card">
                <h2 class="panel-title"><i class="fa-solid fa-fingerprint"></i> <?= cms_text('profil_panel_sec', 'Akses & Keamanan') ?></h2>
                
                <div class="bio-status-box cursor-bind">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div class="bio-status-title"><?= cms_text('profil_sec_title', 'Keamanan Visual Aktif') ?></div>
                    <div class="bio-status-desc"><?= cms_text('profil_sec_desc', 'Wajah Anda menjadi kunci tunggal untuk membedah data Sovereign ini. Tingkat akurasi pemindaian telah dimaksimalkan.') ?></div>
                </div>

                <div style="font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin: 40px 0 20px 0;"><?= cms_text('profil_lbl_passkey', 'Kredensial Fisik (Passkey)') ?></div>
                
                <?php if(!empty($user['webauthn_credential_id'])): ?>
                    <div style="color: var(--text-primary); font-size: 0.8rem; padding: 25px; border: 1px solid var(--glass-border); border-radius: 20px; text-align: center; line-height: 1.6;">
                        <i class="fa-solid fa-lock" style="font-size:1.5rem; margin-bottom:15px; color: var(--success-elegant);"></i><br>
                        <?= cms_text('profil_msg_passkey', 'Perangkat ini telah terkunci dan disahkan sebagai Token Keamanan Fisik.') ?>
                    </div>
                <?php else: ?>
                    <div id="bioOptionsBox">
                        <button class="btn-passkey cursor-bind" onclick="startBiometricEnrollment()">
                            <i class="fa-solid fa-key" style="margin-right: 5px;"></i> <?= cms_text('profil_btn_passkey', 'Autentikasi Perangkat Ini') ?>
                        </button>
                    </div>
                <?php endif; ?>
                
                <div id="bioStatus" style="margin-top: 20px; font-size: 0.75rem; color: var(--text-secondary); text-align: center; line-height: 1.6;">
                    <?= cms_text('profil_desc_bio', 'Gunakan biometrik bawaan (Touch ID/Face ID) pada gawai Anda sebagai otentikasi lapis kedua tanpa sandi.') ?>
                </div>
                
                <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--glass-border);">
                    <div style="font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;"><i class="fa-solid fa-key" style="margin-right: 8px;"></i><?= cms_text('profil_lbl_change_pw', 'Ubah Kata Sandi') ?></div>
                    
                    <form action="/profil/change-password" method="POST">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <input type="password" name="current_password" class="form-input" id="inp_curpass" placeholder=" " required>
                            <label class="form-label" for="inp_curpass"><?= cms_text('profil_lbl_old_pw', 'Kata Sandi Lama') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="password" name="new_password" class="form-input" id="inp_newpass" placeholder=" " required minlength="8">
                            <label class="form-label" for="inp_newpass"><?= cms_text('profil_lbl_new_pw', 'Kata Sandi Baru (min. 8)') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" class="form-input" id="inp_cfmpass" placeholder=" " required>
                            <label class="form-label" for="inp_cfmpass"><?= cms_text('profil_lbl_confirm_pw', 'Konfirmasi Sandi Baru') ?></label>
                            <div class="liquid-line"></div>
                        </div>
                        <button type="submit" class="btn-submit cursor-bind" style="width: 100%; font-size: 0.8rem; padding: 12px;">
                            <i class="fa-solid fa-shield-halved" style="margin-right: 8px;"></i> <?= cms_text('profil_btn_update_pw', 'Perbarui Kata Sandi') ?>
                        </button>
                    </form>
                </div>

                <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--glass-border); text-align: center;">
                    <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 2px;"><?= cms_text('profil_lbl_exec', 'Identitas Eksekutif') ?></div>
                    <a href="/sovereign" class="cursor-bind" style="color:var(--gold-premium); text-decoration:none; font-size:0.85rem; display:inline-block; margin-top:15px; font-weight: 600; letter-spacing: 2px;"><i class="fa-solid fa-cube" style="margin-right: 8px;"></i> <?= cms_text('profil_btn_sov', 'BUKA SOVEREIGN ID 5D') ?></a>
                </div>

                <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid rgba(255, 51, 102, 0.2); text-align: center;">
                    <div style="font-size: 0.75rem; color: #ff3366; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;"><i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i><?= cms_text('profil_lbl_danger', 'Zona Berbahaya') ?></div>
                    <form action="/profil/delete-account" method="POST" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Apakah Anda yakin ingin menonaktifkan akun ini selamanya? Proses ini tidak dapat dibatalkan dengan mudah.').then(res => { if(res) this.submit(); });">
                        <?= csrf_field() ?>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <input type="password" name="password_delete" class="form-input" id="inp_delpass" placeholder=" " required style="border-color: rgba(255, 51, 102, 0.3);">
                            <label class="form-label" for="inp_delpass" style="color: #ff3366;"><?= cms_text('profil_lbl_del_pw', 'Konfirmasi Sandi untuk Hapus') ?></label>
                            <div class="liquid-line" style="background: linear-gradient(90deg, transparent, #ff3366, transparent);"></div>
                        </div>
                        <button type="submit" class="btn-submit cursor-bind" style="background: rgba(255, 51, 102, 0.1); color: #ff3366; border: 1px solid #ff3366; width: 100%; font-size: 0.8rem; padding: 12px;">
                            <i class="fa-solid fa-user-xmark" style="margin-right: 8px;"></i> <?= cms_text('profil_btn_del', 'Nonaktifkan Akun') ?>
                        </button>
                    </form>
                </div>

                <!-- Easter Egg / Hidden Admin Access -->
                <div style="margin-top: 60px; font-size: 0.6rem; color: rgba(255,255,255,0.15); text-align: justify; line-height: 1.8; font-family: 'Inter', sans-serif;">
                    Expedient bermakna kepraktisan dan ketepatan dalam bertindak. Ia adalah filosofi tentang bagaimana mencapai tujuan dengan cara yang paling efisien dan bijaksana. Generasi yang membawa nama ini tidak terjebak pada hal-hal yang rumit tanpa alasan; mereka berfokus pada apa yang benar-benar bermakna dan membawa kebaikan bersama. Kemampuan untuk menempatkan segala sesuatu pada proporsi yang tepat adalah bentuk kedewasaan. Pada akhirnya, keindahan dari sebuah perjalanan terletak pada kesederhanaan niat dan keyakinan <a href="/admin/dashboard" style="color: inherit; text-decoration: none; cursor: default; outline: none;" class="cursor-bind">utuh</a> untuk saling melengkapi di setiap langkah.
                </div>

            </div>

        </div>
    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

<script>
    window.dbFaceDataRaw = <?= empty($face_data_db) || $face_data_db === 'null' ? 'null' : $face_data_db ?>;
    window.isAdmin = false;

    // ── WA Toggle Animation ──
    const waToggle = document.getElementById('waToggle');
    const waTrack  = document.getElementById('waToggleTrack');
    const waThumb  = document.getElementById('waToggleThumb');
    if (waToggle) {
        waToggle.addEventListener('change', function () {
            if (this.checked) {
                waTrack.style.background = '#25d366';
                waThumb.style.transform  = 'translateX(22px)';
            } else {
                waTrack.style.background = 'rgba(255,255,255,0.1)';
                waThumb.style.transform  = 'translateX(0)';
            }
        });
    }
</script>
<script src="/assets/js/profil.js"></script>
<?= $this->endSection() ?>
