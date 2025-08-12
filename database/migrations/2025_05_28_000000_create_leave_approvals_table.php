<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->bigIncrements('approval_id');
            $table->string('hr_manager_id')->nullable();
            $table->unsignedBigInteger('leave_id');
            $table->enum('approval', ['approve', 'disapprove']);
            $table->string('approved_for')->nullable();
            $table->text('dissapproved_due_to')->nullable();
            $table->timestamps();

            $table->foreign('hr_manager_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('leave_id')
                ->references('id')
                ->on('leave_requests')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
};
