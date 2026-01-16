<?php

namespace APP\Module;

use Pet\Errors\AppException;
/**
 * @see cabinet/tests/Unit/Module/SmsTest.php
 *  Модуль тестирования SmsTest
 *  запуск в консоли
 *  composer unit::filter SmsTest
 **/
class Sms
{
    protected $url = 'https://smsc.ru/sys/send.php';
    protected $login = 'altamedplus';
    protected $psw = 'MxiMgxThyG7C';

    public const FORMAT_STRING = 0;
    public const FORMAT_JSON = 1;
    public const FORMAT_XML = 2;

    public function __construct()
    {
        if (!defined('SMS_LOGIN') || !defined('SMS_PASSWORD')) {
            throw new AppException('Error! keys constat for smsc.ru');
        }

        $this->login = SMS_LOGIN;
        $this->psw = SMS_PASSWORD;
    }
    public function send($phone, $text)
    {
        return file_get_contents($this->url . "?login={$this->login}&psw={$this->psw}&phones=$phone&mes=$text");
    }

    public function getBalance($format = self::FORMAT_JSON)
    {
        return file_get_contents($this->url . "?login={$this->login}&psw={$this->psw}&fmt={$format}");
    }
}
