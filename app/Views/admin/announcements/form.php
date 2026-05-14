<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .admin-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 800px;
        margin: 0 auto;
    }
    .admin-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .admin-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3vw, 2.5rem);
        color: #d4af37;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 10px;
    }
    .admin-subtitle {
        color: var(--text-secondary);
        font-size: 0.85rem;
        letter-spacing: 2px;
    }

    .form-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 40px;
    }

    .form-group {
        margin-bottom: 30px;
    }
    .form-label {
        display: block;
        color: #d4af37;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-family: 'Courier New', monospace;
    }
    .form-input, .form-textarea, .form-select {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
        padding: 15px 20px;
        font-family: 'Inter', sans-serif;
        transition: 0.3s;
        outline: none;
        box-sizing: border-box;
    }
    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: rgba(212,175,55,0.6);
        box-shadow: 0 0 20px rgba(212,175,55,0.1);
    }
    .form-textarea {
        resize: vertical;
        min-height: 200px;
        line-height: 1.7;
    }
    .form-select {
        appearance: none;
        cursor: pointer;
    }
    .form-select option {
        background: #0a110e;
        color: #fff;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }
    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #d4af37;
        cursor: pointer;
    }
    .checkbox-label {
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    .btn-submit {
        flex: 1;
        background: linear-gradient(135deg, rgba(212,175,55,0.3), rgba(212,175,55,0.1));
        border: 1px solid rgba(212,175,55,0.5);
        color: #d4af37;
        padding: 15px;
        border-radius: 10px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-submit:hover {
        background: #d4af37;
        color: #030504;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(212,175,55,0.3);
    }
    .btn-cancel {
        padding: 15px 30px;
        background: transparent;
        border: 1px solid var(--glass-border);
        color: var(--text-secondary);
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.85rem;
        text-decoration: none;
        text-align: center;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
    }
    .btn-cancel:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="admin-wrapper">
    <div class="admin-header">
        <h1 class="admin-title"><?= $announcement ? 'Edit Pengumuman' : 'Buat Pengumuman' ?></h1>
        <p class="admin-subtitle">Publikasikan informasi untuk seluruh entitas Expedient</p>
    </div>

    <div class="form-panel">
        <form action="<?= $announcement ? '/admin/announcements/update/' . $announcement['id'] : '/admin/announcements/store' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-input" placeholder="Masukkan judul pengumuman..." 
                       value="<?= esc($announcement['title'] ?? old('title')) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Konten</label>
                <textarea name="content" class="form-textarea" placeholder="Tulis isi pengumuman secara detail..." required><?= esc($announcement['content'] ?? old('content')) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select" required>
                        <option value="berita" <?= ($announcement['category'] ?? old('category')) === 'berita' ? 'selected' : '' ?>>Berita</option>
                        <option value="pengumuman" <?= ($announcement['category'] ?? old('category')) === 'pengumuman' ? 'selected' : '' ?>>Pengumuman</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Publikasi</label>
                    <input type="date" name="published_at" class="form-input" 
                           value="<?= esc($announcement['published_at'] ?? old('published_at') ?? date('Y-m-d')) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-group">
                    <input type="checkbox" name="is_pinned" value="1" 
                           <?= ($announcement['is_pinned'] ?? old('is_pinned')) ? 'checked' : '' ?>>
                    <span class="checkbox-label">Sematkan di atas (Pinned)</span>
                </label>
            </div>

            <div class="form-actions">
                <a href="/admin/announcements" class="btn-cancel hover-trigger">Batal</a>
                <button type="submit" class="btn-submit hover-trigger">
                    <i class="fa-solid fa-paper-plane" style="margin-right:8px;"></i>
                    <?= $announcement ? 'Perbarui' : 'Publikasikan' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
