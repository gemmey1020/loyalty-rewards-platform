<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
            $table->decimal('points', 10, 2)->change();
            $table->decimal('cashback', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->integer('amount');
            $table->integer('points');
            $table->integer('cashback');
        });
    }
};
