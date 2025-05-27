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
}
