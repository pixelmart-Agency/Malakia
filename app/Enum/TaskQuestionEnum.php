<?php

namespace App\Enum;

enum TaskQuestionEnum: int
{
    case TEXT = 0;
    case MULTIPLE = 1;
    case  TRUE_FALSE = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


    public function lang(): string
    {
        return match ($this) {
            self::TEXT => 'مقالي',
            self::TRUE_FALSE => 'صح أم خطأ',
            self::MULTIPLE => 'اختيار من متعدد',
        };
    }
}
