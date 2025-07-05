<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('share_account_number');
            $table->decimal('balance', 15, 2);
            $table->string('employer_name')->nullable();
            $table->string('employer_address')->nullable();
            $table->string('employer_email')->nullable();
            $table->string('employer_phone')->nullable();
            $table->unsignedBigInteger('nominee_id')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('nominee_id')->references('id')->on('nominees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_accounts');
    }
};
