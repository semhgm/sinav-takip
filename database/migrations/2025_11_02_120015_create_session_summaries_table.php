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
        Schema::create('session_summaries', function (Blueprint $table) {
            $table->foreignId('session_id')->primary()->constrained('exam_sessions')->cascadeOnDelete();
            $table->decimal('integrity_score',5,2)->nullable();
            $table->unsignedInteger('total_events')->default(0);
            $table->unsignedInteger('critical_events')->default(0);
            $table->unsignedInteger('browser_events')->default(0);
            $table->unsignedInteger('camera_events')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_summaries');
    }
};
