const CACHE_NAME = "aula-ingles-v4";
const OFFLINE_URL = "/offline";

const STATIC_ASSETS = [
    "/offline",
    "/img/logo-ingles.png",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(STATIC_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    const req = event.request;
    const url = new URL(req.url);

    if (req.method !== "GET") return;

    const rutasBloqueadas = [
        "/",
        "/login",
        "/logout",
        "/iniciando",
        "/recuperar-contrasena",
        "/recuperar-password",
    ];

    if (rutasBloqueadas.some(r => url.pathname === r)) {
        event.respondWith(fetch(req));
        return;
    }

    if (url.pathname.startsWith("/build/")) {
        event.respondWith(
            caches.match(req).then(cached => {
                return cached || fetch(req).then(res => {
                    if (res.ok) {
                        caches.open(CACHE_NAME).then(cache =>
                            cache.put(req, res.clone())
                        );
                    }
                    return res;
                });
            })
        );
        return;
    }

    if (req.mode === "navigate") {
        event.respondWith(
            fetch(req).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    event.respondWith(
        caches.match(req).then(cached => {
            return cached ||
                fetch(req).then(res => {
                    if (res.ok) {
                        caches.open(CACHE_NAME).then(cache =>
                            cache.put(req, res.clone())
                        );
                    }
                    return res;
                }).catch(() => {
                    if (req.destination === "image") {
                        return caches.match("/img/logo-ingles.png");
                    }
                });
        })
    );
});
