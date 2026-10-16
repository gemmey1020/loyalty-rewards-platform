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
        Schema::create('invoice_edit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('edited_by')->constrained('users');
            $table->string('invoice_number')->nullable();
            $table->decimal('invoice_total', 10, 2);
            $table->date('invoice_date');
            $table->json('changes')->nullable();
            $table->string('action')->default('updated'); // 'updated', 'deleted', etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_edit_histories');
    }
};
