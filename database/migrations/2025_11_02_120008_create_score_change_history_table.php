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
        Schema::create('score_change_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('student_answers')->cascadeOnDelete();
            $table->decimal('old_score',5,2)->nullable();
            $table->decimal('new_score',5,2)->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('acted_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_change_history');
    }
};
