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
        Schema::table('projects', function (Blueprint $table) {
            // Kolom ini akan menyimpan ID dari product_attributes yang dipilih
            $table->foreignId('display_attribute_id')->nullable()->after('description')->constrained('product_attributes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['display_attribute_id']);
            $table->dropColumn('display_attribute_id');
        });
    }
};
