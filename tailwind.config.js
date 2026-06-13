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
                'dark-bg': '#090613',
                'dark-sidebar': '#120d22',
                'dark-card': '#17122b',
                'border-custom': '#2d2644',
                'deep-dark': '#020617',
                'card-dark': '#0f172a',
                'dark-card': '#111827',
                    }
                }
            },

    plugins: [],
};