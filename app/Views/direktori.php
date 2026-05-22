<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
The Archive - 42nd Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="/vendor/swiper/swiper-bundle.min.css">
<link rel="stylesheet" href="/css/direktori.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="direktori-container">

    <div class="ethereal-glow"></div>

    <div class="search-wrapper">
        <form action="/direktori" method="GET" style="display:flex; gap:8px; width:100%; max-width:500px; margin:0 auto;">
            <input type="text" id="searchInput" name="q" class="search-input" placeholder="Temukan Rekam Jejak..." value="<?= esc($search ?? '') ?>">
            <button type="submit" style="background:#d4af37; border:none; color:#000; padding:10px 18px; border-radius:10px; cursor:pointer; font-weight:bold; font-size:0.85rem; letter-spacing:1px;"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <?php if(!empty($search)): ?>
            <div style="text-align:center; margin-top:10px;">
                <a href="/direktori" style="color:var(--text-secondary); font-size:0.8rem; text-decoration:none;"><i class="fa-solid fa-times"></i> Reset pencarian</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if(empty($alumni)): ?>
        <div class="text-center text-themeSec font-tech w-full py-12">
            <i class="fa-solid fa-book-open text-4xl text-accent/50 mb-4"></i><br>Arsip belum mencatat histori apapun.
        </div>
    <?php else: ?>
        
        <div class="swiper mySwiper">
            <div class="swiper-wrapper" id="swiperWrapper">
                
                <?php foreach($alumni as $user): ?>
                    <?php 
                        $foto = !empty($user['foto_profil']) ? '/uploads/profiles/' . $user['foto_profil'] : '/images/default-avatar.png';
                        $serial = 'ID-42.' . strtoupper(substr(md5($user['id']), 0, 4));
                    ?>
                    <div class="swiper-slide alumni-slide" data-search="<?= strtolower($user['nama_lengkap'] . ' ' . $user['nama_panggilan']) ?>">
                        <div class="luminary-card">
                            
                            <div class="agent-serial"><?= $serial ?></div>

                            <div class="photo-ring">
                                <img src="<?= $foto ?>" class="card-photo" alt="Photo">
                            </div>

                            <h3 class="card-name"><?= esc($user['nama_panggilan']) ?></h3>
                            <div class="card-full-name"><?= esc($user['nama_lengkap']) ?></div>
                            
                            <div class="card-details">
                                <?php if (!empty($isLoggedIn)): ?>
                                <div class="detail-group reveal-item r-1">
                                    <div class="d-label">Asal</div>
                                    <div class="d-value"><?= esc(($user['tempat_lahir'] ?? '-') . (!empty($user['tanggal_lahir']) ? ', ' . date('d F Y', strtotime($user['tanggal_lahir'])) : '')) ?></div>
                                </div>
                                <div class="detail-group reveal-item r-2">
                                    <div class="d-label">Domisili</div>
                                    <div class="d-value"><?= esc($user['alamat_lengkap'] ?? '-') ?></div>
                                </div>
                                <div class="detail-group reveal-item r-3">
                                    <div class="d-label">Visi & Aspirasi</div>
                                    <div class="d-value"><?= esc($user['cita_cita'] ?? 'Merahasiakan Tujuannya') ?></div>
                                </div>

                                <div class="card-quote reveal-item r-4">
                                    "<?= esc($user['motivasi_hidup'] ?? 'Belum membagikan kutipan.') ?>"
                                </div>

                                <div class="card-socials reveal-item r-4">
                                    <a href="/chat/personal/<?= $user['id'] ?>" class="soc-btn" title="Kirim Pesan"><i class="fa-solid fa-comment-dots"></i></a>
                                    <?php if(!empty($user['akun_ig'])): ?>
                                        <a href="https://instagram.com/<?= esc(ltrim($user['akun_ig'], '@')) ?>" target="_blank" class="soc-btn" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if(!empty($user['akun_tiktok'])): ?>
                                        <a href="https://tiktok.com/@<?= esc(ltrim($user['akun_tiktok'], '@')) ?>" target="_blank" class="soc-btn" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <div class="reveal-item r-1" style="text-align:center; padding:15px 0;">
                                    <a href="/login" style="color:#d4af37; text-decoration:none; font-size:0.8rem; font-weight:600; letter-spacing:1px;">
                                        <i class="fa-solid fa-lock" style="margin-right:6px;"></i> Masuk untuk lihat profil lengkap
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="nav-arrow nav-prev" id="btnPrev"><i class="fa-solid fa-chevron-left"></i></div>
        <div class="nav-arrow nav-next" id="btnNext"><i class="fa-solid fa-chevron-right"></i></div>

        <?php if(isset($pager)): ?>
        <div style="display:flex; justify-content:center; gap:10px; margin-top:30px; padding-bottom:20px;">
            <?= $pager->links('default', 'default_full') ?>
        </div>
        <?php endif; ?>

    <?php endif; ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        
        const swiperWrapper = document.getElementById('swiperWrapper');
        if(!swiperWrapper) return; 

        // Swiper dengan Setting Super Smooth (Tanpa Hacker Script)
        const swiper = new Swiper('.mySwiper', {
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            initialSlide: 0,
            speed: 800, // Diperlambat sedikit agar terasa "berat" dan mewah
            coverflowEffect: {
                rotate: 15,     
                stretch: 0,     
                depth: 400,     
                modifier: 1,    
                slideShadows: false, 
            },
            navigation: {
                nextEl: '#btnNext',
                prevEl: '#btnPrev',
            },
            keyboard: { enabled: true },
            on: {
                slideChangeTransitionStart: function () {
                    if (navigator.vibrate) navigator.vibrate(10); // Subtle haptic
                }
            }
        });

        // Live Search Cerdas (Mulus tanpa reload)
        const searchInput = document.getElementById('searchInput');
        const originalSlides = Array.from(document.querySelectorAll('.alumni-slide'));

        searchInput.addEventListener('input', function() {
            const filterText = this.value.toLowerCase();
            
            swiper.removeAllSlides();

            const filteredSlides = originalSlides.filter(slide => {
                const nameData = slide.getAttribute('data-search');
                return nameData.includes(filterText);
            });

            if(filteredSlides.length > 0) {
                swiper.appendSlide(filteredSlides);
                swiper.update(); 
                swiper.slideTo(0, 0); 
            }
        });

    });
</script>
<?= $this->endSection() ?>