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
        Schema::create('leave_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')->constrained()->onDelete('cascade');
            $table->string('decision');
            $table->text('reason')->nullable();
            $table->string('department_admin_id');
            $table->foreign('department_admin_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_recommendations');
    }
};
