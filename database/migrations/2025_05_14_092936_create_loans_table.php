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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            // $table->string('code_number')->nullable();
            // $table->string('loan_number')->unique();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('min_balance', 15, 2)->nullable();
            $table->decimal('max_loan_amount', 15, 2)->nullable();
            $table->integer('loan_term_months')->nullable();

            $table->string('office_address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('designation')->nullable();
            $table->string('office_contact')->nullable();

            $table->decimal('self_income')->nullable();
            $table->decimal('family_income')->nullable();
            $table->decimal('total_income')->nullable();

            $table->decimal('rent')->nullable();
            $table->decimal('food_expense')->nullable();
            $table->decimal('education_expense')->nullable();
            $table->decimal('transport_expense')->nullable();
            $table->decimal('other_expense')->nullable();
            $table->decimal('total_expense')->nullable();

            $table->decimal('current_share_amount')->nullable();
            $table->decimal('before_share_amount')->nullable();

            $table->decimal('loan_amount')->nullable();
            $table->string('loan_type')->nullable();
            $table->string('loan_purpose')->nullable();

            $table->integer('previous_loans')->nullable();
            $table->boolean('is_reg_paid')->default(true);

            $table->integer('total_installment')->nullable();
            $table->string('first_installment')->nullable();

            $table->decimal('other_loan_amount')->nullable();
            $table->integer('other_loan_installment')->nullable();
            $table->decimal('other_loan_remaining')->nullable();

            $table->integer('loan_surety_id')->nullable();
            $table->string('surety_name')->nullable();
            $table->string('self_surety_amount')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('active'); // e.g., active, closed, defaulted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
