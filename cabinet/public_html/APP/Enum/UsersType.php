<?php
namespace APP\Enum;
class UsersType
{
    const SYSADMIN = 1;
    const SENIOR_ADMIN = 2;
    const ADMIN = 3;
    const MARKETING = 4;
    const DOCTOR = 5;

    public static function data(): array
    {
        return [
            self::SYSADMIN => 'Системный Администратор',
            self::SENIOR_ADMIN => 'Старший Администратор',
            self::ADMIN => 'Администратор',
            self::MARKETING => 'Маркетинг',
            self::DOCTOR => 'Доктор'
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
