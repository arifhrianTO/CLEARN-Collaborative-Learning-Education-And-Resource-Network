import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
 export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#7C3AED',
                'dark-bg': '#0F0B1A',
                'dark-sidebar': '#1A1625',
                'dark-card': '#1A1625',
                'border-custom': '#2d2644',
                'deep-dark': '#020617',
                'card-dark': '#0f172a',
                'dark-card': '#111827',
                    }
                }
            },

    plugins: [],
};