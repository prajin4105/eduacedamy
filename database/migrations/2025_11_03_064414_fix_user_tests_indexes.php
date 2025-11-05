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
    Schema::table('user_tests', function (Blueprint $table) {
        // 1) Drop FKs first (use column-based API so names don’t matter)
        try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
        try { $table->dropForeign(['test_id']); } catch (\Throwable $e) {}

        // 2) Drop the composite unique on (user_id, test_id)
        try { $table->dropUnique(['user_id', 'test_id']); } catch (\Throwable $e) {}

        // 3) Re-add the FKs
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');

        // 4) Optional: add a normal (non-unique) composite index to keep lookups fast
        try { $table->index(['user_id', 'test_id']); } catch (\Throwable $e) {}
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_tests', function (Blueprint $table) {
            //
        });
    }
};
