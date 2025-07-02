import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Allows access from outside the container
        port: 5173,     // Default Vite development server port
        hmr: {
            clientPort: 5173,
            host: 'localhost' // Or your Docker host IP if needed
        }
    },
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
