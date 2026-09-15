import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Segoe UI"', 'Tahoma', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                be: {
                    sidebar: '#1a2b44',
                    accent: '#2f6fed',
                },
            },
        },
    },

    plugins: [forms],
};
