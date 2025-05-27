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
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
            $table->integer('installment_no'); // e.g. 1, 2, 3...
            $table->date('due_date');
            $table->decimal('principal_due', 10, 2);
            $table->decimal('interest_due', 10, 2);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->date('paid_at')->nullable();
            $table->boolean('is_minimum_allowed')->default(false); 
            $table->decimal('minimum_allowed_amount', 10, 2)->nullable(); 
            $table->timestamps();

            // Indexes
            $table->index(['loan_id', 'installment_no']);
            $table->index(['due_date']); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installments');
    }
};
