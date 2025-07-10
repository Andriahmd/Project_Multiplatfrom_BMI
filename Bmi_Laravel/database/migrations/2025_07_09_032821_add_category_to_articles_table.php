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
        Schema::table('articles', function (Blueprint $table) {
            // Cek jika kolom 'category' belum ada sebelum menambahkannya
            if (!Schema::hasColumn('articles', 'category')) {
                $table->string('category')->after('content')->nullable(); // Tambahkan kolom category
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};