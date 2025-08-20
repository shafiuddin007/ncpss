<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('savings_acc_id');
            $table->unsignedBigInteger('member_id');
            $table->year('year');
            $table->tinyInteger('month');
            $table->date('due_date')->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->decimal('due', 16, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->foreign('savings_acc_id')->references('id')->on('savings_accounts')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_payments');
    }
};
