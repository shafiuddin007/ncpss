<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('savings_account_number')->unique();
            $table->decimal('initial_deposit', 16, 2)->default(0);
            $table->unsignedBigInteger('nominee_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('nominee_id')->references('id')->on('nominees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_accounts');
    }
};
