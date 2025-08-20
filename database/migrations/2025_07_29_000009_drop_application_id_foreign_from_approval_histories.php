<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
        });
    }

    public function down(): void
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
    }
};
