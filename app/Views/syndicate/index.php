<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
The Syndicate - Ruang Eksekutif 42nd Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* ================= BACKGROUND & STAGE ================= */
    .syndicate-container {
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding: 120px 5% 50px;
        background: radial-gradient(circle at top right, rgba(212, 175, 55, 0.05), transparent 40%),
                    radial-gradient(circle at bottom left, rgba(0, 255, 136, 0.05), transparent 40%);
        z-index: 1;
        /* BUG FIX KURSOR: Pastikan pointer-events auto dan kursor default muncul */
        pointer-events: auto !important; 
        cursor: default;
    }

    /* ================= HEADER & FILTER ================= */
    .syn-header { text-align: center; margin-bottom: 50px; pointer-events: none; }
    .syn-title {
        font-family: 'Playfair Display', serif; font-size: 3.5rem; color: #fff;
        font-weight: 900; letter-spacing: 4px; text-transform: uppercase;
        text-shadow: 0 10px 30px rgba(212, 175, 55, 0.3); margin-bottom: 10px;
    }
    .syn-subtitle { font-family: 'Courier New', monospace; font-size: 1rem; color: #d4af37; letter-spacing: 6px; text-transform: uppercase; }

    .syn-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 40px; flex-wrap: wrap; gap: 20px;
        position: relative; z-index: 10;
    }
    
    .filter-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-filter {
        background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
        color: #aaa; padding: 10px 20px; border-radius: 50px; font-size: 0.8rem;
        font-weight: 600; text-transform: uppercase; letter-spacing: 2px; cursor: pointer;
        transition: 0.3s ease;
    }
    .btn-filter.active, .btn-filter:hover { background: rgba(212, 175, 55, 0.2); border-color: #d4af37; color: #d4af37; }

    .btn-add-biz {
        background: linear-gradient(135deg, #d4af37, #aa8529); border: none; color: #000;
        padding: 12px 25px; border-radius: 50px; font-size: 0.85rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 2px; cursor: pointer;
        transition: 0.4s ease; display: flex; align-items: center; gap: 10px;
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); text-decoration: none;
    }
    .btn-add-biz:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.5); }

    /* ================= BLACK CARD GALLERY ================= */
    .syn-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        position: relative; z-index: 5;
    }

    /* KARTU VIP GLASSMORPHISM */
    .black-card {
        background: rgba(15, 18, 16, 0.7); backdrop-filter: blur(20px);
        border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 15px;
        overflow: hidden; position: relative;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        display: flex; flex-direction: column; transition: 0.4s ease;
        /* Efek garis pantulan cahaya di ujung kartu */
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        /* BUG FIX KURSOR: Kursor panah saat miring */
    }
    
    /* Efek logo/gambar bisnis */
    .bc-cover { width: 100%; height: 180px; background: #0a0a0a; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .bc-cover img { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; transition: 0.5s; }
    .black-card:hover .bc-cover img { opacity: 1; transform: scale(1.05); }
    
    .bc-badge {
        position: absolute; top: 15px; right: 15px;
        background: rgba(0, 0, 0, 0.8); border: 1px solid #d4af37; color: #d4af37;
        padding: 5px 12px; border-radius: 5px; font-size: 0.7rem; font-weight: 600;
        letter-spacing: 2px; text-transform: uppercase; backdrop-filter: blur(5px);
    }

    /* Info Pemilik (Agen) */
    .bc-owner {
        display: flex; align-items: center; gap: 15px; padding: 0 20px;
        transform: translateY(-20px); /* Menarik foto ke atas memotong batas cover */
    }
    .bc-owner img {
        width: 60px; height: 60px; border-radius: 50%; object-fit: cover;
        border: 3px solid #111; box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }
    .bc-owner-info { display: flex; flex-direction: column; margin-top: 25px; }
    .bc-owner-name { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.1rem; font-weight: 700; margin: 0; }
    .bc-owner-title { font-family: 'Courier New', monospace; color: #00ff88; font-size: 0.7rem; letter-spacing: 1px; }

    /* Konten Kartu */
    .bc-body { padding: 0 20px 20px; flex-grow: 1; margin-top: -10px; }
    .bc-title { font-family: 'Inter', sans-serif; font-size: 1.4rem; color: #fff; font-weight: 800; margin-bottom: 10px; line-height: 1.2; }
    .bc-desc { font-size: 0.85rem; color: #aaa; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

    /* Footer Aksi */
    .bc-footer {
        display: flex; border-top: 1px solid rgba(255,255,255,0.05);
        background: rgba(0,0,0,0.3);
    }
    .bc-btn {
        flex: 1; padding: 15px; text-align: center; color: #fff; text-decoration: none;
        font-size: 0.85rem; font-weight: 600; letter-spacing: 1px; transition: 0.3s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        /* BUG FIX KURSOR: Kursor tangan di tombol */
        cursor: pointer;
    }
    .bc-btn i { font-size: 1rem; }
    .bc-btn.wa { border-right: 1px solid rgba(255,255,255,0.05); }
    .bc-btn.wa:hover { background: rgba(37, 211, 102, 0.1); color: #25D366; }
    .bc-btn.web:hover { background: rgba(212, 175, 55, 0.1); color: #d4af37; }

    /* STATE KOSONG */
    .empty-state {
        grid-column: 1 / -1; text-align: center; padding: 80px 20px;
        background: rgba(255,255,255,0.02); border: 1px dashed rgba(212,175,55,0.3); border-radius: 15px;
    }
    .empty-state i { font-size: 4rem; color: rgba(212,175,55,0.3); margin-bottom: 20px; }
    .empty-state h3 { color: #fff; font-family: 'Playfair Display', serif; }

    @media (max-width: 768px) {
        .syn-title { font-size: 2.2rem; }
        .syn-toolbar { flex-direction: column; align-items: stretch; }
        .filter-group { justify-content: center; }
        .btn-add-biz { justify-content: center; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="syndicate-container">
    
    <div class="syn-header">
        <h1 class="syn-title">The Syndicate</h1>
        <div class="syn-subtitle">Alumni Business & Professional Network</div>
    </div>

    <div class="syn-toolbar">
        <div class="filter-group" id="filterGroup">
            <button class="btn-filter active" data-filter="all">Semua</button>
            <button class="btn-filter" data-filter="F&B">Kuliner (F&B)</button>
            <button class="btn-filter" data-filter="Teknologi">Teknologi</button>
            <button class="btn-filter" data-filter="Jasa">Jasa & Agensi</button>
            <button class="btn-filter" data-filter="Kreatif">Kreatif</button>
            <button class="btn-filter" data-filter="Retail">Retail</button>
        </div>

        <a href="<?= base_url('syndicate/create') ?>" class="btn-add-biz">
            <i class="fa-solid fa-plus"></i> Registrasi Bisnis Anda
        </a>
    </div>

    <div class="syn-grid" id="bizGrid">
        
        <?php if(empty($portofolio)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-vault"></i>
                <h3>Brankas Masih Kosong</h3>
                <p style="color:#aaa;">Jadilah agen pertama yang memamerkan kerajaan bisnis Anda di sini.</p>
            </div>
        <?php else: ?>

            <?php foreach($portofolio as $biz): ?>
                
                <div class="black-card biz-item" data-category="<?= htmlspecialchars($biz['kategori']) ?>" data-tilt data-tilt-glare data-tilt-max-glare="0.2" data-tilt-scale="1.02">
                    
                    <div class="bc-cover">
                        <div class="bc-badge"><?= esc($biz['kategori']) ?></div>
                        <?php if($biz['logo_bisnis']): ?>
                            <img src="<?= base_url('uploads/bisnis/' . $biz['logo_bisnis']) ?>" alt="Logo">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Placeholder">
                        <?php endif; ?>
                    </div>

                    <div class="bc-owner">
                        <?php if($biz['foto_profil']): ?>
                            <img src="<?= base_url('uploads/profiles/' . $biz['foto_profil']) ?>" alt="Owner">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($biz['nama_panggilan']) ?>&background=0D0D0D&color=D4AF37&bold=true" alt="Owner">
                        <?php endif; ?>
                        
                        <div class="bc-owner-info">
                            <h4 class="bc-owner-name"><?= esc($biz['nama_panggilan']) ?></h4>
                            <span class="bc-owner-title">Direktur / Founder</span>
                        </div>
                    </div>

                    <div class="bc-body">
                        <h2 class="bc-title"><?= esc($biz['nama_bisnis']) ?></h2>
                        <div class="bc-desc"><?= esc($biz['deskripsi']) ?></div>
                    </div>

                    <div class="bc-footer">
                        <?php 
                            $wa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $biz['no_whatsapp']));
                        ?>
                        <a href="https://wa.me/<?= $wa ?>" target="_blank" class="bc-btn wa">
                            <i class="fa-brands fa-whatsapp"></i> Hubungi
                        </a>
                        
                        <?php if($biz['link_url']): ?>
                            <a href="<?= esc($biz['link_url']) ?>" target="_blank" class="bc-btn web">
                                <i class="fa-solid fa-globe"></i> Kunjungi
                            </a>
                        <?php else: ?>
                            <a href="#" onclick="return false;" class="bc-btn web" style="opacity:0.3; cursor:not-allowed;">
                                <i class="fa-solid fa-globe"></i> N/A
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if($biz['user_id'] == session()->get('user_id')): ?>
                    <div class="bc-footer" style="border-top: none; background: rgba(0,0,0,0.5);">
                        <a href="<?= base_url('syndicate/edit/' . $biz['id']) ?>" class="bc-btn" style="color: #00ff88; border-right: 1px solid rgba(255,255,255,0.05);">
                            <i class="fa-solid fa-pen-to-square"></i> Ubah
                        </a>
                        <form action="<?= base_url('syndicate/delete/' . $biz['id']) ?>" method="POST" style="flex: 1; display: flex;" onsubmit="event.preventDefault(); window.showConfirm('Konfirmasi', 'Apakah Anda yakin ingin menghapus arsip bisnis ini?').then(res => { if(res) this.submit(); });">
                            <?= csrf_field() ?>
                            <button type="submit" class="bc-btn" style="color: #ff3366; width: 100%; border: none; background: transparent;">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
    
    <div style="margin-top: 40px; display: flex; justify-content: center;" class="pagination-wrapper">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        
        const filterBtns = document.querySelectorAll('.btn-filter');
        const bizItems = document.querySelectorAll('.biz-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filterValue = btn.getAttribute('data-filter');

                bizItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'flex';
                        item.style.animation = 'fadeInUp 0.5s ease forwards';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
</script>
<?= $this->endSection() ?>