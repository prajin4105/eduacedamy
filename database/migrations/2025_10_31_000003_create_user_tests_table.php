<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tests', function (Blueprint $table) {
            $table->id();   
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->unsignedTinyInteger('score')->default(0); // percentage 0-100
            $table->boolean('passed')->default(false);
            $table->timestamp('attempted_at');
            $table->timestamps();

            $table->unique(['user_id', 'test_id']); // one attempt per user per test
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tests');
    }
};


