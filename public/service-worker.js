const CACHE_NAME = 'expedient-vvip-cache-v1';

// Daftar URL dan aset statis yang akan langsung disimpan ke cache saat PWA diinstal
const PRECACHE_ASSETS = [
  '/',
  '/offline',
  '/css/styles.css',
  '/css/sovereign.css',
  '/css/wasiat.css',
  '/images/logo-utuh.png',
  '/images/bg-pattern.png'
];

// Event INSTALL: Membuka cache dan menyimpan aset utama
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[Service Worker] Precaching Offline Page and Assets');
      return cache.addAll(PRECACHE_ASSETS);
    }).then(() => self.skipWaiting())
  );
});

// Event ACTIVATE: Membersihkan cache versi lama jika ada pembaruan versi (v2, v3, dst)
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (key !== CACHE_NAME) {
          console.log('[Service Worker] Removing old cache', key);
          return caches.delete(key);
        }
      }));
    }).then(() => self.clients.claim())
  );
});

// Event FETCH: Strategi "Network First, falling back to Cache"
self.addEventListener('fetch', (event) => {
  // Hanya proses request jenis GET
  if (event.request.method !== 'GET') return;

  event.respondWith(
    // Langkah 1: Coba ambil data terbaru dari jaringan/internet
    fetch(event.request)
      .then((networkResponse) => {
        // Jika berhasil, clone response dan simpan ke cache untuk akses offline berikutnya
        return caches.open(CACHE_NAME).then((cache) => {
          // Jangan menyimpan respons ekstensi chrome atau skema non-http(s)
          if(event.request.url.startsWith('http')){
              cache.put(event.request, networkResponse.clone());
          }
          return networkResponse;
        });
      })
      .catch(() => {
        // Langkah 2: Jika jaringan gagal (OFFLINE), cari file yang dicari di dalam Cache
        return caches.match(event.request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse; // Kembalikan dari cache (misal: gambar/CSS yg sudah tersimpan)
          }
          
          // Langkah 3: Jika file tidak ada di cache (misal: membuka halaman /beranda saat offline pertama kali)
          // Kembalikan ke Halaman Mode Offline Khusus
          if (event.request.headers.get('accept').includes('text/html')) {
            return caches.match('/offline');
          }
        });
      })
  );
});
