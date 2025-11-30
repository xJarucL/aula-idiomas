const CACHE_NAME = "aula-ingles-v1";
const OFFLINE_URL = "/offline";

const STATIC_ASSETS = [
    "/",
    "/offline",
    "/img/logo-ingles.png",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_NAME);

            await cache.addAll(STATIC_ASSETS);

            try {
                const res = await fetch("/");
                const html = await res.text();

                const viteAssets = [...html.matchAll(/\/build\/assets\/[^\"]+/g)].map(
                    (m) => m[0]
                );

                console.log("[SW] Detectados assets de Vite:", viteAssets);

                await cache.addAll(viteAssets);
            } catch (e) {
                console.warn("[SW] No pude precargar assets de Vite:", e);
            }
        })()
    );

    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            )
        )
    );

    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET") return;

    event.respondWith(
        caches.match(event.request).then((cached) => {
            return (
                cached ||
                fetch(event.request).catch(() => caches.match(OFFLINE_URL))
            );
        })
    );
});
