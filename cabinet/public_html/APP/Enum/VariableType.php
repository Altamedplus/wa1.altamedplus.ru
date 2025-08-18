<?php

namespace APP\Enum;

use APP\Module\UI\UI;

class VariableType
{
    const TEXT = 1;
    const PHONE = 2;
    const DATE = 3;
    const TIME = 4;
    const DATETIME = 5;
    const TEXTAREA = 6;

    public static function data(): array
    {
        return [
            self::TEXT => 'Текст',
            self::PHONE => 'Телефон',
            self::DATE => 'Даты',
            self::TIME => 'Время',
            self::DATETIME => 'Дата время',
            self::TEXTAREA => 'Большое текстовое поле',
        ];
    }

    public static function get(?int $type = null): ?string
    {
        if (!empty($type)) {
            return self::data()[$type] ?? null;
        }
        return null;
    }

    public static function getHtml(?int $type = null, $param = null): string
    {
        $value = '';
        $attr = [];
        foreach ($param as $name => $value) {
            $attr[$name] = $value ?? '';
        }
        switch ($type) {
            case self::TEXT:
                $value = UI::showStr([
                    'tag' => 'input',
                    'type' => 'text',
                ] + $attr);
                break;
            case self::PHONE:
                $value = UI::showStr([
                    'tag' => 'input',
                    'type' => 'phone',
                ] + $attr);
                break;
            case self::DATE:
                $value = UI::showStr([
                    'tag' => 'input',
                    'type' => 'date',
                ] + $attr);
                break;
            case self::TIME:
                $value = UI::showStr([
                    'tag' => 'input',
                    'type' => 'time',
                ] + $attr);
                break;
            case self::DATETIME:
                $value = UI::showStr([
                    'tag' => 'input',
                    'type' => 'datetime-local',
                ] + $attr);
                break;
            case self::TEXTAREA:
                $value = UI::showStr([
                    'tag' => 'textarea',
                ] + $attr);
                break;
        }
        return $value;
    }
}
