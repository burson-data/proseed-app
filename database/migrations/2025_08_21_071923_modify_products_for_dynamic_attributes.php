<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. Tambahkan kolom JSON untuk menyimpan data atribut
            $table->json('attributes')->nullable()->after('status');

            // 2. Hapus kolom-kolom lama yang tidak lagi diperlukan
            $table->dropColumn([
                'product_line',
                'product_type',
                'model_color',
                'ram_rom',
                'imei1',
                'imei2',
                'box',
                'body_condition',
                'screen_condition',
                'charger',
                'camera_condition',
                'date_entered',
                'notes',
                'photo_url',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
