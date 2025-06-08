<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id'); // Kolom untuk relasi user
            $table->string('title')->after('user_id');         // Kolom untuk judul
            $table->text('content')->after('title');          // Kolom untuk konten
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'title', 'content']);
        });
    }
};