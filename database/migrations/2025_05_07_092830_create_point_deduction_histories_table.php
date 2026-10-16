<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_deduction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_point_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('points_deducted');
            $table->string('reason')->nullable();
            $table->foreignId('deducted_by')->constrained('users')->onDelete('cascade'); // Admin who performed the deduction
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_deduction_histories');
    }
};
