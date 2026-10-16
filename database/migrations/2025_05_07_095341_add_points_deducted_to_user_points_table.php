<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_points', function (Blueprint $table) {
            $table->integer('points_deducted')->default(0)->after('points');
        });
    }

    public function down(): void
    {
        Schema::table('user_points', function (Blueprint $table) {
            $table->dropColumn('points_deducted');
        });
    }
};
