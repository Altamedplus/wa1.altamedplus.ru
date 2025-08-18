<?php
namespace APP\Enum;

use APP\Model\ClinicModel;

class VariableReserveType
{
    const CLINIC = 'clinic';
    const ADDRESS_CLINIC = 'address';

    public static function data()
    {
        return [
            self::CLINIC => 'Клиника',
            self::ADDRESS_CLINIC => 'Адрес Клиники'
        ];
    }

    public static function get(string $name):string
    {
        return self::data()[$name] ?? '';
    }

    public static function keys(): array
    {
        return array_keys(self::data());
    }
}
