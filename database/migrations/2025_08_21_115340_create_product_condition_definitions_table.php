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
        Schema::create('product_condition_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('name'); // Contoh: 'Kondisi Box', 'Kesehatan Baterai'
            $table->string('type'); // 'text' atau 'dropdown'
            $table->json('options')->nullable(); // Pilihan untuk dropdown, misal: ["Baik", "Lecet", "Rusak"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_condition_definitions');
    }
};
