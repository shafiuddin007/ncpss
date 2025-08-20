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
        Schema::create('share_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('share_acc_id');
            $table->unsignedBigInteger('member_id');
            $table->year('year');
            $table->tinyInteger('month');
            $table->date('due_date');
            $table->decimal('amount', 12, 2);
            $table->decimal('due', 12, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->foreign('share_acc_id')->references('id')->on('share_accounts')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_payments');
    }
};
