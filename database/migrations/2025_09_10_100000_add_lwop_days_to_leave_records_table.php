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
            $table->decimal('lwop_days', 8, 3)->default(0)->after('undertime_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_records', function (Blueprint $table) {
            $table->dropColumn('lwop_days');
        });
    }
};