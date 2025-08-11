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
        Schema::dropIfExists('leave_requests');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->enum('leave_type', ['vacation', 'sick']);
            $table->string('subtype');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('number_of_days');
            $table->string('where_spent');
            $table->boolean('commutation');
            $table->enum('status', ['pending', 'department_approved', 'approved', 'disapproved'])->default('pending');
            $table->text('department_comments')->nullable();
            $table->text('hr_comments')->nullable();
            $table->timestamps();
        });
    }
};
