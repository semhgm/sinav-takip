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
        Schema::create('question_scoring', function (Blueprint $table) {
            $table->foreignId('question_id')->primary()->constrained('questions')->cascadeOnDelete();
            $table->decimal('max_score',5,2)->default(1.00);
            $table->boolean('partial_credit')->default(false);
            $table->decimal('negative_score',5,2)->default(0.00);
            $table->json('rubric')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_scoring');
    }
};
