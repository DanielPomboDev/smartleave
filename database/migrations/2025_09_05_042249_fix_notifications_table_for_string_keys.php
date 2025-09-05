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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the existing notifiable columns
            $table->dropMorphs('notifiable');
            
            // Add new notifiable columns with string type
            $table->string('notifiable_type');
            $table->string('notifiable_id');
            
            // Recreate the index
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the string columns
            $table->dropIndex(['notifiable_type', 'notifiable_id']);
            $table->dropColumn(['notifiable_type', 'notifiable_id']);
            
            // Recreate the morphs (will create integer notifiable_id)
            $table->morphs('notifiable');
        });
    }
};
