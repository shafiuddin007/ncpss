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
        Schema::create('monthly_closures', function (Blueprint $table) {
            $table->id();
            $table->string('account_type');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('member_id');
            $table->year('year');
            $table->tinyInteger('month');
            $table->decimal('deposite', 12, 2)->default(0);
            $table->decimal('withdraw', 12, 2)->default(0);
            $table->decimal('due', 12, 2)->default(0);
            $table->decimal('fine', 12, 2)->default(0);
            $table->decimal('interest', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('processed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_closures');
    }
};
