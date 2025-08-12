<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_requests', 'hr_approved_by')) {
                $table->string('hr_approved_by')->nullable()->after('hr_comments');
            }
            if (!Schema::hasColumn('leave_requests', 'hr_approved_at')) {
                $table->timestamp('hr_approved_at')->nullable()->after('hr_approved_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'hr_approved_at')) {
                $table->dropColumn('hr_approved_at');
            }
            if (Schema::hasColumn('leave_requests', 'hr_approved_by')) {
                $table->dropColumn('hr_approved_by');
            }
        });
    }
};
