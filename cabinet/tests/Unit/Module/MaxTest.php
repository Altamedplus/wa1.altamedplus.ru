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
        $contet =  file_get_contents(DATA_MAX_DIR . 'test.button.payload.messag.json');
        $this->max->send($contet, $this->max->getApiUrl() . '/messages?user_id=36866622');
        Console::print($this->max->getResult(), Console::GREEN);

    }
}
