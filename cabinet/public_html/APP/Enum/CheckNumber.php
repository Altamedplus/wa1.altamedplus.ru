<?php
namespace APP\Enum;

class CheckNumber
{
    const NO_REQUEST = 1;
    const ASK = 2;
    const CHECK = 3;

    public static function data(): array
    {
        return [
            self::NO_REQUEST => 'Не отправлять повторно',
            self::ASK => 'Спрашивать',
            self::CHECK => 'Не проверять номер'
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