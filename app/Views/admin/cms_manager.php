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
    .cms-table td { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-primary); vertical-align: middle; transition: 0.3s; }
    .cms-table tr:hover { background: rgba(255,255,255,0.02); }
    .cms-table tr:last-child td { border-bottom: none; }
    .cms-table tr.draft-modified { background: rgba(0, 255, 136, 0.05); }

    .key-badge { background: rgba(0,0,0,0.4); padding: 5px 10px; border-radius: 6px; font-family: monospace; color: #d4af37; border: 1px solid rgba(212,175,55,0.2); }
    .type-badge { font-size: 0.7rem; padding: 4px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; }
    .type-image { background: rgba(0, 191, 255, 0.1); color: #00bfff; border: 1px solid rgba(0, 191, 255, 0.3); }
    .type-html { background: rgba(255, 165, 0, 0.1); color: #ffa500; border: 1px solid rgba(255, 165, 0, 0.3); }
    .type-text { background: rgba(255, 255, 255, 0.05); color: #ccc; border: 1px solid rgba(255, 255, 255, 0.1); }
    .draft-badge { display:none; font-size: 0.6rem; background:#00ff88; color:#000; padding:2px 6px; border-radius:10px; font-weight:bold; margin-left:10px; vertical-align:middle; text-transform:uppercase; letter-spacing:1px; }

    .btn-edit { background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), transparent); border: 1px solid rgba(0, 255, 136, 0.4); color: #00ff88; padding: 8px 15px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 0.8rem; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-edit:hover { background: rgba(0, 255, 136, 0.2); box-shadow: 0 0 15px rgba(0, 255, 136, 0.3); color: #00ff88; }
    
    .btn-danger-sm { background: transparent; border: 1px solid rgba(255, 51, 102, 0.4); color: #ff3366; padding: 6px 10px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 0.7rem; margin-top:10px; display:inline-block; }
    .btn-danger-sm:hover { background: rgba(255, 51, 102, 0.2); }

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

    /* FLOATING BATCH SAVE BUTTON */
    .batch-save-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, #d4af37, #b8860b);
        color: #000;
        padding: 15px 25px;
        border-radius: 30px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        box-shadow: 0 10px 30px rgba(212,175,55,0.5);
        cursor: pointer;
        z-index: 1000;
        display: none;
        align-items: center;
        gap: 10px;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform: translateY(100px);
    }
    .batch-save-widget.visible {
        display: flex;
        transform: translateY(0);
    }
    .batch-save-widget:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 40px rgba(212,175,55,0.7);
    }
    .batch-save-count {
        background: #000;
        color: #d4af37;
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: bold;
    }
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
    </div>

    <div class="cms-panel reveal-up" style="transition-delay: 0.1s;">
        
        <?php $first = true; foreach ($groupedContents as $prefix => $items): ?>
        <div id="tab-<?= esc($prefix) ?>" class="cms-tab-content <?= $first ? 'active' : '' ?>">
            <button class="btn-add" onclick="openAddKeyModal('<?= esc($prefix) ?>')"><i class="fa-solid fa-plus"></i> Tambah Kunci Konten Baru</button>
            <div class="cms-table-wrapper">
                <table class="cms-table" id="table-<?= esc($prefix) ?>">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Identifier (Key)</th>
                            <th style="width: 15%;">Format</th>
                            <th style="width: 50%;">Nilai Saat Ini <span class="draft-badge" id="badge-<?= esc($prefix) ?>">DRAFT</span></th>
                            <th style="width: 15%;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $c): ?>
                        <tr id="row-<?= $c['id'] ?>">
                            <td>
                                <span class="key-badge"><?= esc($c['content_key']) ?></span>
                                <span class="draft-badge" id="row-badge-<?= $c['id'] ?>">DIEDIT</span>
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
                            <td id="preview-<?= $c['id'] ?>">
                                <?php if($c['content_type'] == 'image'): ?>
                                    <img src="<?= esc($c['content_value']) ?>" class="content-preview-img" alt="CMS Image">
                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px; word-break: break-all;"><?= esc($c['content_value']) ?></div>
                                <?php else: ?>
                                    <div class="content-preview-text"><?= esc($c['content_value']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn-edit hover-trigger" onclick="openCmsModal(<?= $c['id'] ?>)">
                                    <i class="fa-solid fa-pen-nib"></i> Edit
                                </button>
                                <?php if(strpos($c['content_key'], 'beranda_kenangan_') !== false || strpos($c['content_key'], '_custom_') !== false): ?>
                                <button type="button" class="btn-danger-sm" onclick="markDelete(<?= $c['id'] ?>, this)"><i class="fa-solid fa-trash"></i> Hapus</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $first = false; endforeach; ?>

    </div>
</div>

<!-- Modal Modifikasi Konten -->
<?php foreach ($groupedContents as $prefix => $items): ?>
    <?php foreach ($items as $c): ?>
    <div class="cms-modal-overlay" id="modal-<?= $c['id'] ?>">
        <div class="cms-modal">
            <button class="modal-close" onclick="closeCmsModal(<?= $c['id'] ?>)"><i class="fa-solid fa-xmark"></i></button>
            <h3 style="color: var(--text-primary); font-family: 'Playfair Display', serif; margin-bottom: 5px;">Modifikasi Konten</h3>
            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 25px;">Key: <span class="key-badge" style="padding: 2px 6px; font-size: 0.7rem;"><?= esc($c['content_key']) ?></span></div>
            
            <form onsubmit="saveDraft(event, <?= $c['id'] ?>, '<?= $c['content_type'] ?>')">
                <div class="form-group">
                    <label class="form-label">Nilai Modifikasi</label>
                    <?php if($c['content_type'] == 'image'): ?>
                        <div style="background: rgba(0,191,255,0.05); padding: 15px; border-radius: 8px; border: 1px solid rgba(0,191,255,0.2); margin-bottom: 15px;">
                            <div style="font-size: 0.75rem; color: #00bfff; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;"><i class="fa-solid fa-upload"></i> Unggah Aset Baru</div>
                            <input type="file" id="file-<?= $c['id'] ?>" class="form-control-file" accept="image/*" onchange="document.getElementById('input-<?= $c['id'] ?>').value = ''">
                        </div>
                        <div style="text-align: center; margin-bottom: 15px; color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;">Atau Tautkan URL Eksternal</div>
                        <input type="text" id="input-<?= $c['id'] ?>" class="form-control" value="<?= esc($c['content_value']) ?>" placeholder="https://..." oninput="document.getElementById('file-<?= $c['id'] ?>').value = ''">
                    <?php elseif($c['content_type'] == 'html'): ?>
                        <textarea id="input-<?= $c['id'] ?>" class="form-control" rows="8" required style="font-family: monospace; font-size: 0.85rem;"><?= esc($c['content_value']) ?></textarea>
                    <?php else: ?>
                        <textarea id="input-<?= $c['id'] ?>" class="form-control" rows="5" required><?= esc($c['content_value']) ?></textarea>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-submit hover-trigger">Simpan Sementara (Draft)</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- Modal Tambah Kunci -->
<div class="cms-modal-overlay" id="modal-add-key">
    <div class="cms-modal">
        <button class="modal-close" onclick="closeAddKeyModal()"><i class="fa-solid fa-xmark"></i></button>
        <h3 style="color: #00bfff; font-family: 'Playfair Display', serif; margin-bottom: 5px;">Tambah Kunci Konten Baru</h3>
        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 25px;">Gunakan prefiks yang sesuai (misal: <code id="addKeyPrefixLabel">beranda_</code>) agar masuk di tab ini.</p>
        
        <form onsubmit="saveNewKeyDraft(event)">
            <input type="hidden" id="newKeyPrefix">
            <div class="form-group">
                <label class="form-label">Key Name (Identifier)</label>
                <div style="display:flex; align-items:center; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding-left:12px;">
                    <span style="color:#d4af37; font-family:monospace;" id="addKeyPrefixDisplay"></span>
                    <input type="text" id="newKeySuffix" class="form-control" style="border:none; box-shadow:none; background:transparent; padding-left:5px;" placeholder="nama_kunci" required pattern="[a-zA-Z0-9_]+">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tipe Konten</label>
                <select id="newKeyType" class="form-control" onchange="toggleNewKeyInput(this.value)">
                    <option value="text">Teks Pendek/Panjang</option>
                    <option value="html">Format HTML</option>
                    <option value="image">Gambar / Image</option>
                </select>
            </div>
            
            <div class="form-group" id="newKeyValueWrapper">
                <label class="form-label">Nilai Konten</label>
                <textarea id="newKeyValue" class="form-control" rows="4" required></textarea>
                <div id="newKeyFileWrapper" style="display:none; background: rgba(0,191,255,0.05); padding: 15px; border-radius: 8px; border: 1px solid rgba(0,191,255,0.2);">
                    <input type="file" id="newKeyFile" class="form-control-file" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn-submit hover-trigger" style="background: linear-gradient(135deg, #00bfff, #0077ff); color: #fff;">Tambahkan ke Draft</button>
        </form>
    </div>
</div>

<!-- Floating Widget -->
<div class="batch-save-widget" id="batchSaveWidget" onclick="submitBatchChanges()">
    <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Perubahan
    <div class="batch-save-count" id="batchSaveCount">0</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // State Penyimpanan
    let draftUpdates = {}; 
    let draftFiles = {};
    let draftDeletions = [];
    let newKeys = []; 
    // newKeys: [{key: '...', type: '...', value: '...', file: File|null}]

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
            setTimeout(() => { overlay.style.display = 'none'; document.body.style.overflow = ''; }, 300);
        }
    }

    function updateFloatingButton() {
        const total = Object.keys(draftUpdates).length + draftDeletions.length + newKeys.length;
        const widget = document.getElementById('batchSaveWidget');
        const countEl = document.getElementById('batchSaveCount');
        
        countEl.textContent = total;
        if (total > 0) {
            widget.classList.add('visible');
        } else {
            widget.classList.remove('visible');
        }
    }

    function saveDraft(e, id, type) {
        e.preventDefault();
        
        let val = '';
        let file = null;
        
        if (type === 'image') {
            const fileInput = document.getElementById('file-' + id);
            const textInput = document.getElementById('input-' + id);
            if (fileInput.files.length > 0) {
                file = fileInput.files[0];
                val = '[NEW_FILE]';
            } else {
                val = textInput.value;
            }
        } else {
            val = document.getElementById('input-' + id).value;
        }

        // Store to state
        draftUpdates[id] = val;
        if (file) { draftFiles[id] = file; } else { delete draftFiles[id]; }

        // Update UI
        const row = document.getElementById('row-' + id);
        row.classList.add('draft-modified');
        document.getElementById('row-badge-' + id).style.display = 'inline-block';
        
        const preview = document.getElementById('preview-' + id);
        if (type === 'image') {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="content-preview-img"><div style="font-size: 0.7rem; color: #00ff88; margin-top: 5px;">(Draft File) ${file.name}</div>`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `<img src="${val}" class="content-preview-img"><div style="font-size: 0.7rem; color: #00ff88; margin-top: 5px; word-break: break-all;">${val}</div>`;
            }
        } else {
            // escape HTML
            const div = document.createElement('div');
            div.innerText = val;
            preview.innerHTML = `<div class="content-preview-text" style="border-color:#00ff88; color:#00ff88;">${div.innerHTML}</div>`;
        }

        closeCmsModal(id);
        updateFloatingButton();
        if(window.showToast) window.showToast('Draft Disimpan', 'Perubahan disematkan. Klik Simpan Semua untuk mengaplikasikan.', false);
    }

    function markDelete(id, btnElement) {
        if (!confirm('Tandai kunci ini untuk dihapus?')) return;
        draftDeletions.push(id);
        const row = document.getElementById('row-' + id);
        row.style.opacity = '0.3';
        row.style.background = 'rgba(255, 51, 102, 0.1)';
        btnElement.style.display = 'none';
        updateFloatingButton();
    }

    // Modal Add Key
    function openAddKeyModal(prefix) {
        document.getElementById('newKeyPrefix').value = prefix + '_';
        document.getElementById('addKeyPrefixLabel').textContent = prefix + '_';
        document.getElementById('addKeyPrefixDisplay').textContent = prefix + '_';
        
        const overlay = document.getElementById('modal-add-key');
        overlay.style.display = 'flex';
        void overlay.offsetWidth;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeAddKeyModal() {
        const overlay = document.getElementById('modal-add-key');
        overlay.classList.remove('active');
        setTimeout(() => { overlay.style.display = 'none'; document.body.style.overflow = ''; }, 300);
    }

    function toggleNewKeyInput(type) {
        const val = document.getElementById('newKeyValue');
        const fWrap = document.getElementById('newKeyFileWrapper');
        if (type === 'image') {
            val.placeholder = 'URL Gambar (atau upload di bawah)';
            val.required = false;
            fWrap.style.display = 'block';
        } else {
            val.placeholder = '';
            val.required = true;
            fWrap.style.display = 'none';
        }
    }

    function saveNewKeyDraft(e) {
        e.preventDefault();
        const prefix = document.getElementById('newKeyPrefix').value;
        const suffix = document.getElementById('newKeySuffix').value;
        const type = document.getElementById('newKeyType').value;
        const keyName = prefix + suffix;
        let val = document.getElementById('newKeyValue').value;
        let file = null;

        if (type === 'image') {
            const fInput = document.getElementById('newKeyFile');
            if (fInput.files.length > 0) {
                file = fInput.files[0];
                val = '[NEW_FILE]';
            }
        }

        newKeys.push({ key: keyName, type: type, value: val, file: file });

        // Update UI (Inject new row)
        const tbody = document.querySelector(`#table-${prefix.replace('_','')} tbody`);
        let previewHtml = '';
        if (type === 'image' && file) {
            previewHtml = `<span style="color:#00ff88;font-size:0.8rem;">(Draft File) ${file.name}</span>`;
        } else {
            previewHtml = `<span style="color:#00ff88;font-size:0.8rem;">${val}</span>`;
        }
        
        const tr = document.createElement('tr');
        tr.className = 'draft-modified';
        tr.innerHTML = `
            <td><span class="key-badge">${keyName}</span> <span class="draft-badge" style="display:inline-block">BARU</span></td>
            <td><span class="type-badge type-${type}">${type.toUpperCase()}</span></td>
            <td>${previewHtml}</td>
            <td><span style="color:#ff3366;font-size:0.8rem;">DRAFT</span></td>
        `;
        if(tbody) tbody.appendChild(tr);

        closeAddKeyModal();
        updateFloatingButton();
        
        document.getElementById('newKeySuffix').value = '';
        document.getElementById('newKeyValue').value = '';
        document.getElementById('newKeyFile').value = '';
    }

    async function submitBatchChanges() {
        const btn = document.getElementById('batchSaveWidget');
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';
        btn.style.pointerEvents = 'none';

        const formData = new FormData();
        const csrfHash = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('csrf_test_name', csrfHash);
        
        // Append Updates
        formData.append('updates', JSON.stringify(draftUpdates));
        for (const [id, file] of Object.entries(draftFiles)) {
            formData.append(`file_${id}`, file);
        }

        // Append Deletions
        formData.append('deletions', JSON.stringify(draftDeletions));

        // Append New Keys
        const newKeysMeta = newKeys.map((nk, idx) => ({ key: nk.key, type: nk.type, value: nk.value }));
        formData.append('new_keys', JSON.stringify(newKeysMeta));
        newKeys.forEach((nk, idx) => {
            if (nk.file) formData.append(`new_file_${idx}`, nk.file);
        });

        try {
            const resp = await fetch('/admin/cms/batch_update', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfHash },
                body: formData
            });
            const result = await resp.json();
            
            if (result.status === 'success') {
                if(window.showToast) window.showToast('Berhasil', 'Semua perubahan CMS berhasil diterapkan permanen.', false);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                if(window.showToast) window.showToast('Gagal', result.message || 'Terjadi kesalahan.', true);
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Semua Perubahan';
                btn.style.pointerEvents = 'auto';
            }
        } catch (e) {
            console.error(e);
            alert('Gagal menyambung ke server.');
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Semua Perubahan';
            btn.style.pointerEvents = 'auto';
        }
    }
</script>
<?= $this->endSection() ?>
