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
        Schema::table('leave_requests', function (Blueprint $table) {
            // First, drop the existing status column
            $table->dropColumn('status');

            // Then add it back with the new status option
            $table->enum('status', ['pending', 'recommended', 'department_approved', 'approved', 'disapproved'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // First, drop the modified status column
            $table->dropColumn('status');

            // Then add it back with the original options
            $table->enum('status', ['pending', 'department_approved', 'approved', 'disapproved'])->default('pending');
        });
    }
};
