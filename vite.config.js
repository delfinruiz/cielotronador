import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/admin/theme.css'],
            refresh: true,
            fonts: [
                bunny('Poppins', {
                    weights: [400, 500, 600, 700],
                }),
                bunny('Oswald', {
                    weights: [500, 600, 700],
                }),
                bunny('Inter', {
                    weights: [400, 600, 700],
                }),
                bunny('Montserrat', {
                    weights: [400, 600, 700],
                }),
                bunny('Lato', {
                    weights: [400, 700],
                }),
                bunny('Raleway', {
                    weights: [400, 600, 700],
                }),
                bunny('Playfair Display', {
                    weights: [400, 600, 700],
                }),
                bunny('Roboto Slab', {
                    weights: [400, 700],
                }),
                bunny('Bebas Neue', {
                    weights: [400],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
