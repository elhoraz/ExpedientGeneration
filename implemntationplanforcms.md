# CMS Inline Editing — Rencana Penerapan Bertahap ke Seluruh Halaman

## Bugs Yang Sudah Diperbaiki (Step 0) ✅

| Bug | Penyebab | Solusi |
|-----|----------|--------|
| Logo loader membesar | `cms_image()` membungkus `<img>` dalam `<div>`, menghancurkan CSS `.loader-logo` | Redesign `cms_image()` agar output langsung `<img>` dengan class asli yang dipertahankan |
| Routes.php terduplikat | Script sebelumnya `Add-Content` menambah isi ganda | Rewrite seluruh file Routes.php bersih |

## Prinsip Desain Baru

> [!IMPORTANT]
> - `cms_text('key', 'default')` → Untuk teks pendek (judul, label, kalimat). Langsung output `<span contenteditable>` untuk admin.
> - `cms_html('key', 'default')` → Untuk konten HTML (paragraf dengan `<br>`, `<span class>`, dll). Output `<div contenteditable>` untuk admin.
> - `cms_image('key', 'url', 'original-class', 'alt="..."')` → Untuk gambar. **Mempertahankan class CSS asli!** Admin cukup klik gambar untuk mengganti.
> - `cms_raw('key', 'default')` → Untuk value mentah tanpa HTML wrapper (misal: `placeholder="..."`, `src="..."` di dalam atribut).

## Rencana Step-by-Step

### Step 1: Landing Page & Auth Pages
| File | Elemen yang di-CMS-kan |
|------|----------------------|
| `landing.php` | Hero title, hero subtitle, tagline, CTA button text, section titles, deskripsi fitur, gambar hero |
| `auth/login.php` | Judul halaman, subtitle, placeholder, pesan error custom |
| `auth/register.php` | Judul, subtitle, keterangan field |
| `auth/forgot_password.php` | Judul, instruksi |

### Step 2: Beranda & Galeri (Halaman Publik)
| File | Elemen yang di-CMS-kan |
|------|----------------------|
| `beranda.php` | ✅ Epigraph sudah. Tambah: section titles (Lorong Kenangan, Manuskrip Sejarah, Para Kurator, Garis Waktu, Buku Tamu), gambar kenangan, timeline text, Panca Jiwa labels, monumental text |
| `galeri.php` | Judul galeri, deskripsi, caption gambar |
| `direktori.php` | ✅ Empty state sudah. Tambah: placeholder search |
| `buku_tamu.php` | Judul, deskripsi |

### Step 3: Fitur-Fitur Utama (Part 1)
| File | Elemen yang di-CMS-kan |
|------|----------------------|
| `fitur.php` | Judul halaman, nama fitur, deskripsi fitur, icon labels |
| `oracle_vision.php` | Judul, deskripsi, instruksi |
| `enigma_vault.php` | Judul, deskripsi, instruksi teka-teki |
| `genesis_core.php` | Judul, deskripsi, instruksi |
| `celestial_codex.php` | Judul, deskripsi |

### Step 4: Fitur-Fitur Utama (Part 2)
| File | Elemen yang di-CMS-kan |
|------|----------------------|
| `majlis_syura.php` | Judul, deskripsi, label tombol |
| `tarbiyah_nexus.php` | Judul, deskripsi |
| `baitul_maal.php` | Judul, deskripsi, instruksi |
| `wasiat_vault.php` | Judul, deskripsi |
| `protokol_multazam.php` | Judul, deskripsi, doa |
| `ruang_kontemplasi.php` | Judul, deskripsi |
| `event.php` | Judul, deskripsi |

### Step 5: Fitur Spesial & Profil
| File | Elemen yang di-CMS-kan |
|------|----------------------|
| `profil.php` | ✅ Scan title/subtitle sudah. Tambah: judul panel, deskripsi keamanan, label-label |
| `sovereign.php` | Judul KTA, label |
| `scanner.php` | Judul, instruksi scan |
| `nexus.php` | Judul, deskripsi |
| `birthday.php` / `birthday_list.php` | Judul, deskripsi |
| `wrapped.php` | Judul, deskripsi |
| `chat.php` | Judul lounge, placeholder |
| `syndicate/*.php` | Judul, deskripsi, label form |

## Open Questions

> [!IMPORTANT]
> Setiap step akan saya kerjakan secara **terpisah** agar Anda bisa menguji per-batch.
> Mau saya mulai dari **Step 1 (Landing & Auth)** dulu, atau langkah mana yang lebih prioritas?
