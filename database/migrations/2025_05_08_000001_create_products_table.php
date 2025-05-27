<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ProductType; // Add this

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // BIGINT primary key
            $table->string('name', 100); // VARCHAR(100)
            // Use enum values from ProductType
            $table->enum('type', array_map(fn($case) => $case->value, ProductType::cases()));
            $table->text('description')->nullable(); // TEXT
            $table->decimal('interest_rate', 5, 2)->nullable(); // DECIMAL(5,2)
            $table->decimal('min_balance', 12, 2)->nullable(); // DECIMAL(12,2)
            $table->decimal('max_loan_amount', 12, 2)->nullable(); // DECIMAL(12,2)
            $table->integer('loan_term_months')->nullable(); // INT
            $table->string('currency', 3)->default('BDT'); // VARCHAR(3)
            $table->boolean('is_active')->default(true); // BOOLEAN
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();
    }
};
