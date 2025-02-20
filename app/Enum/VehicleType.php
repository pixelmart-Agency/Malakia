<?php

namespace App\Enum;

enum VehicleType: int
{
    case Car = 0;
    case Motorcycle = 1;
    case Truck = 2;
    case Bus = 3;



    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): string
    {
        return match ($this) {
            self::Car => 'سيارة',
            self::Motorcycle => 'دراجة نارية',
            self::Truck => 'شاحنة',
            self::Bus => 'حافلة',
        };
    }
}
