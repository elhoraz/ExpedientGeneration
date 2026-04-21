// Utility untuk konversi string Base64Url ke ArrayBuffer
function base64urlToBuffer(base64url) {
    const padding = '='.repeat((4 - base64url.length % 4) % 4);
    const base64 = (base64url + padding).replace(/-/g, '+').replace(/_/g, '/');
    const raw = window.atob(base64);
    const buffer = new Uint8Array(raw.length);
    for (let i = 0; i < raw.length; i++) buffer[i] = raw.charCodeAt(i);
    return buffer.buffer;
}

// Utility untuk konversi ArrayBuffer ke Base64Url
function bufferToBase64url(buffer) {
    const bytes = new Uint8Array(buffer);
    let str = '';
    for (const charCode of bytes) str += String.fromCharCode(charCode);
    return window.btoa(str).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

document.getElementById('btnDaftarBiometrik').addEventListener('click', async () => {
    try {
        // 1. Minta tantangan (opsi) dari server
        const response = await fetch('/biometric/register-options');
        const options = await response.json();

        // 2. Format opsi agar bisa dibaca browser
        options.challenge = base64urlToBuffer(options.challenge);
        options.user.id = base64urlToBuffer(options.user.id);

        // 3. Panggil sensor Biometrik (Fingerprint/FaceID)
        const credential = await navigator.credentials.create({ publicKey: options });

        // 4. Ubah hasil bacaan sensor kembali ke string untuk dikirim ke CI4
        const credentialData = {
            id: credential.id,
            rawId: bufferToBase64url(credential.rawId),
            type: credential.type,
            response: {
                attestationObject: bufferToBase64url(credential.response.attestationObject),
                clientDataJSON: bufferToBase64url(credential.response.clientDataJSON),
            }
        };

        // 5. Simpan ke database
        const saveRes = await fetch('/biometric/register-save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(credentialData)
        });

        const result = await saveRes.json();
        alert(result.message);

    } catch (err) {
        console.error('Gagal mendaftarkan biometrik:', err);
        alert('Gagal membaca biometrik atau dibatalkan.');
    }
});

<style>
    .live-toast {
        position: fixed;
        bottom: 30px;
        right: -400px; /* Sembunyi di luar layar kanan */
        width: 320px;
        background: rgba(20, 20, 20, 0.85);
        backdrop-filter: blur(12px);
        border-left: 4px solid var(--gold-accent);
        border-radius: 8px;
        padding: 15px 20px;
        color: var(--text-color);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: right 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 9999;
    }

    .live-toast.show {
        right: 30px; /* Muncul ke layar */
    }

    .toast-icon {
        background: rgba(212, 175, 55, 0.2);
        color: var(--gold-accent);
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex; justify-content: center; align-items: center;
        font-size: 1.2rem;
    }

    .toast-content h4 { margin: 0; font-size: 0.95rem; color: var(--gold-accent); }
    .toast-content p { margin: 3px 0 0 0; font-size: 0.8rem; color: var(--silver-accent); }
</style>

<div id="liveToast" class="live-toast">
    <div class="toast-icon"><i class="fa-solid fa-bell"></i></div>
    <div class="toast-content">
        <h4 id="toastTitle">Seseorang Bergabung!</h4>
        <p id="toastMessage">Memuat...</p>
    </div>
</div>

<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script>
    // Konfigurasi Pusher Client untuk nyambung ke Soketi
    // Enable pusher logging - jangan gunakan di production
    Pusher.logToConsole = true;

    var pusher = new Pusher('app-key', {
        wsHost: '127.0.0.1',
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
        cluster: 'mt1' // Bebas, karena kita pakai server sendiri
    });

    // Berlangganan ke channel yang sama dengan Backend
    var channel = pusher.subscribe('expedient-channel');
    
    // Dengarkan event 'alumni-baru'
    channel.bind('alumni-baru', function(data) {
        // Manipulasi DOM untuk menampilkan notifikasi
        const toast = document.getElementById('liveToast');
        document.getElementById('toastTitle').innerText = data.nama;
        document.getElementById('toastMessage').innerText = data.pesan;

        // Tampilkan animasi masuk
        toast.classList.add('show');

        // Sembunyikan otomatis setelah 5 detik
        setTimeout(() => {
            toast.classList.remove('show');
        }, 5000);
    });
</script>