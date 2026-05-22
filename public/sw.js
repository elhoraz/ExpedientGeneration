const CACHE_NAME = 'expedient-cache-v3';
const OFFLINE_URL = '/offline';

// Aset statis yang akan langsung disimpan ke cache saat PWA diinstal
const PRECACHE_ASSETS = [
  '/',
  OFFLINE_URL,
  '/manifest.json',
  '/css/design-system.css',
  '/css/template.css',
  '/images/logo-utuh.png',
  'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Inter:wght@300;400;500;600;700&display=swap',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

// Event INSTALL: Menyimpan aset utama ke cache
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Service Worker] Precaching assets');
        return cache.addAll(PRECACHE_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

// Event ACTIVATE: Membersihkan cache versi lama
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('[Service Worker] Removing old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Event FETCH: Strategy "Network First, falling back to Cache"
self.addEventListener('fetch', event => {
  // Hanya proses request GET
  if (event.request.method !== 'GET') return;

  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Cache successful responses
        if (response.status === 200) {
          const responseClone = response.clone();
          // Hanya cache request http/https (bukan chrome-extension dll)
          if (event.request.url.startsWith('http')) {
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseClone);
            });
          }
        }
        return response;
      })
      .catch(() => {
        // Network gagal — coba ambil dari cache
        return caches.match(event.request).then(cachedResponse => {
          if (cachedResponse) {
            return cachedResponse;
          }

          // Jika navigasi dan tidak ada di cache, tampilkan halaman offline
          if (event.request.mode === 'navigate' || 
              (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'))) {
            return caches.match(OFFLINE_URL);
          }

          // Untuk asset lain yang gagal, return 404
          return new Response('', { status: 404, statusText: 'Not Found' });
        });
      })
  );
});

// ==========================================
// WEB PUSH API: Handle Incoming Notifications
// ==========================================
self.addEventListener('push', function(event) {
    if (event.data) {
        const payload = event.data.json();
        
        const options = {
            body: payload.body || 'Ada pembaruan baru dari The Vault.',
            icon: '/images/logo-utuh.png',
            badge: '/images/logo-utuh.png',
            vibrate: [100, 50, 100],
            data: {
                url: payload.url || '/'
            },
            actions: [
                { action: 'open', title: 'Buka Sekarang' }
            ]
        };

        event.waitUntil(
            self.registration.showNotification(payload.title || 'Expedient Notification', options)
        );
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const urlToOpen = event.notification.data.url;
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(windowClients => {
            for (let client of windowClients) {
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});
