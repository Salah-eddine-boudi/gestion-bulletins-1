// vite.config.js
import { defineConfig } from 'vite';
import laravel          from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            /**  tableau d’entrées — PAS d’objet  */
            input: [
                'resources/css/app.css',    'resources/js/app.js',

                'resources/css/public.css', 'resources/js/public.js',
                'resources/css/admin.css',  'resources/js/admin.js',
            ],
            refresh: true,       // Live-reload
        }),
    ],
});
