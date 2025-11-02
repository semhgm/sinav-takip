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
            Schema::create('session_heartbeats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('session_id')->constrained('exam_sessions')->cascadeOnDelete();
                $table->dateTime('beat_at');
                $table->unsignedInteger('latency_ms')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_heartbeats');
    }
};
