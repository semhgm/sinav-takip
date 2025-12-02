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
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->decimal('score',5,2)->nullable();
            $table->enum('status', ['started','completed','graded'])->default('started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
