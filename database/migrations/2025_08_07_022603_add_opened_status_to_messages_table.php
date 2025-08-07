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
        // First, we need to modify the enum to include 'opened' status
        // In MySQL, we need to use raw SQL to modify ENUM
        DB::statement("ALTER TABLE messages MODIFY COLUMN status ENUM('draft', 'archived', 'deleted', 'inbox', 'opened', 'sent', 'read') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the previous enum values
        DB::statement("ALTER TABLE messages MODIFY COLUMN status ENUM('draft', 'archived', 'deleted', 'inbox') DEFAULT 'draft'");
    }
};