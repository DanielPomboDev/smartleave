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
            // First, drop the existing decision column
            $table->dropColumn('decision');

            // Then add it back as a string
            $table->string('decision')->after('leave_request_id');
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
