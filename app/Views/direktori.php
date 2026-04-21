<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
The Archive - 42nd Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    .direktori-container { padding: 120px 0 50px 0; min-height: 100vh; width: 100%; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 10; overflow: hidden; }
    
    /* THE ELEGANT SEARCH PILL */
    .search-wrapper { position: relative; width: 90%; max-width: 500px; margin: 0 auto 40px auto; z-index: 50; }
    .search-input { width: 100%; padding: 18px 30px 18px 65px; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-border); border-radius: 50px; color: var(--text-primary); font-family: 'Manrope', sans-serif; font-size: 1rem; font-weight: 500; outline: none; box-shadow: var(--glass-shadow); transition: all 0.5s ease; text-transform: uppercase; letter-spacing: 2px; }
    .search-input:focus { border-color: #d4af37; box-shadow: 0 20px 50px rgba(212, 175, 55, 0.2); }
    .search-icon { position: absolute; left: 25px; top: 50%; transform: translateY(-50%); color: #d4af37; font-size: 1.2rem; pointer-events: none; transition: 0.5s; }

    /* ETHEREAL PEDESTAL (Pendaran Cahaya Halus, bukan laser) */
    .ethereal-glow { position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 100vw; max-width: 900px; height: 300px; background: radial-gradient(ellipse at bottom, rgba(212,175,55,0.1) 0%, transparent 70%); pointer-events: none; z-index: 1; filter: blur(20px); }

    /* SWIPER CONTAINER */
    .swiper { width: 100%; padding-top: 20px; padding-bottom: 80px; z-index: 10; }
    .swiper-slide { width: 340px; height: auto; display: flex; flex-direction: column; opacity: 0.4; transition: opacity 0.6s ease; cursor: grab; }
    .swiper-slide-active { opacity: 1; } 

    /* LUXURY DOSSIER CARD */
    .luminary-card { background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-border); border-radius: 24px; padding: 40px 25px; text-align: center; box-shadow: var(--glass-shadow); transition: all 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.27); position: relative; overflow: hidden; }
    
    .swiper-slide-active .luminary-card { border-color: rgba(212,175,55,0.4); box-shadow: 0 30px 60px rgba(0,0,0,0.5), inset 0 0 30px rgba(212,175,55,0.05); transform: translateY(-15px); }
    [data-theme="light"] .swiper-slide-active .luminary-card { box-shadow: 0 30px 60px rgba(212,175,55,0.15), inset 0 0 30px rgba(212,175,55,0.05); }

    /* Serial Number (Classic Archive Style) */
    .agent-serial { position: absolute; top: 20px; right: 25px; font-family: 'Courier New', monospace; font-size: 0.7rem; color: var(--text-secondary); letter-spacing: 2px; opacity: 0; transition: opacity 0.5s ease; }
    .swiper-slide-active .agent-serial { opacity: 0.7; }

    /* Foto Profil */
    .photo-ring { width: 140px; height: 140px; margin: 0 auto 25px auto; border-radius: 50%; padding: 4px; background: linear-gradient(135deg, #d4af37, transparent, #d4af37); position: relative; transition: all 0.6s ease; filter: grayscale(80%) opacity(0.8); }
    .swiper-slide-active .photo-ring { width: 160px; height: 160px; box-shadow: 0 20px 40px rgba(212,175,55,0.3); filter: grayscale(0%) opacity(1); margin-bottom: 30px; }
    .card-photo { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid var(--bg-main); transition: 0.6s; }

    /* Header Info */
    .card-name { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--text-primary); font-weight: 700; line-height: 1.2; margin-bottom: 5px; transition: color 0.5s ease; }
    .swiper-slide-active .card-name { color: #d4af37; font-size: 2.2rem; }
    .card-full-name { font-family: 'Manrope', sans-serif; font-size: 0.85rem; color: var(--text-secondary); letter-spacing: 3px; text-transform: uppercase; font-weight: 600; }

    /* ================= CINEMATIC REVEAL DETAILS ================= */
    .card-details { max-height: 0; opacity: 0; overflow: hidden; transition: max-height 0.8s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.5s ease; margin-top: 0; }
    .swiper-slide-active .card-details { max-height: 500px; opacity: 1; margin-top: 30px; }

    /* Animasi Staggered Fade Up untuk isi konten */
    .reveal-item { opacity: 0; transform: translateY(20px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .swiper-slide-active .reveal-item { opacity: 1; transform: translateY(0); }
    
    .swiper-slide-active .r-1 { transition-delay: 0.2s; }
    .swiper-slide-active .r-2 { transition-delay: 0.3s; }
    .swiper-slide-active .r-3 { transition-delay: 0.4s; }
    .swiper-slide-active .r-4 { transition-delay: 0.5s; }

    .detail-group { margin-bottom: 18px; }
    .d-label { font-family: 'Courier New', monospace; font-size: 0.65rem; color: #d4af37; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 5px; opacity: 0.8; }
    .d-value { font-family: 'Manrope', sans-serif; font-size: 0.95rem; color: var(--text-primary); font-weight: 500; }

    .card-quote { font-style: italic; font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--text-primary); line-height: 1.6; margin: 30px 0 20px; position: relative; opacity: 0.9; }

    .card-socials { display: flex; justify-content: center; gap: 15px; margin-top: 20px; }
    .soc-btn { width: 45px; height: 45px; border-radius: 50%; background: var(--glass-border); color: var(--text-secondary); display: flex; justify-content: center; align-items: center; text-decoration: none; font-size: 1.2rem; transition: all 0.3s ease; }
    .soc-btn:hover { background: #d4af37; color: #000; transform: scale(1.1); box-shadow: 0 10px 20px rgba(212,175,55,0.4); }

    /* NAVIGASI PANAH ELEGAN */
    .nav-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 60px; height: 60px; border-radius: 50%; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid rgba(212,175,55,0.3); color: #d4af37; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; cursor: pointer; z-index: 50; transition: all 0.4s ease; box-shadow: var(--glass-shadow); }
    .nav-arrow:hover { background: #d4af37; color: #fff; box-shadow: 0 15px 30px rgba(212,175,55,0.4); scale: 1.05; }
    .nav-prev { left: 50px; } .nav-next { right: 50px; }
    .swiper-button-disabled { opacity: 0; pointer-events: none; }

    @media (max-width: 768px) {
        .direktori-container { padding: 100px 0 20px 0; }
        .swiper-slide { width: 300px; }
        .luminary-card { padding: 30px 20px; }
        .nav-arrow { display: none; } 
        .swiper-slide-active .photo-ring { width: 130px; height: 130px; margin-bottom: 20px; }
        .swiper-slide-active .card-name { font-size: 1.8rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="direktori-container">

    <div class="ethereal-glow"></div>

    <div class="search-wrapper">
        <input type="text" id="searchInput" class="search-input" placeholder="Temukan Rekam Jejak...">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
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
                        $serial = 'EXP-42.' . strtoupper(substr(md5($user['id']), 0, 4));
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
                                <div class="detail-group reveal-item r-1">
                                    <div class="d-label">Origin</div>
                                    <div class="d-value"><?= esc($user['tempat_tanggal_lahir']) ?></div>
                                </div>
                                <div class="detail-group reveal-item r-2">
                                    <div class="d-label">Base Location</div>
                                    <div class="d-value"><?= esc($user['alamat_lengkap']) ?></div>
                                </div>
                                <div class="detail-group reveal-item r-3">
                                    <div class="d-label">Primary Objective</div>
                                    <div class="d-value"><?= esc($user['cita_cita'] ?? 'Merahasiakan Tujuannya') ?></div>
                                </div>

                                <div class="card-quote reveal-item r-4">
                                    "<?= esc($user['motivasi_hidup'] ?? 'Diam dalam bayangan, bergerak dalam tindakan.') ?>"
                                </div>

                                <div class="card-socials reveal-item r-4">
                                    <?php if(!empty($user['akun_ig'])): ?>
                                        <a href="https://instagram.com/<?= esc(ltrim($user['akun_ig'], '@')) ?>" target="_blank" class="soc-btn"><i class="fa-brands fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if(!empty($user['akun_tiktok'])): ?>
                                        <a href="https://tiktok.com/@<?= esc(ltrim($user['akun_tiktok'], '@')) ?>" target="_blank" class="soc-btn"><i class="fa-brands fa-tiktok"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="nav-arrow nav-prev" id="btnPrev"><i class="fa-solid fa-chevron-left"></i></div>
        <div class="nav-arrow nav-next" id="btnNext"><i class="fa-solid fa-chevron-right"></i></div>

    <?php endif; ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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