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
            // The enum column already exists, we just need to ensure it includes 'cancelled'
            // This will be handled automatically by the model, but we'll document it here
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't need to remove 'cancelled' from the enum as it doesn't break anything
        // and Laravel handles enum updates automatically based on the model
    }
};
