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
        Schema::table('users', function (Blueprint $table) {
            $table->string('ms_oid')->nullable()->unique()->after('id');
            $table->string('employee_id')->nullable()->unique()->after('ms_oid');
            $table->string('job_title')->nullable()->after('email');
            $table->foreignId('manager_user_id')->nullable()->after('job_title')->constrained('users')->onDelete('set null');
            $table->foreignId('current_company_id')->nullable()->after('manager_user_id')->constrained('companies')->onDelete('set null');
            $table->boolean('is_admin')->default(false)->after('current_company_id');
            $table->boolean('is_evaluator')->default(false)->after('is_admin');
            $table->string('profile_photo_path', 2048)->nullable()->after('is_evaluator');
            $table->boolean('is_active')->default(true)->after('profile_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_user_id']);
            $table->dropForeign(['current_company_id']);
            $table->dropColumn([
                'ms_oid',
                'employee_id',
                'job_title',
                'manager_user_id',
                'current_company_id',
                'is_admin',
                'is_evaluator',
                'profile_photo_path',
                'is_active',
            ]);
        });
    }
};
