<?php declare(strict_types=1);

namespace App\Enums;

enum LocationStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
}
