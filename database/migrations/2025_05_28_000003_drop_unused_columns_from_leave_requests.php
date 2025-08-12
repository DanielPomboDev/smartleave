<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'department_comments')) {
                $table->dropColumn('department_comments');
            }
            if (Schema::hasColumn('leave_requests', 'hr_comments')) {
                $table->dropColumn('hr_comments');
            }
            if (Schema::hasColumn('leave_requests', 'hr_approved_by')) {
                $table->dropColumn('hr_approved_by');
            }
            if (Schema::hasColumn('leave_requests', 'hr_approved_at')) {
                $table->dropColumn('hr_approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_requests', 'department_comments')) {
                $table->text('department_comments')->nullable();
            }
            if (!Schema::hasColumn('leave_requests', 'hr_comments')) {
                $table->text('hr_comments')->nullable();
            }
            if (!Schema::hasColumn('leave_requests', 'hr_approved_by')) {
                $table->string('hr_approved_by')->nullable();
            }
            if (!Schema::hasColumn('leave_requests', 'hr_approved_at')) {
                $table->timestamp('hr_approved_at')->nullable();
            }
        });
    }
};
