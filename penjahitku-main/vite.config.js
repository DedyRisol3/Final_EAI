import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    
    // === TAMBAHKAN BLOK INI ===
    server: {
        host: '127.0.0.1',
    },
    // === AKHIR TAMBAHAN ===

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});