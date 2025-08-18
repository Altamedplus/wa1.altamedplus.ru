<?php
namespace APP\Enum;
class HeaderType
{
    const TEXT = "TEXT";
    const IMAGE = "IMAGE";
    const DOCUMENT = "DOCUMENT";
    const VIDEO = "VIDEO";
    public static function data(): array
    {
        return [
            self::TEXT => 'Текст',
            self::DOCUMENT => 'Документ',
            self::IMAGE => 'Фото',
            self::VIDEO => 'Видео'
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