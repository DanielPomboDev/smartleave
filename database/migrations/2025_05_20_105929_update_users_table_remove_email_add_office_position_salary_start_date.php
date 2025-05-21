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
            // Remove email related columns
            $table->dropUnique('users_email_unique');
            $table->dropColumn(['email', 'email_verified_at']);
            
            // Add new columns
            $table->string('office')->after('last_name');
            $table->string('position')->after('office');
            $table->decimal('salary', 10, 2)->default(0)->after('position');
            $table->date('start_date')->after('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the email columns
            $table->string('email')->unique()->after('last_name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            
            // Remove the new columns
            $table->dropColumn(['office', 'position', 'salary', 'start_date']);
        });
    }
};
