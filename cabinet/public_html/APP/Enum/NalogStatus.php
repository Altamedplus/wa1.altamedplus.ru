<?php

namespace APP\Enum;

class NalogStatus
{
    const NEW = 1;
    const WORKING = 2;
    const READY = 3;
    const ISSUED = 4;

    public static function data(): array
    {
        return [
            self::NEW => 'Новая',
            self::WORKING => 'В работе',
            self::READY => 'Готово',
            self::ISSUED => 'Выдано'
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
