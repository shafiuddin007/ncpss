<?php

namespace App\Enums;

enum ProductType: string
{
    case LOAN = 'loan';
    case SAVINGS = 'savings';
    case CURRENT = 'current';
    case FIXED_DEPOSIT = 'fixed_deposit';
    case SHARE = 'share';
    case Credit_Card = 'credit_card';
    case INVESTMENT = 'investment';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::LOAN => 'Loan',
            self::SAVINGS => 'Savings',
            self::CURRENT => 'Current',
            self::FIXED_DEPOSIT => 'Fixed Deposit',
            self::SHARE => 'Share',
            self::Credit_Card => 'Credit Card',
            self::INVESTMENT => 'Investment',
            self::OTHER => 'Other',
        };
    }
}
