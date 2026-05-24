<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Manajemen Konten (CMS)
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .cms-wrapper {
        padding: clamp(80px, 15vh, 120px) 20px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .cms-header { text-align: center; margin-bottom: 40px; }
    .cms-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4vw, 3rem); color: #00ff88; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 5px; text-shadow: 0 0 20px rgba(0, 255, 136, 0.4); }
    .cms-subtitle { color: var(--text-secondary); font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; }

    /* Tabs Styling */
    .cms-tabs { display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .cms-tab-btn { background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: var(--text-secondary); padding: 10px 20px; border-radius: 8px 8px 0 0; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; white-space: nowrap; }
    .cms-tab-btn:hover { background: rgba(0,255,136,0.1); color: #00ff88; }
    .cms-tab-btn.active { background: rgba(0,255,136,0.15); border-color: #00ff88; border-bottom: 2px solid #00ff88; color: #00ff88; font-weight: bold; }
    .cms-tab-content { display: none; animation: fadeIn 0.4s ease-out; }
    .cms-tab-content.active { display: block; }

    .cms-panel { background: var(--glass-surface); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 0 16px 16px 16px; overflow: hidden; }
    
    .cms-table-wrapper { width: 100%; overflow-x: auto; }
    .cms-table { width: 100%; border-collapse: collapse; text-align: left; }
    .cms-table th { background: rgba(0,0,0,0.5); color: var(--text-secondary); padding: 15px 20px; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; border-bottom: 1px solid var(--glass-border); }
    .cms-table td { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-primary); vertical-align: middle; }
    .cms-table tr:hover { background: rgba(255,255,255,0.02); }
    .cms-table tr:last-child td { border-bottom: none; }

    .key-badge { background: rgba(0,0,0,0.4); padding: 5px 10px; border-radius: 6px; font-family: monospace; color: #d4af37; border: 1px solid rgba(212,175,55,0.2); }
    .type-badge { font-size: 0.7rem; padding: 4px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; }
    .type-image { background: rgba(0, 191, 255, 0.1); color: #00bfff; border: 1px solid rgba(0, 191, 255, 0.3); }
    .type-html { background: rgba(255, 165, 0, 0.1); color: #ffa500; border: 1px solid rgba(255, 165, 0, 0.3); }
    .type-text { background: rgba(255, 255, 255, 0.05); color: #ccc; border: 1px solid rgba(255, 255, 255, 0.1); }

    .btn-edit { background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), transparent); border: 1px solid rgba(0, 255, 136, 0.4); color: #00ff88; padding: 8px 15px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 0.8rem; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-edit:hover { background: rgba(0, 255, 136, 0.2); box-shadow: 0 0 15px rgba(0, 255, 136, 0.3); color: #00ff88; }
    .btn-danger { background: linear-gradient(135deg, rgba(255, 51, 102, 0.1), transparent); border: 1px solid rgba(255, 51, 102, 0.4); color: #ff3366; padding: 8px 15px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 0.8rem; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-danger:hover { background: rgba(255, 51, 102, 0.2); box-shadow: 0 0 15px rgba(255, 51, 102, 0.3); color: #ff3366; }
    .btn-add { background: linear-gradient(135deg, rgba(0, 191, 255, 0.1), transparent); border: 1px solid rgba(0, 191, 255, 0.4); color: #00bfff; padding: 12px 20px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 0.85rem; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; margin: 20px; }
    .btn-add:hover { background: rgba(0, 191, 255, 0.2); box-shadow: 0 0 15px rgba(0, 191, 255, 0.3); color: #00bfff; transform: translateY(-2px); }

    /* Modal Styles */
    .cms-modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); backdrop-filter: blur(10px); display: none; justify-content: center; align-items: center; z-index: 9999; opacity: 0; transition: opacity 0.3s; }
    .cms-modal-overlay.active { display: flex; opacity: 1; }
    .cms-modal { background: var(--bg-base); border: 1px solid var(--glass-border); border-radius: 20px; width: 90%; max-width: 600px; padding: 30px; position: relative; transform: translateY(20px); transition: transform 0.3s; box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
    .cms-modal-overlay.active .cms-modal { transform: translateY(0); }
    
    .modal-close { position: absolute; top: 20px; right: 20px; background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; transition: 0.3s; padding: 5px 10px; border-radius: 50%; }
    .modal-close:hover { color: #ff3366; background: rgba(255,51,102,0.1); }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; }
    .form-control { width: 100%; padding: 12px; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary); border-radius: 8px; font-family: 'Inter', sans-serif; transition: 0.3s; }
    .form-control:focus { outline: none; border-color: #00ff88; box-shadow: 0 0 10px rgba(0, 255, 136, 0.2); }
    .form-control-file { width: 100%; padding: 10px; background: rgba(0,0,0,0.3); border: 1px dashed rgba(255,255,255,0.2); border-radius: 8px; color: var(--text-primary); margin-bottom: 10px; cursor: pointer; font-size: 0.85rem; }

    .btn-submit { width: 100%; padding: 15px; background: linear-gradient(135deg, #00ff88, #008844); border: none; border-radius: 10px; color: #000; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: 0.3s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,255,136,0.3); }

    .content-preview-img { max-height: 60px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); object-fit: contain; background: #000; }
    .content-preview-text { max-height: 80px; overflow-y: auto; font-size: 0.85rem; color: #aaa; line-height: 1.6; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); }

    .content-preview-text::-webkit-scrollbar { width: 6px; }
    .content-preview-text::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .content-preview-text::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="cms-wrapper">
    <div class="nav-actions reveal-up" style="margin-bottom: 20px;">
        <a href="/admin/dashboard" class="action-btn cursor-bind" style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.05); color:var(--text-secondary); padding:8px 15px; border-radius:20px; text-decoration:none; font-size:0.8rem; border:1px solid rgba(255,255,255,0.1);"><i class="fa-solid fa-arrow-left-long"></i> Command Center</a>
    </div>

    <div class="cms-header reveal-up">
        <h1 class="cms-title">Manajemen Konten</h1>
        <p class="cms-subtitle">Pusat Modifikasi Aset & Teks Global</p>
    </div>

    <div class="cms-tabs reveal-up">
        <?php $first = true; foreach ($groupedContents as $prefix => $items): ?>
            <button class="cms-tab-btn <?= $first ? 'active' : '' ?>" onclick="switchTab('tab-<?= esc($prefix) ?>', this)">Konten <?= esc($prefix) ?></button>
        <?php $first = false; endforeach; ?>
        <button class="cms-tab-btn <?= empty($groupedContents) ? 'active' : '' ?>" onclick="switchTab('tab-gallery', this)"><i class="fa-solid fa-images"></i> Galeri Beranda</button>
    </div>

    <div class="cms-panel reveal-up" style="transition-delay: 0.1s;">
        
        <!-- CMS Key-Value Tabs -->
        <?php $first = true; foreach ($groupedContents as $prefix => $items): ?>
        <div id="tab-<?= esc($prefix) ?>" class="cms-tab-content <?= $first ? 'active' : '' ?>">
            <div class="cms-table-wrapper">
                <table class="cms-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Identifier (Key)</th>
                            <th style="width: 15%;">Format</th>
                            <th style="width: 50%;">Nilai Saat Ini</th>
                            <th style="width: 15%;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $c): ?>
                        <tr>
                            <td>
                                <span class="key-badge"><?= esc($c['content_key']) ?></span>
                            </td>
                            <td>
                                <?php if($c['content_type'] == 'image'): ?>
                                    <span class="type-badge type-image"><i class="fa-solid fa-image"></i> Gambar</span>
                                <?php elseif($c['content_type'] == 'html'): ?>
                                    <span class="type-badge type-html"><i class="fa-solid fa-code"></i> HTML</span>
                                <?php else: ?>
                                    <span class="type-badge type-text"><i class="fa-solid fa-font"></i> Teks</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($c['content_type'] == 'image'): ?>
                                    <img src="<?= esc($c['content_value']) ?>" class="content-preview-img" alt="CMS Image">
                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px; word-break: break-all;"><?= esc($c['content_value']) ?></div>
                                <?php else: ?>
                                    <div class="content-preview-text">
                                        <?= esc($c['content_value']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn-edit hover-trigger" onclick="openCmsModal(<?= $c['id'] ?>)">
                                    <i class="fa-solid fa-pen-nib"></i> Modifikasi
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $first = false; endforeach; ?>

        <!-- Beranda Gallery Tab -->
        <div id="tab-gallery" class="cms-tab-content <?= empty($groupedContents) ? 'active' : '' ?>">
            <button class="btn-add" onclick="openAddGalleryModal()"><i class="fa-solid fa-plus"></i> Tambah Gambar Baru</button>
            <div class="cms-table-wrapper">
                <table class="cms-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 20%;">Gambar</th>
                            <th style="width: 50%;">Keterangan (Caption)</th>
                            <th style="width: 20%;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($galleries)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-secondary);">Belum ada gambar di Galeri Beranda.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($galleries as $g): ?>
                            <tr>
                                <td style="color:var(--text-secondary); font-family:monospace;">#<?= $g['id'] ?></td>
                                <td><img src="<?= esc($g['image_url']) ?>" class="content-preview-img" alt="Gallery"></td>
                                <td><?= esc($g['caption']) ?></td>
                                <td>
                                    <form action="/admin/cms/gallery/delete/<?= $g['id'] ?>" method="POST" onsubmit="return confirm('Hapus gambar ini dari galeri?');" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-danger hover-trigger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Modifikasi Konten (Iterasi dari seluruh groupedContents) -->
<?php foreach ($groupedContents as $prefix => $items): ?>
    <?php foreach ($items as $c): ?>
    <div class="cms-modal-overlay" id="modal-<?= $c['id'] ?>">
        <div class="cms-modal">
            <button class="modal-close" onclick="closeCmsModal(<?= $c['id'] ?>)"><i class="fa-solid fa-xmark"></i></button>
            <h3 style="color: var(--text-primary); font-family: 'Playfair Display', serif; margin-bottom: 5px;">Modifikasi Konten</h3>
            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 25px;">Key: <span class="key-badge" style="padding: 2px 6px; font-size: 0.7rem;"><?= esc($c['content_key']) ?></span></div>
            
            <form action="/admin/cms/update" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                
                <div class="form-group">
                    <label class="form-label">Nilai Modifikasi</label>
                    <?php if($c['content_type'] == 'image'): ?>
                        <div style="background: rgba(0,191,255,0.05); padding: 15px; border-radius: 8px; border: 1px solid rgba(0,191,255,0.2); margin-bottom: 15px;">
                            <div style="font-size: 0.75rem; color: #00bfff; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;"><i class="fa-solid fa-upload"></i> Unggah Aset Baru</div>
                            <input type="file" name="image_file" class="form-control-file" accept="image/*">
                        </div>
                        <div style="text-align: center; margin-bottom: 15px; color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;">Atau Tautkan URL Eksternal</div>
                        <input type="text" name="content_value" class="form-control" value="<?= esc($c['content_value']) ?>" placeholder="https://...">
                    <?php elseif($c['content_type'] == 'html'): ?>
                        <textarea name="content_value" class="form-control" rows="8" required style="font-family: monospace; font-size: 0.85rem;"><?= esc($c['content_value']) ?></textarea>
                    <?php else: ?>
                        <textarea name="content_value" class="form-control" rows="5" required><?= esc($c['content_value']) ?></textarea>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn-submit hover-trigger">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- Modal Tambah Galeri Beranda -->
<div class="cms-modal-overlay" id="modal-add-gallery">
    <div class="cms-modal">
        <button class="modal-close" onclick="closeAddGalleryModal()"><i class="fa-solid fa-xmark"></i></button>
        <h3 style="color: #00bfff; font-family: 'Playfair Display', serif; margin-bottom: 5px;">Tambah Gambar Galeri</h3>
        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 25px;">Gambar ini akan ditampilkan pada sesi Lorong Kenangan di Beranda.</p>
        
        <form action="/admin/cms/gallery/add" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">File Gambar</label>
                <div style="background: rgba(0,191,255,0.05); padding: 15px; border-radius: 8px; border: 1px solid rgba(0,191,255,0.2);">
                    <input type="file" name="image_file" class="form-control-file" accept="image/*" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan (Caption)</label>
                <input type="text" name="caption" class="form-control" placeholder="Tuliskan keterangan singkat..." required>
            </div>
            <button type="submit" class="btn-submit hover-trigger" style="background: linear-gradient(135deg, #00bfff, #0077ff); color: #fff;">Unggah Gambar</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function switchTab(tabId, btnElement) {
        document.querySelectorAll('.cms-tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.cms-tab-btn').forEach(btn => btn.classList.remove('active'));
        
        document.getElementById(tabId).classList.add('active');
        btnElement.classList.add('active');
    }

    function openCmsModal(id) {
        const overlay = document.getElementById('modal-' + id);
        if (overlay) {
            overlay.style.display = 'flex';
            void overlay.offsetWidth;
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeCmsModal(id) {
        const overlay = document.getElementById('modal-' + id);
        if (overlay) {
            overlay.classList.remove('active');
            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
    }

    function openAddGalleryModal() {
        const overlay = document.getElementById('modal-add-gallery');
        if (overlay) {
            overlay.style.display = 'flex';
            void overlay.offsetWidth;
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeAddGalleryModal() {
        const overlay = document.getElementById('modal-add-gallery');
        if (overlay) {
            overlay.classList.remove('active');
            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Close on overlay click
    document.querySelectorAll('.cms-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                if (this.id === 'modal-add-gallery') {
                    closeAddGalleryModal();
                } else {
                    const id = this.id.split('-')[1];
                    closeCmsModal(id);
                }
            }
        });
    });
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.cms-modal-overlay.active').forEach(overlay => {
                if (overlay.id === 'modal-add-gallery') {
                    closeAddGalleryModal();
                } else {
                    const id = overlay.id.split('-')[1];
                    closeCmsModal(id);
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
