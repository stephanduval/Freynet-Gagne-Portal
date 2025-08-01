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
        Schema::table('projects', function (Blueprint $table) {
            // Add company_id column as nullable foreign key initially
            $table->foreignId('company_id')->nullable()->constrained('companies')->after('client_id');
        });

        // Populate existing projects with their client's primary company
        // This is safe to run as it only affects existing records
        DB::statement('
            UPDATE projects 
            SET company_id = (
                SELECT user_company.company_id 
                FROM user_company 
                WHERE user_company.user_id = projects.client_id 
                LIMIT 1
            ) 
            WHERE company_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
