import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

// Get a list of all CSS files in the resources/css/ directory
const cssFiles = fs.readdirSync(path.resolve(__dirname, 'resources/css')).map(file => `resources/css/${file}`);

// Get a list of all JavaScript files in the resources/js/ directory
const jsFiles = fs.readdirSync(path.resolve(__dirname, 'resources/js')).map(file => `resources/js/${file}`);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/department.js',
                ...cssFiles,
                ...jsFiles,
            ],
            refresh: true,
        }),
    ],
});

