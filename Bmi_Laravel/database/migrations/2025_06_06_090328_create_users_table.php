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
       Schema::create('users', function (Blueprint $table) {
            $table->id(); // id: bigint, primary key, auto-increment
            $table->string('name')->nullable(); // name: string, nullable
            $table->string('email')->unique(); // email: string, unique
            $table->string('password'); // password: string
             $table->string('foto')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user'); // role: enum
            $table->rememberToken(); // untuk auth remember_me
            $table->timestamps(); // created_at & updated_at
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
