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
        Schema::table('bmi_records', function (Blueprint $table) {
        $table->string('activity_level')->after('gender');
        $table->decimal('tdee', 8, 2)->nullable()->after('bmr');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('bmi_records', function (Blueprint $table) {
        $table->dropColumn(['activity_level', 'tdee']);
    });
    }
};
