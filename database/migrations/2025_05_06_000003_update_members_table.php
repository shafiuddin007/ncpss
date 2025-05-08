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
        Schema::table('members', function (Blueprint $table) {
            $table->string('religion')->change();
            $table->unsignedBigInteger('previous_member_number')->nullable()->after('is_deleted');
            $table->boolean('is_previous_member')->default(false)->after('previous_member_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->integer('religion')->change();
            $table->dropColumn('previous_member_number');
            $table->dropColumn('is_previous_member');
        });
    }
};