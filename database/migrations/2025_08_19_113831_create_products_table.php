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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
            $table->string('product_id')->unique();
            $table->string('product_name');
            $table->string('product_line')->nullable();
            $table->string('product_type')->nullable();
            $table->string('model_color');
            $table->string('ram_rom')->nullable();
            $table->string('imei1')->unique();
            $table->string('imei2')->nullable()->unique();
            $table->string('box')->default('No');
            $table->string('body_condition')->default('Good');
            $table->string('screen_condition')->default('Good');
            $table->string('charger')->default('No');
            $table->string('camera_condition')->default('Good');
            $table->string('status')->default('Available');
            $table->date('date_entered');
            $table->text('notes')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('current_transaction_id')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamps(); // Kolom created_at & updated_at otomatis
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
