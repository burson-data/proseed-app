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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('name'); // Contoh: 'Warna', 'Ukuran Layar', 'Garansi Hingga'
            $table->string('type'); // Contoh: 'text', 'date', 'dropdown', 'photo'
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable(); // Untuk menyimpan pilihan dropdown, misal: ["Merah", "Biru"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
