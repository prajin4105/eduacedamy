<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // USERS table: add soft delete
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        // COURSES table: add soft delete + fix FK constraint
        Schema::table('courses', function (Blueprint $table) {
            // make instructor_id nullable (needed for SET NULL)
            $table->unsignedBigInteger('instructor_id')->nullable()->change();

            // drop old FK
            $table->dropForeign(['instructor_id']);

            // add FK with ON DELETE SET NULL
            $table->foreign('instructor_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropSoftDeletes();

            // drop new FK
            $table->dropForeign(['instructor_id']);

            // revert column to NOT NULL (if needed)
            $table->unsignedBigInteger('instructor_id')->nullable(false)->change();

            // add old FK (no cascade)
            $table->foreign('instructor_id')
                ->references('id')
                ->on('users');
        });
    }
};
