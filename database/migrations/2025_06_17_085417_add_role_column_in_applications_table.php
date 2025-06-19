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
        Schema::table('applications', function (Blueprint $table) {
            // Add role column to applications table
            $table->string('role')->nullable()->after('status');
            

            // Add index for faster querying by role
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the role column
            $table->dropColumn('role');

            // Drop the index for role
            $table->dropIndex(['role']);
        });
    }
};
