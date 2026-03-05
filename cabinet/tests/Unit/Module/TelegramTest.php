<?php

namespace Tests\Unit\Module;

use APP\Module\Max\Messenger;
use APP\Module\Telegram;
use Exception;
use Pet\Command\Console\Console;
use PHPUnit\Framework\TestCase;


class TelegramTest extends TestCase {
    private Telegram $tg;
    private $userId = '713577548';

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->tg = new Telegram();
        } catch (Exception $e) {
            Console::log($e->getMessage(), Console::RED);
        }
    }


    // public function testSendPhoto()
    // {

    //     $result =  $this->tg->send($this->userId, [
    //         'photo' => 'https://play-lh.googleusercontent.com/1-hPxafOxdYpYZEOKzNIkSP43HXCNftVJVttoo4ucl7rsMASXW3Xr6GlXURCubE1tA=w3840-h2160-rw',
    //         'caption' => "<b>Заголовок</b>\n\nОсновной текст сообщения...\n\nВыберите действие:",
    //         'text' => "<b>Заголовок</b>\n\nОсновной текст сообщения...\n\nВыберите действие:"
    //     ]);
    //     Console::print($result, Console::GREEN);
    // }

    public function testTgPhone()
    {

        $result =  $this->tg->send($this->userId, [
            'text' => "Медицинский центр «Альтамед+ на Союзной» приветствует вас!\r\n\r\nСообщаем вам, что в связи с изменением расписания врача, мы вынуждены <b>отменить запись на приём</b> к доктору  01.01.1970 в .\r\n\r\nПожалуйста, свяжитесь с нами по телефону: +7(495)212-90-03 или напишите нам в этом чате. \r\n\r\nМы сделаем все возможное, чтобы подобрать для Вас максимально удобное время и дату приема.\n\r\n\r <i>Доктора, увы, тоже болеют :(</i>",
            "photo" => "https:\/\/wa1.altamedplus.ru\/view\/uploads\/sample\/68ab252e4ecd7.jpg",
            "parse_mode" => "HTML",
            "reply_markup" => [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "Позвонить нам",
                            "url" => "tel:+74952129003"
                        ]
                    ]
                ]
            ]
        ]);
        Console::print($result, Console::GREEN);
    }

}
