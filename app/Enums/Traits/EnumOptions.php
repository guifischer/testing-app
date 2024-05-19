<?php

namespace App\Enums\Traits;

trait EnumOptions
{
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $enum) {
            $options[$enum->name] = [
                'value' => $enum->value,
                'label' => $enum->getLabel(),
            ];
        }

        return $options;
    }

    abstract public function getLabel(): string;
}
