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
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('principal_installment', 12, 2)->nullable()->after('interest_rate');
            $table->decimal('principal_balance', 12, 2)->nullable()->after('principal_installment');
            $table->decimal('interest_due', 12, 2)->nullable()->after('principal_balance');
            $table->decimal('fine_due', 12, 2)->nullable()->after('interest_due');
            $table->boolean('is_defaultder')->default(false)->after('fine_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'principal_installment',
                'principal_balance',
                'interest_due',
                'fine_due',
                'is_defaultder',
            ]);
        });
    }
};
