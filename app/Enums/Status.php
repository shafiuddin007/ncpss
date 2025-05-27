<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case WAITING_FOR_APPROVAL = 'waiting_for_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case RESET = 'reset';
    case ON_HOLD = 'on_hold';
    case ARCHIVED = 'archived';
    case DELETED = 'deleted';
    case DRAFT = 'draft';

    case REVIEW = 'review';

    case EXPIRED = 'expired';
    case RENEWED = 'renewed';

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case CLOSED = 'closed';

    case CANCELLED = 'cancelled';
    case CANCELLED_BY_USER = 'cancelled_by_user';
    case CANCELLED_BY_ADMIN = 'cancelled_by_admin';
  
    case SUSPENDED = 'suspended';
    case PENDING_PAYMENT = 'pending_payment';
    case PROCESSING_PAYMENT = 'processing_payment';
    case COMPLETED_PAYMENT = 'completed_payment';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::WAITING_FOR_APPROVAL => 'Waiting for Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::RESET => 'Reset',
            self::ON_HOLD => 'On Hold',
            self::ARCHIVED => 'Archived',
            self::DELETED => 'Deleted',
            self::DRAFT => 'Draft',
            self::REVIEW => 'Review',
            self::EXPIRED => 'Expired',
            self::RENEWED => 'Renewed',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::CLOSED => 'Closed',
            self::CANCELLED => 'Cancelled',
            self::CANCELLED_BY_USER => 'Cancelled by User',
            self::CANCELLED_BY_ADMIN => 'Cancelled by Admin',
            self::SUSPENDED => 'Suspended',
            self::PENDING_PAYMENT => 'Pending Payment',
            self::PROCESSING_PAYMENT => 'Processing Payment',
            self::COMPLETED_PAYMENT => 'Completed Payment',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
        };
    }
}
