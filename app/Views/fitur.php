<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Expedient Vault - Koleksi Fitur Premium
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="/css/fitur.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="vault-wrapper">



    <div class="features-dashboard" id="featuresDashboard" style="display:flex; opacity:1;">
        <div class="dashboard-header">
            <h1 class="dashboard-title">The Sovereign Vault</h1>
            <p class="dashboard-subtitle">Akses Eksklusif Entitas Expedient Terverifikasi</p>
        </div>

        <div class="cinematic-grid">
            <a href="/sovereign" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-gem card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Sovereign ID</h3>
                    <p class="card-desc">Modul identitas 5D interaktif. Merender ulang data biometrik dan arsip Anda dalam bentuk holografik.</p>
                    <div class="launch-btn">Jelajahi Sekarang <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/scanner" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(50%) hue-rotate(90deg) brightness(0.4);"></div>
                <i class="fa-solid fa-qrcode card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Omni Scanner</h3>
                    <p class="card-desc">Pemindai KTA in-app. Baca kode matriks entitas lain untuk langsung melompat ke bilik profil holografik mereka.</p>
                    <div class="launch-btn">Buka Pemindai <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/profil" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2670&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-id-badge card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Profil Entitas</h3>
                    <p class="card-desc">Pusat manajemen data pribadi dan pengaturan kunci keamanan fisik (Passkey).</p>
                    <div class="launch-btn">Kelola Profil <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/wasiat" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1614064641913-a520f596a247?q=80&w=2574&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.3);"></div>
                <i class="fa-solid fa-vault card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Amanah & Wasiat</h3>
                    <p class="card-desc">Brankas pesan terenkripsi tingkat tinggi. Titipkan pesan rahasia, wasiat, atau data vital yang hanya terbuka dengan pemicu otentikasi spesifik.</p>
                    <div class="launch-btn">Buka Brankas <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/baitul-maal" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1579621970795-87facc2f976d?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-coins card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Baitul Maal</h3>
                    <p class="card-desc">Pusat kontribusi dan wakaf elit. Visualisasi rekam jejak sedekah jariyah angkatan dalam bentuk tabungan cahaya keabadian.</p>
                    <div class="launch-btn">Buka Khasanah <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/majlis" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1594954002661-8f55fc15d7de?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-microphone-lines card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Majlis Syura</h3>
                    <p class="card-desc">Bilik suara VVIP eksklusif. Dengarkan kajian, bertukar pikiran, dan jalin ukhuwah dalam keheningan yang elegan.</p>
                    <div class="launch-btn">Masuk Ruang Majlis <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/tarbiyah" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32b7?q=80&w=2532&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-handshake-angle card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Tarbiyah Nexus</h3>
                    <p class="card-desc">Jaringan mentorship elit & ekosistem B2B Halal. Ruang kolaborasi profesional antar entitas untuk memperkuat muamalah dan karir.</p>
                    <div class="launch-btn">Bergabung <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/oracle" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1620712943543-bcc4688e7485?q=80&w=2565&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-eye card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">The Oracle's Vision</h3>
                    <p class="card-desc">Pemindai kamera interaktif untuk mengekstraksi dan membaca Aura Eksekutif Anda secara langsung.</p>
                    <div class="launch-btn">Mulai Pemindaian <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/multazam" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1542642510-48227b613eec?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-ticket card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Protokol Multazam</h3>
                    <p class="card-desc">Sistem RSVP & Tiket Cerdas untuk acara VVIP. Hadiri kajian akbar dan gala diner angkatan dengan otorisasi pass digital eksklusif.</p>
                    <div class="launch-btn">Lihat Jadwal Acara <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/kontemplasi" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1518241353330-0f7941c2d9b5?q=80&w=2574&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.3);"></div>
                <i class="fa-solid fa-peace card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Ruang Kontemplasi</h3>
                    <p class="card-desc">Mode sanctuary layar penuh. Temukan kedamaian dari bisingnya dunia dengan keheningan, tata napas, dan audio ambience Islami.</p>
                    <div class="launch-btn">Masuki Keheningan <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/celestial" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?q=80&w=2670&auto=format&fit=crop');"></div>
                <i class="fa-solid fa-star card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">The Celestial Codex</h3>
                    <p class="card-desc">Tarik tiga kartu takdir dari dek misterius. Ungkap ramalan dan kebijaksanaan hari ini.</p>
                    <div class="launch-btn">Buka Codex <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/divine" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1606836109968-3e4b37be8079?q=80&w=2574&auto=format&fit=crop'); filter: grayscale(50%) brightness(0.4);"></div>
                <i class="fa-solid fa-book-open card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Divine Verse</h3>
                    <p class="card-desc">Jelajahi untaian ayat suci dan refleksi harian. Sentuhan spiritual dalam balutan teknologi tingkat tinggi.</p>
                    <div class="launch-btn">Resapi Ayat <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/enigma" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(80%) brightness(0.4);"></div>
                <i class="fa-solid fa-lock card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Enigma Vault</h3>
                    <p class="card-desc">Brankas digital terenkripsi. Simpan catatan rahasia, memori tersembunyi, dan arsip pribadi yang hanya Anda yang bisa membukanya.</p>
                    <div class="launch-btn">Buka Brankas <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/genesis" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2672&auto=format&fit=crop'); filter: grayscale(50%) brightness(0.4);"></div>
                <i class="fa-solid fa-atom card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Genesis Core</h3>
                    <p class="card-desc">Inti dari segalanya. Jelajahi asal-usul, filosofi, dan fondasi spiritual yang membangun identitas Expedient Generation.</p>
                    <div class="launch-btn">Jelajahi Asal-Usul <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/nexus" class="premium-card js-tilt-card" style="border-color: rgba(212,175,55,0.6); box-shadow: 0 0 20px rgba(212,175,55,0.1);">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2672&auto=format&fit=crop'); filter: hue-rotate(200deg) brightness(0.3);"></div>
                <i class="fa-solid fa-network-wired card-icon" style="color: #d4af37;"></i>
                <div class="card-content">
                    <h3 class="card-title" style="color: #d4af37;">The Nexus</h3>
                    <p class="card-desc">Algoritma analitik cerdas yang menghubungkan visi Anda dengan Kolega Strategis. Merajut jaringan eksekutif masa depan.</p>
                    <div class="launch-btn" style="color: #d4af37; border-color: #d4af37;">Inisiasi Analitik <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/event" class="premium-card js-tilt-card">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(50%) brightness(0.3);"></div>
                <i class="fa-solid fa-calendar-days card-icon"></i>
                <div class="card-content">
                    <h3 class="card-title">Agenda & Eksibisi</h3>
                    <p class="card-desc">Jadwalkan, kelola, dan hadiri pertemuan eksklusif angkatan. Integrasi sistem RSVP pintar untuk entitas Expedient.</p>
                    <div class="launch-btn">Lihat Jadwal <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>

            <a href="/wrapped" class="premium-card js-tilt-card" style="border-color: rgba(212,175,55,0.8); box-shadow: 0 0 30px rgba(212,175,55,0.2);">
                <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=2670&auto=format&fit=crop'); filter: grayscale(20%) brightness(0.6);"></div>
                <i class="fa-solid fa-film card-icon" style="color: #fff; text-shadow: 0 0 10px #d4af37;"></i>
                <div class="card-content">
                    <h3 class="card-title" style="color: #fff; text-shadow: 0 0 10px #d4af37;">Expedient Wrapped</h3>
                    <p class="card-desc">Kilas balik interaktif perjalanan digital Anda di The Vault sepanjang tahun ini.</p>
                    <div class="launch-btn" style="color: #fff; border-color: #d4af37;">Lihat Kilas Balik <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </a>
        </div>
    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/vendor/gsap/gsap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // === MAGNETIC 3D TILT EFFECT (AWWWARDS JS) ===
    const cards = document.querySelectorAll('.js-tilt-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = ((y - centerY) / centerY) * -15;
            const rotateY = ((x - centerX) / centerX) * 15;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            card.style.transition = "none";
        });

        card.addEventListener('mouseleave', () => {
            card.style.transition = "transform 0.6s cubic-bezier(0.25, 1, 0.5, 1)";
            card.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
        });
    });

    // Intro Animation
    gsap.from(".dashboard-header", { opacity: 0, y: -50, duration: 1.5, ease: "expo.out" });
    gsap.from(".premium-card", { 
        opacity: 0, y: 100, rotationX: -20, duration: 1.2, 
        stagger: 0.15, ease: "back.out(1.5)", delay: 0.2 
    });
});
</script>
<?= $this->endSection() ?>