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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_id'); //loan, deposit, or other payment model
            $table->string('model_type');

            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('member_id')->nullable()->unsigned();
            $table->string('member_name')->nullable();

            // Payment info
            $table->string('payment_method')->nullable();
            $table->string('receipt_number')->nullable()->unique();

            // Payment components
            $table->decimal('principal', 12, 2)->default(0);
            $table->decimal('interest', 12, 2)->default(0);
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('surety_release', 12, 2)->default(0);
            $table->decimal('fine', 12, 2)->default(0);
            $table->decimal('lps', 12, 2)->default(0);
            $table->decimal('advartise', 12, 2)->default(0); // Note: Check spelling
            $table->decimal('bank_int', 12, 2)->default(0);

            // Calculated total
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->date('payment_date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['model_id', 'model_type']);
            $table->index('product_id');
            $table->index('member_id');

            // Foreign keys (optional, based on your schema)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
