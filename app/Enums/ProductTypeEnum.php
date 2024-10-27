<?php

namespace App\Enums;

enum ProductTypeEnum: int
{
    case salty = 1;
    case sweet = 2;
    case drink = 3;

    public static function getDescription(int $type): string
    {
        return match ($type) {
            self::salty->value => 'Salgados',
            self::sweet->value => 'Doces',
            self::drink->value => 'Bebidas',
            default            => '',
        };
    }
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), values: self::names());
    }
}
