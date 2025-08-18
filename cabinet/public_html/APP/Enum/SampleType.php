<?php
namespace APP\Enum;
class SampleType
{
    const TEXT = "TEXT";
    const IMAGE = "IMAGE";
    const DOCUMENT = "DOCUMENT";
    const VIDEO = "VIDEO";
    const AUDIO = "AUDIO";
    const LOCATION = "LOCATION";
    const LIST_PICKER = "LIST_PICKER";
    const AUTHENTICATION = "AUTHENTICATION";
    const FLOW = "FLOW";

    public static function data(): array
    {
        return [
            self::TEXT => 'Текст',
            self::IMAGE => 'Фото',
            self::VIDEO => 'Видео',
            self::AUDIO => 'Аудио',
            self::LOCATION => 'Cообщение с координатами',
            self::LIST_PICKER => "Кнопки интерактивного меню WhatsApp",
            self::AUTHENTICATION => "Cообщение с одноразовым паролем и кнопкой копирования",
            self::FLOW => "Cообщение, содержащее WhatsApp Flows"
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
