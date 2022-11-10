import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/filament/filament-stimulus.js',
                'resources/filament/filament-turbo.js',
                'resources/css/app.css',
                'resources/js/app.js',
                // 'resources/css/filament.css',
            ],
            refresh: true,
        }),
    ],
});
