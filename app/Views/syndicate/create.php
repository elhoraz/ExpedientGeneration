<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Registrasi Jaringan Bisnis - The Syndicate
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ================= BACKGROUND & STAGE ================= */
    .create-syndicate-container {
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding: 120px 5% 50px;
        background: radial-gradient(circle at top right, rgba(0, 255, 136, 0.05), transparent 40%),
                    radial-gradient(circle at bottom left, rgba(212, 175, 55, 0.05), transparent 40%);
        z-index: 1;
        display: flex; flex-direction: column; align-items: center;
        /* BUG FIX KURSOR: Pastikan pointer-events auto */
        pointer-events: auto !important; 
        cursor: default;
    }

    /* TOMBOL KEMBALI MENGAMBANG */
    .btn-back-grid {
        position: absolute; top: 100px; left: 50px; z-index: 30;
        background: rgba(10, 15, 12, 0.7); backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1); color: #fff;
        padding: 12px 25px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 2px; cursor: pointer;
        transition: all 0.4s ease; display: flex; align-items: center; gap: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-decoration: none;
    }
    .btn-back-grid:hover { background: rgba(212, 175, 55, 0.2); border-color: #d4af37; color: #d4af37; transform: translateY(-3px); }

    /* ================= RAQUETS FORM CONTAINER (BLACK CARD VIP) ================= */
    .form-black-card {
        background: rgba(15, 18, 16, 0.7); backdrop-filter: blur(25px);
        border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 20px;
        width: 100%; max-width: 600px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.8);
        padding: 40px;
        position: relative;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        cursor: default; /* Kursor panah saat miring */
    }

    .form-header { text-align: center; margin-bottom: 30px; }
    .form-title {
        font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #fff;
        font-weight: 900; letter-spacing: 2px; text-transform: uppercase;
        margin-bottom: 5px;
    }
    .form-subtitle { font-family: 'Courier New', monospace; font-size: 0.85rem; color: #d4af37; letter-spacing: 4px; text-transform: uppercase; }

    /* ================= GLASSMORPHISM INPUTS ================= */
    .form-group { margin-bottom: 25px; }
    .form-label {
        font-size: 0.75rem; color: #aaa; text-transform: uppercase; letter-spacing: 3px;
        display: block; margin-bottom: 10px; font-weight: 600;
    }
    .form-input {
        width: 100%; background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px;
        padding: 15px 20px; color: #fff; font-size: 0.95rem; font-family: 'Inter', sans-serif;
        transition: 0.4s ease;
    }
    .form-input:focus {
        outline: none; background: rgba(212, 175, 55, 0.05);
        border-color: #d4af37; box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
    }

    /* Select Dropdown styling */
    select.form-input { color: #888; appearance: none; cursor: pointer; }
    select.form-input:focus { color: #fff; }
    select.form-input option { background: #0f1210; color: #fff; padding: 10px; }

    /* Textarea */
    textarea.form-input { resize: none; min-height: 120px; }

    /* File Upload styling */
    .file-upload-box {
        border: 2px dashed rgba(212, 175, 55, 0.3); border-radius: 8px;
        background: rgba(0, 0, 0, 0.3); padding: 20px; text-align: center;
        transition: 0.3s ease; cursor: pointer; position: relative;
    }
    .file-upload-box:hover { border-color: #d4af37; background: rgba(212, 175, 55, 0.1); }
    .file-hidden { display: none; }
    
    .logo-preview-box {
        width: 100px; height: 100px; border-radius: 10px; background: #0a0a0a;
        margin: 0 auto 15px; overflow: hidden; display: none; align-items: center; justify-content: center;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .logo-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .upload-icon { font-size: 2rem; color: rgba(212, 175, 55, 0.5); margin-bottom: 10px; display: block; }
    .upload-text { font-size: 0.8rem; color: #aaa; letter-spacing: 1px; }

    /* SUBMIT BUTTON VIP */
    .btn-submit-form {
        width: 100%; background: linear-gradient(135deg, #d4af37, #aa8529);
        border: none; color: #000; padding: 18px 25px; border-radius: 50px;
        font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 3px;
        cursor: pointer; transition: 0.4s ease; display: flex; align-items: center; justify-content: center; gap: 12px;
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); margin-top: 20px;
    }
    .btn-submit-form:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.5); }
    .btn-submit-form i { font-size: 1.1rem; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .form-black-card { padding: 30px 20px; max-width: 100%; }
        .form-title { font-size: 1.6rem; }
        .btn-back-grid { top: 70px; left: 15px; right: 15px; justify-content: center; width: auto; }
        .btn-submit-form { font-size: 0.8rem; padding: 15px 20px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="create-syndicate-container">
    
    <a href="<?= base_url('syndicate') ?>" class="btn-back-grid">
        <i class="fa-solid fa-vault"></i> Kembali ke Jaringan
    </a>

    <div class="form-black-card" data-tilt data-tilt-glare data-tilt-max-glare="0.1" data-tilt-scale="1.01">
        
        <div class="form-header">
            <h1 class="form-title">Registrasi Arsip</h1>
            <div class="form-subtitle">The Syndicate Professional Ledger</div>
        </div>

        <?php if(session()->getFlashdata('validation_errors')): ?>
            <div style="background: rgba(255, 51, 102, 0.1); border: 1px solid #ff3366; color: #ff3366; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 0.8rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach(session()->getFlashdata('validation_errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('syndicate/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label class="form-label" for="nama_bisnis">Nama Bisnis / Proyek</label>
                <input type="text" class="form-input" id="nama_bisnis" name="nama_bisnis" placeholder="Contoh: Expedient Coffee Co." value="<?= old('nama_bisnis') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="kategori">Kategori Industri</label>
                <select class="form-input" id="kategori" name="kategori" required>
                    <option value="" disabled selected>Pilih Kategori...</option>
                    <option value="F&B" <?= old('kategori') == 'F&B' ? 'selected' : '' ?>>Kuliner (F&B)</option>
                    <option value="Teknologi" <?= old('kategori') == 'Teknologi' ? 'selected' : '' ?>>Teknologi</option>
                    <option value="Jasa" <?= old('kategori') == 'Jasa' ? 'selected' : '' ?>>Jasa & Agensi</option>
                    <option value="Kreatif" <?= old('kategori') == 'Kreatif' ? 'selected' : '' ?>>Kreatif</option>
                    <option value="Retail" <?= old('kategori') == 'Retail' ? 'selected' : '' ?>>Retail</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="deskripsi">Deskripsi Singkat (Max 200 karakter)</label>
                <textarea class="form-input" id="deskripsi" name="deskripsi" maxlength="200" placeholder="Jelaskan apa yang Anda tawarkan kepada agen lain..." required><?= old('deskripsi') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="link_url">Tautan Website / Portofolio (Opsional)</label>
                <input type="url" class="form-input" id="link_url" name="link_url" placeholder="https://www.bisnisanda.com" value="<?= old('link_url') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Logo Bisnis / Gambar Utama</label>
                <div class="file-upload-box" id="logoUploadBox">
                    <div class="logo-preview-box" id="logoPreviewBox">
                        <img src="" id="logoPreviewImg" alt="Preview">
                    </div>
                    <i class="fa-solid fa-cloud-arrow-up upload-icon" id="uploadIcon"></i>
                    <p class="upload-text" id="uploadText">Klik atau Tarik File untuk Unggah Logo<br><span style="color:#666; font-size:0.7rem;">(JPG/PNG, Max 2MB)</span></p>
                    <input type="file" class="file-hidden" id="logo_bisnis" name="logo_bisnis" accept="image/jpeg,image/png">
                </div>
            </div>

            <button type="submit" class="btn-submit-form hover-trigger">
                <i class="fa-solid fa-shield-halved"></i> Daftarkan ke Ledger Pusat
            </button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        
        // SCRIPT LIVE PREVIEW LOGO
        const uploadBox = document.getElementById('logoUploadBox');
        const fileInput = document.getElementById('logo_bisnis');
        const previewBox = document.getElementById('logoPreviewBox');
        const previewImg = document.getElementById('logoPreviewImg');
        const uploadIcon = document.getElementById('uploadIcon');
        const uploadText = document.getElementById('uploadText');

        // Klik pada box akan trigger input file asli
        uploadBox.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Pastikan file adalah gambar
                if (!file.type.match('image.*')) {
                    window.showToast("Akses Ditolak", "Maaf Kapten, hanya file gambar yang diizinkan!", true);
                    this.value = '';
                    return;
                }

                // Cek ukuran (Max 2MB = 2 * 1024 * 1024)
                if (file.size > 2 * 1024 * 1024) {
                    window.showToast("Akses Ditolak", "File terlalu besar (Maksimal 2MB).", true);
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    previewImg.src = reader.result;
                    previewBox.style.display = 'flex';
                    uploadIcon.style.display = 'none';
                    uploadText.style.color = '#fff';
                    uploadText.innerHTML = `<i class="fa-solid fa-check-circle" style="color:#00ff88"></i> Logo Terdeteksi<br><span style="color:#aaa; font-size:0.7rem">${file.name}</span>`;
                });
                reader.readAsDataURL(file);
            }
        });

        // Pastikan kursor pointer muncul dengan benar di HP saat box di-hover
        uploadBox.style.cursor = 'pointer';
    });
</script>
<?= $this->endSection() ?>