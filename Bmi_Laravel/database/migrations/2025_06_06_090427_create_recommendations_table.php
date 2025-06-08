<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRecommendations extends Migration
{
    public function up()
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->text('recommendation_text')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Kolom untuk relasi user
            $table->string('recommendation_text'); // Kolom untuk teks rekomendasi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'recommendation_text']);
        });
    }
}