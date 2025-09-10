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
        Schema::table('leave_records', function (Blueprint $table) {
            $table->decimal('days_present', 5, 2)->default(30.00)->after('user_id');
            $table->decimal('days_leave_without_pay', 5, 2)->default(0.00)->after('days_present');
            $table->decimal('working_days', 5, 2)->default(30.00)->after('days_leave_without_pay');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_records', function (Blueprint $table) {
            $table->dropColumn(['days_present', 'days_leave_without_pay', 'working_days']);
        });
    }
};