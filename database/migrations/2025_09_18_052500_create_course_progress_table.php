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
        Schema::create('course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('progress_percentage')->default(0);
            $table->integer('videos_completed')->default(0);
            $table->integer('total_videos')->default(0);
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->json('video_progress')->nullable(); // Store individual video progress
            $table->timestamps();
            
            $table->unique(['user_id', 'course_id']);
            $table->index(['user_id', 'is_completed']);
            $table->index(['course_id', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_progress');
    }
};
