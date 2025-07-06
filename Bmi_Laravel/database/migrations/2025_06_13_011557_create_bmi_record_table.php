<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bmi_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('height', 8, 2);
            $table->decimal('weight', 8, 2); 
            $table->integer('age'); // Ubah menjadi not nullable jika usia selalu ada
            $table->string('gender'); // Ubah menjadi string
            $table->string('activity_level');
            $table->decimal('bmi', 8, 2); // Lebih baik spesifikkan precision dan scale
            $table->decimal('bmr', 8, 2); // Tambahkan kolom BMR
            $table->decimal('tdee', 8, 2)->nullable();
            $table->string('category');
            $table->timestamp('recorded_at')->nullable(); 

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bmi_records'); // Perbaiki typo
    }
};