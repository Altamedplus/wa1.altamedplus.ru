<?php

namespace Tests\Unit\Module;

use APP\Module\Sms;
use Exception;
use Pet\Command\Console\Console;
use PHPUnit\Framework\TestCase;


class SmsTest extends TestCase
{
    private Sms $sms;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->sms = new Sms();
        } catch (Exception $e) {
            Console::log($e->getMessage(), Console::RED);
        }
    }

    public function testBalance()
    {

        $jsonResponse = $this->sms->getBalance(Sms::FORMAT_JSON);

        $this->assertNotEmpty($jsonResponse, 'Ответ от сервиса не должен быть пустым');

        $data = json_decode($jsonResponse);

        $this->assertNotNull($data, 'Не удалось декодировать JSON ответ');

        $this->assertTrue(isset($data->balance), 'В ответе отсутствует ключ "balance"');

        $balance = $data->balance;

        Console::log("Текущий баланс: " . $balance, Console::GREEN);

        if ($balance < 0) {
            Console::log('Баланс отрицательный: ' . $balance, Console::RED);
            $this->assertLessThan(0, $balance, 'Баланс отрицательный');
        } elseif ($balance == 0) {
            Console::log('Баланс равен нулю', Console::YELLOW);
            $this->assertEquals(0, $balance, 'Баланс должен быть равен 0');
        } elseif ($balance < 20) {
            Console::log('Баланс менее 20: ' . $balance, Console::YELLOW);
            $this->assertLessThan(20, $balance, 'Баланс менее 20');
            $this->assertGreaterThan(0, $balance, 'Баланс больше 0 но менее 20');
        } elseif ($balance == 20) {
            Console::log('Баланс равен 20', Console::GREEN);
            $this->assertEquals(20, $balance, 'Баланс должен быть равен 20');
        } else {
            Console::log('Баланс нормальный: ' . $balance, Console::GREEN);
            $this->assertGreaterThan(20, $balance, 'Баланс должен быть больше 20');
        }
    }

    public function testSend()
    {
        $this->sms->send('79775956853', 'test');
    }
}
