<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('About ProSeed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    
                    <div>
                        <h3 class="text-lg font-bold text-yellow-500 mb-2">Apa itu ProSeed?</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            ProSeed adalah sistem manajemen aset internal yang dirancang untuk membantu tim melacak, mengelola, dan melaporkan semua aset fisik di berbagai proyek. Dengan fitur-fitur canggih seperti atribut produk dan kondisi yang dapat dikustomisasi per proyek, Proseed memberikan fleksibilitas penuh untuk menangani berbagai jenis inventaris.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-yellow-500 mb-2">Fitur Utama</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-600 dark:text-gray-400">
                            <li><strong>Manajemen Multi-Proyek:</strong> Pisahkan data, pengguna, dan pengaturan untuk setiap proyek.</li>
                            <li><strong>Atribut & Kondisi Dinamis:</strong> Definisikan field data unik untuk produk di setiap proyek.</li>
                            <li><strong>Pelacakan Riwayat (Journey):</strong> Lihat riwayat lengkap setiap produk dan partner.</li>
                            <li><strong>Sistem Tanda Terima:</strong> Generate dan kirim tanda terima peminjaman dan pengembalian secara otomatis.</li>
                            <li><strong>Ekspor ke Excel:</strong> Unduh data transaksi, produk, atau partner dengan mudah.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-yellow-500 mb-2">Tim Pengembang</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Aplikasi ini dirancang dan dikembangkan dengan bangga oleh <strong>Tim Data Burson Indonesia</strong> untuk menyederhanakan dan mengoptimalkan alur kerja manajemen aset internal.
                        </p>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-bold text-yellow-500 mb-2">Bantuan & Dukungan</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Jika Anda mengalami kendala teknis atau memiliki pertanyaan terkait penggunaan aplikasi, silakan hubungi kami di:
                        </p>
                        <p class="mt-2 font-semibold">
                            Lantai 36 RDTX Place, Jakarta.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
