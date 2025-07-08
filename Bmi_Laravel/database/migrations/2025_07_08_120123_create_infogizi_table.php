<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infogizi', function (Blueprint $table) {
            $table->id();
            $table->decimal('calories', 10, 1);
            $table->decimal('proteins', 10, 1);
            $table->decimal('fat', 10, 1);
            $table->decimal('carbohydrate', 10, 1);
            $table->string('name', 255);
            $table->string('image', 1024);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infogizi');
    }
};