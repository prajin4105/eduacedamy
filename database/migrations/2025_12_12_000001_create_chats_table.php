<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedInteger('student_unread_count')->default(0);
            $table->unsignedInteger('instructor_unread_count')->default(0);
            $table->timestamps();

            $table->unique(['course_id', 'student_id'], 'chats_course_student_unique');
            $table->index(['instructor_id', 'last_message_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
