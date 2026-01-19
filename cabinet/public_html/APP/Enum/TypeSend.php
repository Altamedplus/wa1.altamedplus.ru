<?php

namespace APP\Enum;

class TypeSend
{
    const WA = 0;
    const MAX = 1;
    const TELEGRAM = 3;

    public static function data(): array
    {
        return [
            self::WA => 'WhatsApp',
            self::MAX => 'Max',
            self::TELEGRAM => 'Telegramm',
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
