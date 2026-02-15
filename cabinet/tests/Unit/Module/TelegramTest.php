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


    public function testSendPhoto()
    {

        $result =  $this->tg->send($this->userId, [
            'photo' => 'https://play-lh.googleusercontent.com/1-hPxafOxdYpYZEOKzNIkSP43HXCNftVJVttoo4ucl7rsMASXW3Xr6GlXURCubE1tA=w3840-h2160-rw',
            'caption' => "<b>Заголовок</b>\n\nОсновной текст сообщения...\n\nВыберите действие:",
            'text' => "<b>Заголовок</b>\n\nОсновной текст сообщения...\n\nВыберите действие:"
        ]);
        Console::print($result, Console::GREEN);
    }


}
