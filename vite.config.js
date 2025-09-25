import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { createHtmlPlugin } from 'vite-plugin-html';
import vueDevTools from 'vite-plugin-vue-devtools';

// Export the Vite configuration
export default defineConfig({
    plugins: [
        // Laravel plugin for handling asset compilation and live reload
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true, // Ensures the page reloads on changes
        }),

        // Enables Vue DevTools in development
        vueDevTools(),

        // Plugin for injecting HTML tags dynamically (e.g., title, meta tags)
        createHtmlPlugin({
            inject: {
                // You can add dynamic title or any other HTML tag you need here
                injectData: {
                    title: 'Your Project Title',
                },
            },
        }),

        // Vue plugin for handling .vue components
        vue({
            template: {
                transformAssetUrls: {
                    base: null, // Ensures relative URLs are maintained
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        hmr: {
            host: 'localhost', // Enabling Hot Module Replacement (HMR) on localhost
        },
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js', // Resolving Vue for bundler compatibility
        },
    },
});
    