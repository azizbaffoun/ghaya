import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/order-management.js',
                'resources/js/live-search.js',
                'resources/js/product-modal.js',
                'resources/js/category-modal.js',
                'resources/js/website-management.js',
            ],
            refresh: false,
        }),
    ],
});
