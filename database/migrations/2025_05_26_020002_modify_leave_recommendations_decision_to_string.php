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
        Schema::table('leave_recommendations', function (Blueprint $table) {
            if (Schema::hasColumn('leave_recommendations', 'decision')) {
                $table->dropColumn('decision');
            }
            if (!Schema::hasColumn('leave_recommendations', 'recommendation')) {
                $table->enum('recommendation', ['approve', 'disapprove']);
            }
            if (Schema::hasColumn('leave_recommendations', 'reason')) {
                $table->renameColumn('reason', 'remarks');
            }
            if (Schema::hasColumn('leave_recommendations', 'leave_request_id')) {
                $table->renameColumn('leave_request_id', 'leave_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_recommendations', function (Blueprint $table) {
            // First, drop the string column
            $table->dropColumn('decision');

            // Then add it back as an enum
            $table->enum('decision', ['approve', 'disapprove'])->after('leave_request_id');
        });
    }
};
