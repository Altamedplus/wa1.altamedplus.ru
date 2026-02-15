<?php

namespace APP\Module;

use Pet\Errors\AppException;
/**
 * @see cabinet/tests/Unit/Module/SmsTest.php
 *  Модуль тестирования SmsTest
 *  запуск в консоли
 *  composer test:filter SmsTest
 **/
class Sms
{
    protected $url = [
        'send' =>  'https://smsc.ru/sys/send.php',
        'balace' => 'https://smsc.ru/sys/balance.php'
    ];

    protected $urlBalance = '';
    protected $login = 'altamedplus';
    protected $psw = 'MxiMgxThyG7C';

    public const FORMAT_STRING = 0;
    public const FORMAT_JSON = 3;
    public const FORMAT_XML = 2;

    public function __construct()
    {
        if (!defined('SMS_LOGIN') || !defined('SMS_PASSWORD')) {
            throw new AppException('Error! keys constat in env (SMS_LOGIN, SMS_PASSWORD )for smsc.ru');
        }

        $this->login = SMS_LOGIN;
        $this->psw = SMS_PASSWORD;
    }
    public function send($phone, $text)
    {
        $str = $this->url['send'] . "?login={$this->login}&psw={$this->psw}&phones=$phone&mes=$text&fmt=3";
        return file_get_contents($str);
    }

    public function getBalance($format = self::FORMAT_JSON)
    {
        return file_get_contents($this->url['balace'] . "?login={$this->login}&psw={$this->psw}&fmt={$format}");
    }
}
