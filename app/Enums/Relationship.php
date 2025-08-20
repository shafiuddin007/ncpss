<?php

namespace App\Enums;

enum Relationship: string
{
    case FATHER = 'Father';
    case MOTHER = 'Mother';
    case SPOUSE = 'Spouse';
    case SIBLING = 'Sibling';
    case CHILD = 'Child';
    case OTHER = 'Other';

    public function label(): string
    {
        return match ($this) {
            self::FATHER => 'Father',
            self::MOTHER => 'Mother',
            self::SPOUSE => 'Spouse',
            self::SIBLING => 'Sibling',
            self::CHILD => 'Child',
            self::OTHER => 'Other',
        };
    }
}
