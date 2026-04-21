<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Profil Entitas - Expedient
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --gold-main: #d4af37;
        --gold-light: #fff2cd;
        --glass-bg: rgba(10, 15, 12, 0.65);
        --glass-border: rgba(212,175,55,0.2);
    }

    body {
        background-color: #030504;
        background-image: radial-gradient(circle at 50% 0%, #0d1410 0%, #030504 80%);
    }

    .profil-wrapper {
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding: clamp(100px, 15vh, 150px) 5% 10vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow-x: hidden;
    }

    /* Tombol Navigasi */
    .nav-actions {
        width: 100%;
        max-width: 900px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        z-index: 10;
        opacity: 0; /* GSAP Intro */
    }

    .btn-back {
        color: #7b8e9b; text-decoration: none; font-family: 'Inter', sans-serif;
        font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;
        display: flex; align-items: center; gap: 10px; transition: 0.3s;
    }
    .btn-back:hover { color: var(--gold-main); transform: translateX(-5px); }

    /* Header Profil */
    .profil-header { text-align: center; margin-bottom: 50px; opacity: 0; }
    .profil-title { font-family: 'Playfair Display', serif; color: var(--gold-main); font-size: 2.5rem; letter-spacing: 4px; text-transform: uppercase; margin: 0 0 10px 0; }
    .profil-subtitle { color: #7b8e9b; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; }

    /* Grid Layout Utama */
    .profil-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        width: 100%;
        max-width: 900px;
        opacity: 0; /* GSAP Intro */
    }

    /* Desain Kartu Glassmorphism Premium */
    .premium-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        position: relative;
        overflow: hidden;
    }

    .premium-panel::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px;
        background: linear-gradient(90deg, transparent, var(--gold-main), transparent);
        opacity: 0.5;
    }

    .panel-title { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.5rem; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; }
    .panel-title i { color: var(--gold-main); }

    /* Info Group */
    .info-group { margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px; }
    .info-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    .data-label { font-size: 0.65rem; color: #7b8e9b; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px; }
    .data-value { font-size: 1.1rem; color: #fff; font-weight: 500; }
    .data-value.gold { color: var(--gold-main); font-family: 'Playfair Display', serif; font-size: 1.3rem; }

    /* Foto Profil Mini di dalam Panel */
    .avatar-wrapper { display: flex; align-items: center; gap: 20px; margin-bottom: 35px; }
    .avatar-img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold-main); box-shadow: 0 0 20px rgba(212,175,55,0.2); }

    /* Tombol Keamanan (Passkey) */
    .btn-passkey {
        width: 100%; background: rgba(212,175,55,0.05); border: 1px solid var(--gold-main);
        color: var(--gold-main); padding: 18px; border-radius: 12px; font-family: 'Inter', sans-serif;
        font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 15px; transition: 0.4s;
    }
    .btn-passkey:hover { background: var(--gold-main); color: #000; box-shadow: 0 10px 20px rgba(212,175,55,0.2); transform: translateY(-3px); }

    .status-text { margin-top: 20px; font-size: 0.8rem; color: #7b8e9b; text-align: center; line-height: 1.6; }

    /* Responsif Mobile */
    @media (max-width: 768px) {
        .profil-grid { grid-template-columns: 1fr; }
        .profil-title { font-size: 2rem; }
        .premium-panel { padding: 30px 20px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="profil-wrapper">

    <div class="nav-actions" id="navActions">
        <a href="/fitur" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Brankas</a>
        <a href="/logout" class="btn-back" style="color:#ff3366;"><i class="fa-solid fa-power-off"></i> Akhiri Sesi</a>
    </div>

    <div class="profil-header" id="profilHeader">
        <h1 class="profil-title">Ruang Kendali</h1>
        <div class="profil-subtitle">Manajemen Entitas & Protokol Keamanan</div>
    </div>

    <div class="profil-grid" id="profilGrid">
        
        <div class="premium-panel">
            <h2 class="panel-title"><i class="fa-solid fa-id-card-clip"></i> Arsip Entitas</h2>
            
            <div class="avatar-wrapper">
                <img src="<?= !empty($user['foto_profil']) ? base_url('uploads/profiles/' . $user['foto_profil']) : 'https://ui-avatars.com/api/?name='.urlencode($user['nama_panggilan']).'&background=d4af37&color=000' ?>" class="avatar-img" alt="Profil">
                <div>
                    <div class="data-label">Nama Sandi / Panggilan</div>
                    <div class="data-value gold"><?= esc($user['nama_panggilan']) ?></div>
                </div>
            </div>

            <div class="info-group">
                <div class="data-label">Nama Lengkap Sesuai Dokumen</div>
                <div class="data-value"><?= esc($user['nama_lengkap']) ?></div>
            </div>

            <div class="info-group">
                <div class="data-label">Alamat Surel Resmi</div>
                <div class="data-value"><?= esc($user['email']) ?></div>
            </div>

            <div class="info-group">
                <div class="data-label">Jalur Komunikasi Pribadi (WhatsApp)</div>
                <div class="data-value"><?= esc($user['no_whatsapp']) ?></div>
            </div>
            
            <div class="info-group">
                <div class="data-label">Identitas Visual Lanjut</div>
                <a href="/sovereign" style="color:var(--gold-main); text-decoration:none; font-size:0.85rem; display:inline-block; margin-top:5px;"><i class="fa-solid fa-gem"></i> Tampilkan Sovereign ID 5D</a>
            </div>
        </div>

        <div class="premium-panel">
            <h2 class="panel-title"><i class="fa-solid fa-shield-halved"></i> Protokol Biometrik</h2>
            
            <div class="info-group">
                <div class="data-label">Otorisasi Visual (Face ID)</div>
                <div class="data-value" style="color: var(--gold-main); font-size: 0.9rem;">
                    <i class="fa-solid fa-check-double"></i> Matriks 128-D Terenkripsi & Aktif
                </div>
            </div>

            <div style="margin-top: 40px;">
                <div class="data-label" style="margin-bottom: 15px;">Kunci Keamanan Perangkat (FIDO2 / Passkey)</div>
                
                <?php if(!empty($user['webauthn_credential_id'])): ?>
                    <div style="color: var(--gold-main); font-size: 0.85rem; padding: 20px; border: 1px solid var(--gold-main); border-radius: 12px; background: rgba(212,175,55,0.05); text-align: center;">
                        <i class="fa-solid fa-lock" style="font-size:1.5rem; margin-bottom:10px;"></i><br>
                        Perangkat ini telah didaftarkan sebagai Kunci Keamanan Fisik.
                    </div>
                <?php else: ?>
                    <div id="bioOptionsBox">
                        <button class="btn-passkey" onclick="startBiometricEnrollment()">
                            <i class="fa-solid fa-fingerprint"></i> Daftarkan Perangkat Saat Ini
                        </button>
                    </div>
                <?php endif; ?>
                
                <div id="bioStatus" class="status-text">
                    Gunakan Sidik Jari (Touch ID / Windows Hello) pada perangkat Anda sebagai lapisan keamanan absolut.
                </div>
            </div>
        </div>

    </div>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // 1. Animasi Masuk (Intro GSAP)
    const tl = gsap.timeline();
    tl.to("#navActions", { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" })
      .to("#profilHeader", { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }, "-=0.6")
      .to("#profilGrid", { opacity: 1, y: 0, duration: 1, ease: "power3.out" }, "-=0.4");
});

// ================= LOGIKA WEBAUTHN / PASSKEY =================
function base64urlToBuffer(base64url) {
    if (!base64url) return new ArrayBuffer(0);
    const padding = '='.repeat((4 - base64url.length % 4) % 4);
    const base64 = (base64url + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) { outputArray[i] = rawData.charCodeAt(i); }
    return outputArray.buffer;
}

function bufferToBase64url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) { binary += String.fromCharCode(bytes[i]); }
    return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

async function startBiometricEnrollment() {
    const statusEl = document.getElementById('bioStatus');
    const optionsBox = document.getElementById('bioOptionsBox');
    
    if (navigator.vibrate) navigator.vibrate(50);

    try {
        // State: Loading
        optionsBox.style.display = 'none';
        statusEl.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menghubungkan Protokol Enkripsi...';
        statusEl.style.color = "var(--gold-main)";

        // 1. Minta tantangan (challenge) dari server PHP
        const res = await fetch('/biometric/register-options');
        const opt = await res.json();
        if (opt.error) throw new Error(opt.error);

        // Menyesuaikan struktur JSON dari library WebAuthn
        const pkConfig = opt.publicKey ? opt.publicKey : opt;
        if (!pkConfig.challenge) throw new Error("Tantangan Kriptografi gagal dimuat.");

        // 2. Decode String ke Buffer
        pkConfig.challenge = base64urlToBuffer(pkConfig.challenge);
        pkConfig.user.id = base64urlToBuffer(pkConfig.user.id);
        if (pkConfig.excludeCredentials) {
            pkConfig.excludeCredentials.forEach(cred => { cred.id = base64urlToBuffer(cred.id); });
        }

        statusEl.innerText = "Sistem menunggu pemindaian perangkat keras Anda (Sidik Jari / PIN)...";

        // 3. PANGGIL SENSOR HP / LAPTOP (FIDO2)
        const credential = await navigator.credentials.create({ publicKey: pkConfig });
        statusEl.innerText = "Mengenkripsi kunci...";

        // 4. Siapkan data untuk dikirim kembali ke server
        const attestationData = {
            id: credential.id,
            rawId: bufferToBase64url(credential.rawId),
            type: credential.type,
            response: { clientDataJSON: bufferToBase64url(credential.response.clientDataJSON) }
        };

        // 5. Kirim untuk diverifikasi
        const verifyRes = await fetch('/biometric/register-verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(attestationData)
        });

        const result = await verifyRes.json();

        // 6. Selesai!
        if (result.status === 'success') {
            statusEl.innerHTML = '<i class="fa-solid fa-check"></i> Kredensial Perangkat Tersimpan';
            statusEl.style.color = "#00ff88";
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
            setTimeout(() => window.location.reload(), 2000); 
        } else {
            throw new Error(result.error || 'Gagal menyimpan kunci di database.');
        }

    } catch (err) {
        console.error(err);
        optionsBox.style.display = 'block';
        statusEl.innerText = "Otorisasi Dibatalkan: " + err.message;
        statusEl.style.color = "#ff3366";
    }
}
</script>
<?= $this->endSection() ?>