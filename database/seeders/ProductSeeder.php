<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Personal Loan',
                'type' => 'loan',
                'description' => 'A loan for personal use with flexible terms.',
                'interest_rate' => 12.50,
                'min_balance' => null,
                'max_loan_amount' => 500000.00,
                'loan_term_months' => 60,
                'currency' => 'USD',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Savings Account',
                'type' => 'savings',
                'description' => 'A savings account with competitive interest rates.',
                'interest_rate' => 3.75,
                'min_balance' => 1000.00,
                'max_loan_amount' => null,
                'loan_term_months' => null,
                'currency' => 'USD',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Credit Card',
                'type' => 'credit_card',
                'description' => 'A credit card with a low annual fee.',
                'interest_rate' => 18.00,
                'min_balance' => null,
                'max_loan_amount' => 10000.00,
                'loan_term_months' => null,
                'currency' => 'USD',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fixed Deposit',
                'type' => 'investment',
                'description' => 'A fixed deposit account with high returns.',
                'interest_rate' => 6.50,
                'min_balance' => 5000.00,
                'max_loan_amount' => null,
                'loan_term_months' => 12,
                'currency' => 'USD',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}