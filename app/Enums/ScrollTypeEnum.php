<?php

namespace App\Enums;

enum ScrollTypeEnum: int {
    case GENERAL_SCROLL = 1;
    case BREAKING = 2;

    public function label(): string
    {
        return match($this) {
            self::GENERAL_SCROLL => 'General Scroll',
            self::BREAKING => 'Breaking',
        };
    }

    public static function options(): array
    {
        return [
            ['id' => self::GENERAL_SCROLL->value, 'type' => self::GENERAL_SCROLL->label()],
            ['id' => self::BREAKING->value, 'type' => self::BREAKING->label()],
        ];
    }
}
