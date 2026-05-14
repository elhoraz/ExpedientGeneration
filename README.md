# 🏛️ EXPEDIENT GENERATION — Museum Digital VVIP

> Platform komunitas alumni eksklusif Pondok Modern Arrisalah Angkatan ke-42, dibangun dengan **CodeIgniter 4**, estetika **luxury dark/gold glassmorphism**, dan fitur interaktif tingkat tinggi.

---

## 📋 Daftar Isi

- [Teknologi](#-teknologi)
- [Arsitektur](#-arsitektur)
- [Keamanan](#-keamanan)
- [Tema & Desain](#-tema--desain)
- [Views](#-views-detail)
- [Controllers](#-controllers-detail)
- [Database](#-database--migrasi)
- [Fitur Utama](#-fitur-utama)
- [Service Layer](#-service-layer)
- [REST API](#-rest-api)
- [Filter & Middleware](#️-filter--middleware)
- [Cache Strategy](#-cache-strategy)
- [Activity Logger](#-activity-logger)
- [Testing](#-testing)
- [Vendor (CDN Lokal)](#-vendor-local-cdn-bundle)
- [Cara Menjalankan](#-cara-menjalankan)
- [Struktur File](#-struktur-file-penting)

---

## 🔧 Teknologi

| Layer | Stack |
|-------|-------|
| Framework | CodeIgniter 4.7+ |
| Database | MySQL |
| Frontend | Vanilla JS, GSAP, Three.js, Globe.gl, Swiper.js |
| Font | Playfair Display, Inter, Amiri (Arabic) |
| Auth | Password + FIDO2/WebAuthn Biometric |
| Realtime | Pusher / Soketi self-hosted (port 6001) |
| Email | SMTP Gmail + Async Queue (cron) |
| 3D Engine | Three.js (Sovereign Vault) |
| Maps | Globe.gl + Nominatim API |

---

## 🏗 Arsitektur

```
app/
├── Config/
│   ├── Routes.php          # Routing utama + auth filter + API group
│   └── Filters.php         # AuthFilter + ThrottleApiFilter
├── Controllers/
│   ├── Api/                # REST API controllers (JSON standar)
│   │   ├── BaseApiController.php
│   │   ├── LocationApi.php
│   │   └── BiometricApi.php
│   └── ...                 # 25 controller halaman
├── Database/Migrations/    # 7 migrasi (users, biometrics, syndicate, buku_tamu, dll)
├── Filters/
│   ├── AuthFilter.php      # Middleware cek session login
│   └── ThrottleApiFilter.php  # Rate limiting 10 req/menit per IP
├── Libraries/
│   └── ActivityLogger.php  # Audit trail ke writable/logs/
├── Models/
│   └── UserModel.php       # Model utama data alumni
├── Services/
│   ├── AuthService.php         # Business logic otentikasi
│   ├── ProfileService.php      # Business logic profil + foto
│   ├── PusherService.php       # Centralized Pusher/Soketi
│   ├── EmailQueueService.php   # Async email queue
│   ├── AnalyticsService.php    # Dashboard analytics
│   ├── BerandaService.php      # Homepage data aggregation
│   └── GamificationService.php # Prestise points system
├── Commands/
│   └── ProcessEmailQueue.php   # php spark email:process
└── Views/                      # 25+ view files + subdirektori
    ├── layout/template.php # Base template (dual theme)
    ├── auth/               # Login & Register
    ├── syndicate/          # Bisnis alumni
    └── errors/             # Custom error pages
```

---

## 🔒 Keamanan

| Fitur | Implementasi |
|-------|-------------|
| **CSRF** | Aktif global, token di setiap form & AJAX header |
| **Auth Filter** | Middleware proteksi semua route dashboard |
| **Password** | `password_hash()` + `password_verify()` (bcrypt) |
| **Rate Limiting** | Session-based pada login (maks 5 percobaan/5 menit) |
| **Email Verifikasi** | Token unik via SMTP Gmail |
| **Forgot Password** | Token reset + kode verifikasi 6 digit |
| **WebAuthn/FIDO2** | Passwordless biometric (Fingerprint/FaceID) |
| **Input Validation** | Rules ketat di semua controller |
| **XSS Protection** | `esc()` helper di semua output view |
| **Base64 Sanitasi** | Validasi format foto profil sebelum simpan |
| **Geolocation** | CSRF token pada update koordinat via AJAX |

---

## 🎨 Tema & Desain

### Dual Theme System
Dikelola via `data-theme` attribute pada `<html>`:
- **Dark Mode** — Latar `#030504`, aksen emas `#d4af37`, glassmorphism gelap
- **Light Mode** — Latar `#f0f5f3`, aksen emas lebih gelap, glass terang

### Elemen Desain Kunci
- **Glassmorphism**: `backdrop-filter: blur()` + border transparan + shadow
- **CSS Variables**: `--bg-main`, `--text-primary`, `--glass-bg`, `--gold-premium`
- **Animasi**: GSAP ScrollTrigger, particle systems, cinematic loaders
- **Typography**: Playfair Display (heading), Inter (body), Amiri (Arabic)
- **Mobile-First**: Touch gestures, haptic feedback, swipe interactions
- **10 Variasi Loader**: Transisi halaman dinamis dengan teks acak

---

## 📄 Views Detail

### `layout/template.php` — Base Template (480 baris)
Template induk seluruh halaman. Mengandung:
- Navbar glassmorphism responsif dengan toggle tema (☀️/🌙)
- Sistem partikel emas latar belakang (Canvas API)
- Custom cursor hover effect
- 10 variasi loader transisi halaman
- Notifikasi real-time via Pusher/Soketi
- Footer dengan link sosial media
- CSS variables untuk dual-theme system

### `auth/login.php` — Halaman Login
- Form glassmorphism dengan floating label
- Rate-limiting UI feedback (countdown timer)
- Integrasi "Forgot Password" inline (modal multi-step)
- Tombol login biometric (WebAuthn)
- Animasi particle background

### `auth/register.php` — Halaman Registrasi
- Form multi-field dengan validasi real-time
- Upload foto profil via Base64 encoding
- Field: nama lengkap, panggilan, kelamin, TTL, alamat, email, WA, password
- Swipe-to-submit pada mobile
- CSRF protection

### `beranda.php` — Museum Utama (1419 baris)
Halaman utama platform, terdiri dari beberapa "ruang pameran":

| Seksi | Deskripsi |
|-------|-----------|
| **Mecha Stage** | Hero section dengan logo interaktif 192-frame sequence animation. Logo bisa dipencar menjadi 13 shard dan diputar 3D. |
| **Epigraph** | Quote besar angkatan dengan efek scroll reveal |
| **Stats Grid** | Counter animasi (124+ entitas, 34 wilayah, 2025 tahun) |
| **Lorong Kenangan** | Horizontal scroll galeri foto greyscale-to-color |
| **Manuskrip Sejarah** | Arsip dokumen dalam modal "Sovereign Archive" |
| **Para Kurator** | Grid kartu pengurus angkatan |
| **Panca Jiwa** | 5 pilar filosofi pesantren — klik membuka modal cinematic dengan animasi SVG unik per pilar |
| **Garis Waktu** | Timeline vertikal dengan dot connector |
| **Buku Tamu** | Form signature + swipe-to-seal (mobile) |
| **Birthday Notif** | Notifikasi popup untuk alumni yang berulang tahun hari ini |

**Teknologi**: GSAP, ScrollTrigger, Canvas API, Web Audio API (spatial sound)

### `fitur.php` — The Sovereign Vault (393 baris)
Dashboard hub berisi 12 kartu fitur dengan efek **magnetic 3D tilt** (perspective + rotateX/Y):

| Kartu | Route | Status |
|-------|-------|--------|
| Sovereign ID | `/sovereign` | ✅ Aktif |
| Profil Entitas | `/profil` | ✅ Aktif |
| Amanah & Wasiat | `/wasiat` | 🔲 Placeholder |
| Baitul Maal | `/baitul-maal` | 🔲 Placeholder |
| Majlis Syura | `/majlis` | 🔲 Placeholder |
| Tarbiyah Nexus | `/tarbiyah` | 🔲 Placeholder |
| Oracle's Vision | `/oracle` | ✅ Aktif |
| Protokol Multazam | `/multazam` | 🔲 Placeholder |
| Ruang Kontemplasi | `/kontemplasi` | 🔲 Placeholder |
| Celestial Codex | `/celestial` | ✅ Aktif |
| Divine Verse | `/divine` | ✅ Aktif |
| Enigma Vault | `/enigma` | ✅ Aktif |
| Genesis Core | `/genesis` | ✅ Aktif |

### `radar.php` — Global Radar 3D (327 baris)
Visualisasi globe 3D persebaran alumni menggunakan **Globe.gl**:
- Globe bumi dengan texture day/night sesuai tema
- Ring animasi pada setiap titik alumni
- Arc trails (comet effect) dari pusat ke setiap alumni
- Tombol sinkronisasi GPS real-time (Nominatim reverse geocoding)
- Tooltip interaktif (nama, kota, koordinat)
- Fallback ke peta datar 2D (`radar_flat.php`)

### `radar_flat.php` — Peta Datar 2D
Alternatif radar menggunakan Leaflet.js untuk perangkat low-end.

### `sovereign.php` — Sovereign Vault 3D (1002 baris)
Halaman **standalone** (tidak extend template) menggunakan **Three.js**:
- Render 3D kartu ID VVIP dengan material fisik (MeshPhysicalMaterial)
- Texture prosedural: brushed metal, smart chip, barcode, QR code
- Lanyard physics simulation (Bezier curve)
- KTA card terpisah (format landscape)
- Foto profil di-render ke dalam texture kartu
- QR code otomatis dari API qrserver.com
- Drag interaction dengan raycasting
- Marble background Kintsugi (emas retak)
- Dual theme (Noir Vault / Calacatta Gallery)
- Dust particle system (AdditiveBlending)

### `profil.php` — Sovereign Dossier (1128 baris)
Halaman profil pengguna dengan 2 fase:

**Fase 1: Biometric Gateway**
- Camera feed dengan efek Leica-style brackets
- Face detection via face-api.js
- Scan line animation + liveness indicator (3 step)
- Auto-proceed setelah verifikasi wajah berhasil

**Fase 2: Control Panel**
- Edit profil (nama, WA, email, IG, TikTok, motivasi, cita-cita)
- Upload foto profil (Base64 encoding)
- Floating label inputs dengan liquid-line animation
- Panel keamanan: status biometric + passkey enrollment
- Link ke Sovereign ID 3D
- Parallax tilt effect pada panel

### `profil_dossier.php` — Profil Publik
Halaman profil yang dapat diakses publik via QR code scan.

### `direktori.php` — The Archive (225 baris)
Direktori seluruh alumni menggunakan **Swiper.js** coverflow effect:
- Search pill dengan filter real-time
- Kartu luminary dengan foto, nama, serial number
- Detail reveal animasi: origin, lokasi, cita-cita, quote, sosmed
- Haptic feedback pada slide change
- Ethereal glow pedestal effect

### `oracle_vision.php` — The Oracle's Vision (460 baris)
Modul gamifikasi **standalone** — scanner aura:
- Camera feed dengan reticle logo overlay
- Fake scanning sequence (4 pesan + laser animation)
- 30 aura unik (The Tycoon, The Diplomat, The Architect, dll)
- Capture foto + CSS filter overlay sesuai aura
- Result overlay dengan animasi reveal

### `enigma_vault.php` — Enigma Vault (485 baris)
Puzzle **standalone** — brankas 3 ring:
- 3 ring konsentris yang bisa diputar (drag/touch)
- 10 simbol Romawi per ring
- Target solusi: Outer=IV, Middle=II, Inner=X
- Snap-to-grid rotation (36° per step)
- Unlock animation: ring scale-out + glow
- Success overlay dengan quote rahasia

### `divine_verse.php` — Kalam Ilahi (185 baris)
Modul spiritual **standalone**:
- 32 ayat Al-Quran (Arab, Latin, terjemahan, sumber)
- Islamic geometric pattern SVG background
- Gold particle field animation
- Reveal sequence: Bismillah → Ayat → Divider → Latin → Arti → Sumber
- Font Amiri untuk teks Arab

### `celestial_codex.php` — Celestial Codex
Modul tarik kartu takdir — 3 kartu dari dek misterius dengan animasi flip 3D.

### `genesis_core.php` — Genesis Core
Modul filosofi asal-usul dan fondasi spiritual Expedient Generation.

### `galeri.php` — Galeri Multimedia (56KB)
Galeri foto/video angkatan dengan layout masonry dan lightbox.

### `birthday.php` — Birthday Celebration
Halaman ucapan ulang tahun personal untuk alumni.

### `scan_gateway.php` — Scan Gateway (32 baris)
Pop-up gateway saat QR code discan — pilihan:
1. Inisialisasi AR Hologram
2. Ekstrak Data Kontak (vCard)

### `ar_hologram.php` — AR Hologram (28KB)
Halaman **standalone** Three.js untuk menampilkan hologram AR interaktif dari data alumni.

### `wasiat_vault.php`, `baitul_maal.php`, `majlis_syura.php`, `tarbiyah_nexus.php`, `protokol_multazam.php`, `ruang_kontemplasi.php`
Modul-modul placeholder yang sudah memiliki UI dasar namun belum fully functional.

---

## ⚙️ Controllers Detail

### `AuthController.php` (394 baris) — Pusat Otentikasi
| Method | Fungsi |
|--------|--------|
| `login()` | Tampilkan form login |
| `prosesLogin()` | Validasi kredensial + rate limiting (5x/5min) |
| `register()` | Tampilkan form registrasi |
| `prosesRegister()` | Validasi, hash password, simpan user, kirim email verifikasi |
| `verifyEmail()` | Verifikasi token email |
| `forgotPassword()` | Kirim kode reset 6-digit via SMTP |
| `verifyResetCode()` | Validasi kode reset |
| `resetPassword()` | Update password baru (bcrypt) |
| `logout()` | Destroy session |

### `BiometricController.php` (6.5KB) — WebAuthn/FIDO2
| Method | Fungsi |
|--------|--------|
| `registerOptions()` | Generate challenge untuk enrollment |
| `registerVerify()` | Verifikasi & simpan credential biometric |
| `loginOptions()` | Generate challenge untuk login |
| `loginVerify()` | Verifikasi signature biometric |

### `BerandaController.php` (89 baris) — Homepage
| Method | Fungsi |
|--------|--------|
| `index()` | Load data statistik, galeri, berita, kurator, timeline, birthday users |
| `simpan_pesan()` | Simpan buku tamu (validasi + insert ke `buku_tamu`) |

### `RadarController.php` (115 baris) — Geolocation
| Method | Fungsi |
|--------|--------|
| `index()` | Load globe 3D + data koordinat alumni |
| `flat()` | Load peta datar 2D (fallback) |
| `updateLocation()` | Update lat/lng user via AJAX (POST + CSRF) |

### `ProfileController.php` (3.2KB) — Manajemen Profil
| Method | Fungsi |
|--------|--------|
| `index()` | Load profil user + data face descriptor |
| `update()` | Update profil + foto Base64 (resize, sanitasi, simpan ke `uploads/profiles/`) |

### `SovereignController.php` — Sovereign ID 3D
Load data user + foto profil untuk render kartu 3D Three.js.

### `VaultController.php` (97 baris) — Gateway Publik
| Method | Fungsi |
|--------|--------|
| `profil($id)` | Profil publik (via QR scan) |
| `scan_gateway($id)` | Gateway pilihan AR/vCard |
| `ar_hologram($id)` | Hologram 3D |
| `download_vcard($id)` | Generate file .vcf otomatis |

### `SyndicateController.php` (3.8KB) — Bisnis Alumni
CRUD portofolio bisnis alumni dengan upload logo.

### `DirektoriController.php` — Arsip Alumni
Load seluruh data alumni untuk direktori Swiper.

### `GaleriController.php` — Galeri Multimedia
Manajemen foto/video angkatan.

### `BirthdayController.php` — Ulang Tahun
Deteksi dan tampilkan ucapan untuk alumni yang berulang tahun.

### Controller Modul Interaktif
`OracleController`, `EnigmaController`, `DivineController`, `CelestialController`, `GenesisController` — Masing-masing hanya me-return view standalone tanpa data dinamis.

### Controller Placeholder
`BaitulMaalController`, `MajlisController`, `MultazamController`, `TarbiyahController`, `WasiatController`, `KontemplasiController` — Struktur dasar, menunggu pengembangan.

---

## 🗄 Database & Migrasi

### Tabel `users` (Migrasi Utama)
```
id, nama_lengkap, nama_panggilan, jenis_kelamin, tempat_tanggal_lahir,
alamat_lengkap, email (UNIQUE), password_hash, no_whatsapp,
motivasi_hidup, cita_cita, akun_ig, akun_tiktok, foto_profil,
email_verify_token, email_verified_at, created_at, updated_at
```

### Tabel `user_biometrics` (FK → users)
```
id, user_id, credential_id (TEXT), public_key (TEXT), sign_count, created_at
```

### Migrasi Tambahan
| File | Fungsi |
|------|--------|
| `AddResetPasswordFields` | Tambah `reset_token`, `reset_expires` ke users |
| `AddMissingUserColumns` | Tambah `latitude`, `longitude`, `face_data`, `webauthn_credential_id` |
| `CreateSyndicateTable` | Tabel `syndicate` (bisnis alumni, FK → users) |
| `CreateBukuTamuTable` | Tabel `buku_tamu` (nama, pesan, created_at) |
| `AddTanggalLahirColumn` | Tambah `tanggal_lahir` (DATE) ke users |
| `AddTempatLahirColumn` | Tambah `tempat_lahir` (VARCHAR) ke users |

---

## 🌟 Fitur Utama

### 1. Otentikasi Multi-Layer
Login password + biometric WebAuthn + email verifikasi + forgot password

### 2. Museum Interaktif (Beranda)
192-frame sequence animation, 13 shard logo interaktif, Panca Jiwa modal, buku tamu swipe-to-seal

### 3. Global Radar
Globe 3D persebaran alumni + GPS sync + peta datar fallback

### 4. Sovereign ID 3D
Kartu identitas VVIP di-render Three.js dengan material fisik, foto, QR code, dan lanyard

### 5. Modul Gamifikasi
Oracle Vision (aura scanner), Enigma Vault (puzzle ring), Celestial Codex (tarik kartu), Genesis Core (filosofi)

### 6. Kalam Ilahi (Divine Verse)
32 ayat Al-Quran dengan tipografi Arab premium dan animasi reveal

### 7. Direktori Alumni
Swiper coverflow dengan search real-time dan reveal animasi

### 8. Birthday System
Auto-detect ulang tahun + notifikasi popup + halaman ucapan personal

### 9. The Syndicate
Portofolio bisnis alumni dengan CRUD dan relasi database

### 10. Notifikasi Real-time
Pusher/Soketi untuk notifikasi alumni baru bergabung

---

## 🧩 Service Layer

Business logic dipisahkan dari Controller ke dalam Service class di `app/Services/`:

| Service | Method | Fungsi |
|---------|--------|--------|
| **AuthService** | `attemptLogin()` | Verifikasi email + password + status verifikasi |
| | `registerUser()` | Hash password, generate token, simpan user, invalidate cache |
| | `sendVerificationEmail()` | Enqueue email verifikasi (async via EmailQueueService) |
| | `sendResetCode()` | Generate kode 6 digit, enqueue email reset |
| | `verifyResetCode()` | Validasi kode + cek expiry (15 menit) |
| | `resetPassword()` | Hash password baru, clear reset token |
| **ProfileService** | `processProfilePhoto()` | Validasi MIME (jpg/png/webp), cek ukuran (maks 2MB), hapus file lama, simpan baru |
| | `updateProfile()` | Update DB + invalidate cache `alumni_list` & `radar_nodes` |
| **PusherService** | `trigger()` | Kirim event real-time via Pusher/Soketi |
| | `notifyNewAlumni()` | Shortcut notifikasi alumni baru |
| | `sendChatMessage()` | Shortcut kirim pesan chat |
| **EmailQueueService** | `enqueue()` | Tambah email ke antrian database |
| | `processQueue()` | Proses batch email pending via SMTP |
| **AnalyticsService** | `getWeeklyTrend()` | Data chart pengunjung 7 hari |
| | `getTopPages()` | Halaman paling sering dikunjungi |
| **BerandaService** | `getDashboardData()` | Agregasi data beranda (stats, berita, birthday) |

---

## 🌐 REST API

Endpoint API terpisah di `app/Controllers/Api/` dengan format response JSON standar:

```json
{
  "status": "success | error",
  "message": "...",
  "data": { ... }
}
```

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `POST` | `/api/location/update` | `LocationApi` | Update koordinat GPS user |
| `GET` | `/api/biometric/login-options` | `BiometricApi` | Generate challenge login |
| `POST` | `/api/biometric/login-verify` | `BiometricApi` | Verifikasi signature biometric |
| `GET` | `/api/biometric/register-options` | `BiometricApi` | Generate challenge registrasi |
| `POST` | `/api/biometric/register-verify` | `BiometricApi` | Simpan credential biometric baru |

> Semua endpoint API dilindungi filter `auth` + `throttle`.

---

## 🛡️ Filter & Middleware

| Filter | File | Fungsi |
|--------|------|--------|
| `auth` | `AuthFilter.php` | Cek session `logged_in`, redirect ke `/login` jika belum |
| `throttle` | `ThrottleApiFilter.php` | Rate limiting: maks 10 req/menit per IP per endpoint, return JSON 429 |

Filter `throttle` diterapkan pada endpoint:
- `POST /radar/update-location`
- `POST /biometric/login-verify` & `register-verify`
- `POST /beranda/simpan_pesan`
- `POST /syndicate/store`
- `POST /profil/update`
- Seluruh group `/api/*`

---

## ⚡ Cache Strategy

Menggunakan CI4 file cache bawaan untuk mengurangi beban database:

| Cache Key | TTL | Controller | Invalidasi |
|-----------|-----|------------|------------|
| `alumni_count` | 15 menit | BerandaController | Saat registrasi baru (AuthController) |
| `alumni_list` | 10 menit | DirektoriController | Saat registrasi & update profil |
| `radar_nodes` | 5 menit | RadarController | Saat update lokasi |
| `birthday_today_{date}` | 1 jam | BerandaController | Otomatis per hari |

---

## 📊 Activity Logger

Semua aktivitas penting dicatat ke `writable/logs/activity-YYYY-MM-DD.log`:

```
Format: [TIMESTAMP] [USER:ID] [ACTION] [IP:ADDRESS] Detail
```

| Aksi | Dicatat Di | Kapan |
|------|-----------|-------|
| `LOGIN_SUCCESS` | AuthController | Login berhasil |
| `LOGIN_FAILED` | AuthController | Password/email salah |
| `REGISTER` | AuthController | Registrasi berhasil |
| `LOGOUT` | AuthController | User logout |
| `PASSWORD_RESET` | AuthController | Password berhasil di-reset |
| `PROFILE_UPDATE` | ProfileController | Data profil diubah |
| `PHOTO_UPLOAD` | ProfileController | Foto baru diupload |
| `PHOTO_CLEANUP` | ProfileController | Foto lama dihapus |
| `LOCATION_UPDATE` | RadarController | Koordinat GPS diperbarui |
| `BIOMETRIC_LOGIN` | BiometricApi | Login via biometric |
| `BIOMETRIC_ENROLL` | BiometricApi | Biometric baru didaftarkan |

---

## 🧪 Testing

Unit test tersedia di `tests/unit/`:

| File Test | Menguji |
|-----------|---------|
| `Services/AuthServiceTest.php` | Login valid/invalid, email belum verifikasi, reset code |
| `Services/ProfileServiceTest.php` | Validasi foto: format, MIME, ukuran maks |

```bash
# Jalankan semua test
php spark test

# Jalankan test spesifik
php spark test --filter AuthServiceTest
```

---

## 📦 Vendor (Local CDN Bundle)

Library pihak ketiga sudah di-download ke `public/vendor/` agar tidak bergantung CDN eksternal:

```
public/vendor/
├── gsap/gsap.min.js              # Animasi scroll & transisi
├── gsap/ScrollTrigger.min.js     # Scroll-based animation
├── swiper/swiper-bundle.min.js   # Carousel/slider
├── swiper/swiper-bundle.min.css
├── three/three.module.min.js     # 3D rendering (Sovereign Vault)
├── globe/globe.gl.min.js         # Globe 3D (Radar)
├── face-api/face-api.min.js      # Face detection/recognition
└── pusher/pusher.min.js          # Real-time notifications
```

> Untuk mengupdate: edit `scripts/download_vendor.php` lalu jalankan `php scripts/download_vendor.php`

---

## 🚀 Cara Menjalankan

```bash
# 1. Clone & masuk direktori
cd angkatan

# 2. Install dependencies
composer install

# 3. Copy environment
cp env .env

# 4. Konfigurasi .env
#    - database.default.hostname/database/username/password
#    - app.baseURL
#    - email.SMTPHost/SMTPUser/SMTPPass
#    - PUSHER_APP_KEY/SECRET/ID (opsional)
#    - NOMINATIM_EMAIL (untuk reverse geocoding)

# 5. Jalankan migrasi
php spark migrate

# 6. Download vendor (CDN lokal)
php scripts/download_vendor.php

# 7. Start server
php spark serve

# 8. (Opsional) Setup cron untuk email queue
# * * * * * cd /path/to/project && php spark email:process >> /dev/null 2>&1

# 9. (Opsional) Jalankan test
php spark test
```

### Konfigurasi Soketi (Opsional)
Jika ingin self-hosted WebSocket, uncomment variabel berikut di `.env`:
```env
PUSHER_HOST="your-server-ip"
PUSHER_PORT="6001"
PUSHER_SCHEME="https"
```

---

## 📁 Struktur File Penting

```
app/
├── Config/
│   ├── Routes.php              # 40+ route definitions + API group
│   └── Filters.php             # AuthFilter + ThrottleApiFilter
├── Controllers/
│   ├── Api/                    # REST API controllers
│   │   ├── BaseApiController.php
│   │   ├── LocationApi.php
│   │   └── BiometricApi.php
│   ├── AuthController.php      # Otentikasi (login/register/reset)
│   ├── BerandaController.php   # Dashboard + cache
│   ├── DirektoriController.php # Daftar alumni + cache
│   ├── ProfileController.php   # Manajemen profil + foto
│   ├── RadarController.php     # Globe/Peta + cache
│   └── ... (20 controller lainnya)
├── Filters/
│   ├── AuthFilter.php          # Middleware cek session
│   └── ThrottleApiFilter.php   # Rate limiting API
├── Libraries/
│   └── ActivityLogger.php      # Audit trail logger
├── Models/
│   └── UserModel.php           # Model utama data alumni
├── Services/
│   ├── AuthService.php         # Business logic otentikasi
│   └── ProfileService.php      # Business logic profil
└── Views/                      # 23 view files + subdirektori

public/
├── css/
│   └── design-system.css       # Shared styles (glassmorphism, buttons, dll)
├── vendor/                     # Library CDN lokal
├── images/                     # Logo, shard assets
├── uploads/profiles/           # Foto profil user
└── assets/sequence/            # Frame animasi logo

scripts/
└── download_vendor.php         # Script download CDN ke lokal

tests/unit/
└── Services/                   # Unit tests
    ├── AuthServiceTest.php
    └── ProfileServiceTest.php
```

---

## 📜 Lisensi

Proyek internal Expedient Generation — Angkatan ke-42 Pondok Modern Arrisalah.

---

> *"Kami bukan sekadar angkatan. Kami adalah barisan pelopor yang lahir dari rahim Arrisalah, dibentuk oleh waktu, dipersatukan oleh takdir."*

