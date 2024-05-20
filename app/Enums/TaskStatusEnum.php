<?php

namespace App\Enums;

use App\Enums\Traits\EnumOptions;

enum TaskStatusEnum: string
{
    use EnumOptions;
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed'
        };
    }
}
