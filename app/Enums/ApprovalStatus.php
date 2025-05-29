<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case FORWARDED = 'forwarded';
    case RESET = 'reset';
    case ON_HOLD = 'on_hold';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::FORWARDED => 'Forwarded',
            self::RESET => 'Reset',
            self::ON_HOLD => 'On Hold',
            self::DRAFT => 'Draft',
        };
    }
}
