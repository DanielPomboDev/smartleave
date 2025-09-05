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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            
            // Email notifications
            $table->boolean('email_leave_requests')->default(true);
            $table->boolean('email_approvals')->default(true);
            $table->boolean('email_rejections')->default(true);
            
            // In-app notifications
            $table->boolean('in_app_leave_requests')->default(true);
            $table->boolean('in_app_approvals')->default(true);
            $table->boolean('in_app_rejections')->default(true);
            
            // Push notifications (for future mobile app)
            $table->boolean('push_leave_requests')->default(false);
            $table->boolean('push_approvals')->default(false);
            $table->boolean('push_rejections')->default(false);
            
            $table->timestamps();
            
            // Unique constraint for user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
