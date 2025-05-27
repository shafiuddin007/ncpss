<?php

use App\Enums\Status;
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
            $table->string('code_number')->nullable();
            $table->string('loan_number')->nullable();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');

            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('min_balance', 15, 2)->nullable()->default(0);
            $table->decimal('max_loan_amount', 15, 2)->nullable()->default(0);
            $table->integer('loan_term_months')->default(0);

            // income and expense
            $table->string('office_address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('designation')->nullable();
            $table->string('office_contact')->nullable();

            $table->decimal('self_income')->default(0);
            $table->decimal('family_income')->default(0);
            $table->decimal('total_income')->default(0);

            $table->decimal('rent')->default(0);
            $table->decimal('food_expense')->default(0);
            $table->decimal('education_expense')->default(0);
            $table->decimal('transport_expense')->default(0);
            $table->decimal('other_expense')->default(0);
            $table->decimal('total_expense')->default(0);

            // loan details
            $table->decimal('loan_amount')->default(0);
            $table->string('loan_purpose')->nullable();
            $table->string('loan_type')->nullable();
            $table->decimal('uregent_fee')->default(0);
            $table->integer('total_installment')->default(0);
            $table->date('installment_start_date')->nullable();
            $table->string('first_installment')->nullable();

            $table->decimal('other_loan_amount')->default(0);
            $table->integer('other_loan_installment')->default(0);
            $table->decimal('other_loan_remaining')->default(0);

            $table->string('loan_collateral_type')->nullable();
            $table->string('self_deposite_amount')->nullable();

            // family information
            $table->integer('family_member')->default(0);

            // Use enum for status
            $table->enum('status', array_column(Status::cases(), 'value'))->default(Status::PENDING->value);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index(['member_id', 'product_id']);
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
