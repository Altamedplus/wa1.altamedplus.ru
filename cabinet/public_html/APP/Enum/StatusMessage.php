<?php

namespace APP\Enum;

class StatusMessage
{
    const QUEUE  = 1;
    const SEND_WA = 2;
    const SENT_WA = 3;
    const DELIVERED_WA = 4;
    const READ_WA = 5;
    const UNDELIVERED_WA = 6;

    public static function data(): array
    {
        return [
            self::QUEUE => 'В очереди',
            self::SEND_WA => 'Отправлено Edna',
            self::SENT_WA => 'Отправлено WhatsApp',
            self::DELIVERED_WA => 'Доставлено WhatsApp',
            self::READ_WA => 'Прочитано WhatsApp',
            self::UNDELIVERED_WA => 'Недоставлено WhatsApp'
        ];
    }

    public static function get(int $name): string
    {
        return self::data()[$name] ?? '';
    }
    public static function getEdna(string $status): int
    {
        return match ($status) {
            'READ' => self::READ_WA,
            'DELIVERED' => self::DELIVERED_WA,
            'SENT' => self::SENT_WA,
            'UNDELIVERED' => self::UNDELIVERED_WA
        };
    }
}
