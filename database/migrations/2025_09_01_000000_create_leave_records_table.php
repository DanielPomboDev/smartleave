<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_records', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            
            // Month/Year for this record
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            
            // Leave Summary
            $table->decimal('vacation_earned', 8, 3)->default(1.25);
            $table->decimal('vacation_used', 8, 3)->default(0);
            $table->decimal('vacation_balance', 8, 3)->default(0);
            
            $table->decimal('sick_earned', 8, 3)->default(1.25);
            $table->decimal('sick_used', 8, 3)->default(0);
            $table->decimal('sick_balance', 8, 3)->default(0);
            
            // Undertime entries (total hours for the month)
            $table->decimal('undertime_hours', 5, 2)->default(0);
            
            // Vacation leave entries (can store date ranges)
            $table->json('vacation_entries')->nullable();
            
            // Sick leave entries
            $table->json('sick_entries')->nullable();
            
            // Index for faster queries
            $table->index(['user_id', 'year', 'month']);
            
            $table->timestamps();
            
            // Unique constraint for user + month/year combination
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_records');
    }
};