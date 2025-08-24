import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Mengaktifkan dark mode berbasis class

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // --- PALET WARNA BARU KITA ---
            colors: {
                // Warna untuk Light Mode
                'light-bg': '#f2f3f5',      // Latar belakang utama (abu-abu sangat terang)
                'light-surface': '#FFFFFF', // Latar belakang kartu/panel (putih)
                'light-header': '#000000',  // Latar belakang header (hitam)
                'light-text': '#111827',     // Teks utama (hitam)
                'light-text-darker': '#374151', // Teks lebih gelap untuk form (abu-abu gelap)
                'light-text-muted': '#6B7280', // Teks sekunder (abu-abu)
                'light-header-text': '#FFFFFF', // Teks di header (putih)

                // Warna untuk Dark Mode (Tampilan Baru)
                'dark-bg': '#1F2937',        // Latar belakang utama (abu-abu gelap)
                'dark-surface': '#374151',      // Latar belakang kartu/panel (abu-abu lebih terang)
                'dark-text': '#F3F4F6',      // Teks utama (putih pudar)
                'dark-text-muted': '#9CA3AF', // Teks sekunder (abu-abu terang)

                // Warna Aksen & Aksi (berlaku untuk kedua mode)
                'accent': '#ffd601',         // Kuning terang (amber-500)
                'action': '#3B82F6',         // Biru muda (blue-500)
                'success': '#10B981',        // Hijau (emerald-500)
                'danger': '#EF4444',         // Merah (red-500)
                'border-color': 'rgba(128, 128, 128, 0.2)', // Warna border transparan
            },
        },
    },

    plugins: [forms],
};
