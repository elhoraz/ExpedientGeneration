const CACHE_NAME = 'expedient-vvip-cache-v3';
const urlsToCache = [
  '/manifest.json',
  '/images/logo-utuh.png',
  '/offline',
  'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.filter(name => name !== CACHE_NAME)
          .map(name => caches.delete(name))
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  // KHUSUS UNTUK HALAMAN PHP (HTML): Gunakan strategi Network-First
  // Ini WAJIB untuk mencegah "The action you requested is not allowed" akibat CSRF Token kadaluarsa yang tersimpan di cache
  if (event.request.mode === 'navigate' || event.request.method !== 'GET') {
    event.respondWith(
      fetch(event.request).catch(() => {
        return caches.match('/offline');
      })
    );
    return;
  }

  // Untuk Aset Statis (Gambar, CSS, JS): Gunakan strategi Cache-First
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});
