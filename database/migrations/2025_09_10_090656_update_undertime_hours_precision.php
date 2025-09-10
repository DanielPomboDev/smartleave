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
            // Change undertime_hours to support 3 decimal places
            $table->decimal('undertime_hours', 8, 3)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_records', function (Blueprint $table) {
            // Revert to 2 decimal places
            $table->decimal('undertime_hours', 5, 2)->default(0)->change();
        });
    }
};
