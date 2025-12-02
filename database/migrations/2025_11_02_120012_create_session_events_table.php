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
        Schema::create('session_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exam_sessions')->cascadeOnDelete();
            $table->enum('source', ['browser','camera','system']);
            $table->string('event_type',100);
            $table->json('details')->nullable();
            $table->enum('level', ['info','warning','critical'])->default('info');
            $table->dateTime('occurred_at');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_events');
    }
};
