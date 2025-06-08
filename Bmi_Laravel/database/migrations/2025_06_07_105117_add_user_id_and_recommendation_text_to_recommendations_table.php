<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('recommendations', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->after('id');
        $table->string('recommendation_text')->after('user_id');
    });
}

public function down()
{
    Schema::table('recommendations', function (Blueprint $table) {
        $table->dropColumn(['user_id', 'recommendation_text']);
    });
}
};
