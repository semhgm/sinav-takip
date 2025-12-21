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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('question_categories')->nullOnDelete();
            $table->enum('type', ['multiple_choice','open_ended'])->default('multiple_choice');
            $table->text('text');
            $table->json('options')->nullable();
            $table->string('correct_option', 255)->nullable();
            $table->decimal('points', 5, 2)->default(10);
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
