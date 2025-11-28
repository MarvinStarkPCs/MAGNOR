import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta de colores MAGNOR - Plataforma de Reciclaje
                primary: {
                    50: '#e8f5ed',
                    100: '#c2e4d1',
                    200: '#9ad3b5',
                    300: '#72c199',
                    400: '#4ab07d',
                    500: '#146e39', // Verde principal - Reciclaje
                    600: '#115e30',
                    700: '#0e4e27',
                    800: '#0b3e1e',
                    900: '#082e15',
                },
                secondary: {
                    50: '#e6f1f7',
                    100: '#b8d9e8',
                    200: '#8ac1d9',
                    300: '#5ca9ca',
                    400: '#2e91bb',
                    500: '#276691', // Azul - Profesionalismo
                    600: '#21567a',
                    700: '#1b4663',
                    800: '#15364c',
                    900: '#0f2635',
                },
                accent: {
                    50: '#fef5e7',
                    100: '#fde3bc',
                    200: '#fcd191',
                    300: '#fbbf66',
                    400: '#faad3b',
                    500: '#f78921', // Amarillo/Naranja - Alertas
                    600: '#d9771d',
                    700: '#bb6519',
                    800: '#9d5315',
                    900: '#7f4111',
                },
                danger: {
                    50: '#fce8e9',
                    100: '#f7bdc0',
                    200: '#f29297',
                    300: '#ed676e',
                    400: '#e83c45',
                    500: '#cc2128', // Rojo - Errores/Eliminación
                    600: '#b11d23',
                    700: '#96191e',
                    800: '#7b1519',
                    900: '#601114',
                },
                // Alias para mantener compatibilidad
                success: {
                    50: '#e8f5ed',
                    100: '#c2e4d1',
                    200: '#9ad3b5',
                    300: '#72c199',
                    400: '#4ab07d',
                    500: '#146e39', // Mismo que primary (verde)
                    600: '#115e30',
                    700: '#0e4e27',
                    800: '#0b3e1e',
                    900: '#082e15',
                },
                dark: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
            },
        },
    },

    plugins: [forms],
};
