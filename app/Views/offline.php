<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Koneksi Terputus
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px;">
    <i class="fa-solid fa-satellite-dish" style="font-size: 5rem; color: #d4af37; margin-bottom: 20px; filter: drop-shadow(0 0 15px rgba(212,175,55,0.5));"></i>
    <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px; color: var(--text-primary);">Koneksi Terputus</h1>
    <p style="color: var(--text-secondary); max-width: 400px; margin-bottom: 30px; font-size: 1.1rem;">
        Anda sedang tidak terhubung ke jaringan. Beberapa fitur mungkin tidak tersedia dalam mode offline.
    </p>
    <button onclick="window.location.reload()" style="padding: 12px 30px; background: transparent; border: 1px solid #d4af37; color: #d4af37; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='rgba(212,175,55,0.1)'" onmouseout="this.style.background='transparent'">
        Coba Ulang
    </button>
</div>
<?= $this->endSection() ?>
