<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('How to Use ProSeed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-surface dark:bg-dark-surface overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-light-text dark:text-dark-text space-y-10">
                    
                    <div>
                        <h3 class="text-xl font-bold text-accent mb-3">1. Memilih Ruang Kerja (Proyek)</h3>
                        <p class="text-light-text-muted dark:text-dark-text-muted">
                            Setiap kali Anda login, Anda harus memilih **Proyek** yang ingin Anda masuki. Anggap saja setiap proyek adalah "ruang kerja" yang terpisah. Semua data yang Anda lihat dan kelola—seperti Produk, Partner, dan Request—sepenuhnya terisolasi di dalam proyek yang sedang aktif. Ini memastikan data tidak tercampur.
                        </p>
                        <ul class="list-disc list-inside mt-2 text-sm text-light-text-muted dark:text-dark-text-muted">
                            <li>Jika Anda hanya ditugaskan ke satu proyek, sistem akan otomatis memilihnya untuk Anda.</li>
                            <li>Untuk berpindah proyek, gunakan menu dropdown di pojok kanan atas navigasi.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-accent mb-3">2. Mengelola Data Master (Produk & Partner)</h3>
                        <p class="text-light-text-muted dark:text-dark-text-muted">
                            Sebelum membuat request, pastikan data master Anda sudah lengkap.
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-light-text-muted dark:text-dark-text-muted">
                            <li><strong>Menambah Data:</strong> Gunakan tombol "Add New Product" atau "Add New Partner". Anda akan diminta mengisi field-field yang telah didefinisikan oleh Admin untuk proyek tersebut, termasuk **Atribut Kunci** yang unik (seperti IMEI atau Nomor Seri) dan kondisi awal produk.</li>
                            <li><strong>Melihat Detail:</strong> Cukup klik di mana saja pada baris tabel untuk membuka halaman detail yang menampilkan semua informasi, termasuk foto dan file yang diunggah.</li>
                            <li><strong>Mengelola Data:</strong> Gunakan menu "Actions" di setiap baris untuk **Mengedit**, melihat **Journey** (riwayat), atau **Menonaktifkan** (Deactivate) data yang sudah tidak relevan.</li>
                            <li><strong>Filter & Pencarian:</strong> Gunakan kotak pencarian dan centang "Show Inactive" untuk menemukan data yang Anda butuhkan dengan cepat.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-accent mb-3">3. Alur Kerja Request (Siklus Peminjaman)</h3>
                        <p class="text-light-text-muted dark:text-dark-text-muted">
                            Ini adalah alur kerja utama untuk melacak peminjaman aset dari awal hingga akhir.
                        </p>
                        <ol class="list-decimal list-inside space-y-3 mt-3 text-light-text-muted dark:text-dark-text-muted">
                            <li>
                                <strong>Buat Request Baru:</strong>
                                <p class="text-sm pl-2">Di halaman "Requests", klik "Create New Request". Gunakan dropdown pencarian untuk memilih produk yang tersedia, partner, dan tugaskan satu atau lebih konsultan.</p>
                            </li>
                            <li>
                                <strong>Kirim Tanda Terima Peminjaman (Loan Receipt):</strong>
                                <p class="text-sm pl-2">Setelah request dibuat, cari di halaman "Active Requests". Gunakan menu "Actions" untuk "Send Receipt Email". Pihak eksternal akan menerima email berisi PDF dan link unik untuk mengunggah bukti yang sudah ditandatangani.</p>
                            </li>
                            <li>
                                <strong>Proses Pengembalian Fisik:</strong>
                                <p class="text-sm pl-2">Saat barang kembali, cari request-nya di "Active Requests" dan pilih "Process Return". Di sini, Anda mencatat tanggal kembali dan kondisi terakhir barang. Status transaksi akan berubah menjadi `Awaiting Receipt Upload`.</p>
                            </li>
                            <li>
                                <strong>Kirim Tanda Terima Pengembalian (Return Receipt):</strong>
                                <p class="text-sm pl-2">Setelah memproses pengembalian, kirim "Return Receipt" dari menu "Actions" di halaman History. Pihak eksternal akan menerima email untuk mengunggah bukti pengembalian.</p>
                            </li>
                            <li>
                                <strong>Transaksi Selesai:</strong>
                                <p class="text-sm pl-2">Setelah bukti pengembalian diunggah, status transaksi akan otomatis menjadi `Completed`, dan status produk kembali menjadi `Available`. Transaksi ini sekarang secara permanen tercatat di halaman "History".</p>
                            </li>
                        </ol>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-border-color pt-6">
                        <h3 class="text-xl font-bold text-accent mb-3">Frequently Asked Questions (FAQ)</h3>
                        <div class="space-y-4 mt-4">
                            <div>
                                <h4 class="font-semibold text-light-text dark:text-dark-text">Mengapa data Produk/Partner saya tidak muncul?</h4>
                                <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Pastikan Anda berada di dalam Proyek yang benar. Data di ProSeed terisolasi per proyek. Gunakan "Project Switcher" di pojok kanan atas untuk berpindah. Jika data yang Anda cari tidak aktif, jangan lupa centang "Show Inactive".</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-light-text dark:text-dark-text">Mengapa saya tidak bisa melihat menu "Manage Projects"?</h4>
                                <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Menu "Manage Projects" hanya bisa diakses oleh pengguna dengan peran 'Admin'. Jika Anda merasa seharusnya memiliki akses, silakan hubungi administrator sistem.</p>
                            </div>
                             <div>
                                <h4 class="font-semibold text-light-text dark:text-dark-text">Bagaimana cara mengubah field kustom (Atribut/Kondisi) untuk sebuah proyek?</h4>
                                <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Hanya Admin yang bisa melakukannya. Masuk ke menu "Manage Projects", klik "Edit" pada proyek yang diinginkan, lalu Anda bisa menambah, mengubah, atau menghapus Atribut dan Kondisi di bagian bawah halaman.</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-light-text dark:text-dark-text">Bagaimana cara menghapus data (Produk, Partner, Proyek)?</h4>
                                <p class="text-sm text-light-text-muted dark:text-dark-text-muted">Untuk menjaga integritas data, sebagian besar item di ProSeed tidak dihapus secara permanen, melainkan dinonaktifkan (Deactivate). Gunakan menu "Actions" di setiap baris tabel untuk menonaktifkan Produk atau Partner. Untuk Proyek, hanya Admin yang bisa menonaktifkannya dari menu "Manage Projects". Data transaksi tidak bisa dihapus, tetapi akan otomatis masuk ke History setelah selesai.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
