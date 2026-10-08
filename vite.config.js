import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // Saat dev server, url('/fonts/..'), '/images/..', '/storage/..' pada CSS/HTML
        // di-resolve ke origin Vite (5173). Proxy-kan ke Laravel supaya asset tetap ada.
        proxy: Object.fromEntries(
            ['/fonts', '/images', '/storage', '/favicon.ico'].map((path) => [
                path,
                { target: process.env.VITE_PROXY_TARGET || 'http://127.0.0.1:8000', changeOrigin: true },
            ]),
        ),
    },
});
