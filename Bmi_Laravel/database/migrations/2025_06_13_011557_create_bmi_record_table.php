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
            $table->decimal('height' )->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('bmi')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('category')->nullable();
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
        Schema::dropIfExists('bmi_record');
    }
};
