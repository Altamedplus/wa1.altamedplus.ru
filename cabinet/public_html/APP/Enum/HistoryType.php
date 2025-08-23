<?php

namespace APP\Enum;

use APP\Module\UI\UI;

class HistoryType
{
    public const ADD = 1;
    public const EDIT = 2;
    public const DELETE = 3;

    public static function data(): array
    {
        return [
            self::ADD => 'Добвавление',
            self::EDIT => 'Измтнение',
            self::DELETE => 'Удаление',
        ];
    }
}