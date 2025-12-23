const CACHE_NAME = 'hangar-gym-v1';
const urlsToCache = [
  'index.php',
  'css/style.css',
  'uploads/default.png'
];

// Yükleme (Install) Olayı
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Çalıştırma (Fetch) Olayı
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request);
    })
  );
});