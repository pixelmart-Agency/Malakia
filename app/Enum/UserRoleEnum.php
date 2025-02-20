<?php

namespace App\Enum;

enum UserRoleEnum: int
{
    // 1=, 2=عامل, 3=المشرف, 4=السائقين, 1=مراقب
    case LEADER = 1;
    case WORKER = 2;
    case SUPERVISOR = 3;
    case DRIVER = 4;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): string
    {
        return match ($this) {
            self::LEADER => 'مشرف',
            self::WORKER => 'عامل',
            self::SUPERVISOR => 'مراقب',
            self::DRIVER => 'سائق',
        };
    }
}
