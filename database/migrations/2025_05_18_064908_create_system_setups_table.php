<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_setups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image_type')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('default')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_setups');
    }
};
