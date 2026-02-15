<?php

namespace APP\Enum;

use APP\Module\UI\UI;

class TypeCannel
{
    const WA = 0;
    const MAX = 1;
    const TELEGRAM = 3;
    const SMS = 4;

    public static function data(): array
    {
        return [
            self::WA => 'WhatsApp',
            self::MAX => 'Max',
            self::TELEGRAM => 'Телеграмм',
            self::SMS => 'Смс'
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
