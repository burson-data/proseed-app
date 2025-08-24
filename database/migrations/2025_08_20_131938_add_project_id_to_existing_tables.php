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
            $table->foreignId('project_id')->after('id')->constrained('projects')->onDelete('cascade');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->foreignId('project_id')->after('id')->constrained('projects')->onDelete('cascade');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('project_id')->after('id')->constrained('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('existing_tables', function (Blueprint $table) {
            //
        });
    }
};
