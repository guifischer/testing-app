<?php

namespace App\Enums;

use App\Enums\Traits\EnumOptions;

enum TaskPriorityEnum: string
{
    use EnumOptions;

    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';

    public function getLabel(): string
    {
        return match ($this) {
            self::HIGH => 'High',
            self::MEDIUM => 'Medium',
            self::LOW => 'Low'
        };
    }
}
