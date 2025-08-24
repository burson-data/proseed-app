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
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom untuk Loan Receipt
            $table->string('loan_receipt_status')->default('Not Sent')->after('notes');
            $table->string('loan_receipt_path')->nullable()->after('loan_receipt_status');
            $table->string('loan_upload_token')->nullable()->unique()->after('loan_receipt_path');

            // Kolom untuk Return Receipt
            $table->string('return_receipt_status')->default('Not Uploaded')->after('return_notes');
            $table->string('return_receipt_path')->nullable()->after('return_receipt_status');
            $table->string('return_upload_token')->nullable()->unique()->after('return_receipt_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
