<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_tests', function (Blueprint $table) {
            if (Schema::hasColumn('user_tests', 'score')) {
                $table->unsignedTinyInteger('score')->default(0)->change();
            }
            $table->unsignedTinyInteger('attempt_number')->default(1)->after('passed');
            $table->unsignedSmallInteger('num_correct')->default(0)->after('score');
            $table->unsignedSmallInteger('num_questions')->default(0)->after('num_correct');
            $table->json('answers')->nullable()->after('attempt_number');
        });

        // Drop unique index to allow multiple attempts
        try {
            Schema::table('user_tests', function (Blueprint $table) {
                $table->dropUnique('user_tests_user_id_test_id_unique');
            });
        } catch (\Throwable $e) {
            // index may not exist or driver may name it differently
        }
    }

    public function down(): void
    {
        Schema::table('user_tests', function (Blueprint $table) {
            if (Schema::hasColumn('user_tests', 'answers')) {
                $table->dropColumn(['answers']);
            }
            if (Schema::hasColumn('user_tests', 'attempt_number')) {
                $table->dropColumn(['attempt_number']);
            }
            if (Schema::hasColumn('user_tests', 'num_correct')) {
                $table->dropColumn(['num_correct']);
            }
            if (Schema::hasColumn('user_tests', 'num_questions')) {
                $table->dropColumn(['num_questions']);
            }
        });

        // Cannot safely recreate unique without data loss context; skip.
    }
};


