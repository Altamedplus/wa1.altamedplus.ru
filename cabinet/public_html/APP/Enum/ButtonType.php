<?php
namespace APP\Enum;

use APP\Module\UI\UI;

class ButtonType
{
    const URL = "URL";
    const QUICK_REPLY = "QUICK_REPLY";
    const PHONE = "PHONE";
    const OPT = "OPT";

    public static function data(): array
    {
        return [
            self::URL => 'URL',
            self::PHONE => 'Телефон',
            self::QUICK_REPLY => 'Быстрый ответ',
            self::OPT => 'Копирует'
        ];
    }

    public static function get(?int $type = null): ?string
    {
        if (!empty($type)) {
            return self::data()[$type] ?? null;
        }
        return null;
    }

}
