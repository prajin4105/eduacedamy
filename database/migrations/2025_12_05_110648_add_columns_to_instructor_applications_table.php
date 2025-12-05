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
        Schema::table('instructor_applications', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->text('bio')->nullable()->after('user_id');
            $table->string('portfolio_url', 255)->nullable()->after('bio');
            $table->string('document_path', 255)->nullable()->after('portfolio_url');
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending')->after('document_path');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('applied_at')->nullable()->after('rejection_reason');
            $table->timestamp('reviewed_at')->nullable()->after('applied_at');
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructor_applications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'user_id',
                'bio',
                'portfolio_url',
                'document_path',
                'status',
                'rejection_reason',
                'applied_at',
                'reviewed_at',
                'reviewed_by',
            ]);
        });
    }
};
