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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // ID pengguna target (jika notifikasi personal), nullable jika untuk semua
            $table->string('type'); // Contoh: 'new_article', 'new_recommendation', 'achievement'
            $table->string('title');
            $table->text('subtitle');
            $table->unsignedBigInteger('related_id')->nullable(); // ID artikel atau rekomendasi yang terkait
            $table->string('related_type')->nullable(); // 'article' atau 'recommendation'
            $table->boolean('is_read')->default(false); // Status sudah dibaca atau belum
            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
