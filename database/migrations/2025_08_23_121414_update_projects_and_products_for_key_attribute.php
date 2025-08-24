<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Perbarui tabel 'projects'
        Schema::table('projects', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropForeign(['display_attribute_id']);
            $table->dropColumn('display_attribute_id');

            // Tambahkan kolom baru untuk menyimpan NAMA dari atribut kunci
            $table->string('key_attribute_name')->default('Identifier')->after('description');
        });

        // 2. Perbarui tabel 'products'
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom baru untuk menyimpan NILAI dari atribut kunci
            $table->string('key_attribute_value')->after('product_name');

            // Buat agar nilainya unik HANYA di dalam satu proyek
            $table->unique(['project_id', 'key_attribute_value']);
        });
    }

    public function down(): void
    {
        // Logika untuk rollback jika diperlukan
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('key_attribute_name');
            $table->foreignId('display_attribute_id')->nullable()->constrained('product_attributes');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'key_attribute_value']);
            $table->dropColumn('key_attribute_value');
        });
    }
};