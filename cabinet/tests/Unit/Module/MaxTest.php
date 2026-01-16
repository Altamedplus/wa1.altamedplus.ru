<?php

namespace Tests\Unit\Module;

use APP\Module\Max\Messenger;
use Exception;
use Pet\Command\Console\Console;
use PHPUnit\Framework\TestCase;


class MaxTest extends TestCase {
    private Messenger $max;
    private $userId = '36866622';

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->max = new Messenger();
        } catch (Exception $e) {
            Console::log($e->getMessage(), Console::RED);
        }
    }


    public function testSendButton()
    {
        // $contet =  file_get_contents(DATA_MAX_DIR . 'test.button.payload.messag.json');
        // // $contet = json_decode($contet, )
        // $this->max->send($contet, $this->max->getApiUrl() . '/messages?user_id=36866622');
        // Console::print($this->max->getResult(), Console::GREEN);
    }

    public function testLoadFile(){
        // $testUrl = 'https://app.edna.ru/edna.b1/886/2023-11-03/197ce21c25de47b58721910c316a1096.jpg';
        // $token = $this->max->load($testUrl, 'image');
        // Console::print($this->max->getResult(), Console::GREEN);

        // Console::log($token);
    }
    public function testGetUrlLoad()
    {
        // $data = $this->max->getUrlLoad();
        // Console::print($data, Console::GREEN);
    }

    public function testLongPuling()
    {
        $pulling = $this->max->longPollingUpdate(['message_created', 'message_edit', 'message_callback']);
        Console::print($pulling, Console::GREEN);
    }
}
