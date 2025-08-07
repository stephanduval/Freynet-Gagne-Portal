<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Since task_status is a string field (not enum), we don't need to modify the column type
        // We just need to update any validation rules in the application
        // However, if you want to enforce this at the database level with a CHECK constraint:
        
        // For MySQL, we can add a comment to document the allowed values
        Schema::table('messages', function (Blueprint $table) {
            $table->string('task_status')->comment('Allowed values: new, opened, in_process, completed')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the comment
        Schema::table('messages', function (Blueprint $table) {
            $table->string('task_status')->comment('Allowed values: new, in_process, completed')->change();
        });
    }
};