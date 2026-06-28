import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Allow connections from all network interfaces
        cors: true,      // Enable CORS for Ngrok
        hmr: {
            host: 'localhost', // Keep HMR working properly on local
        },
    },
});
