<?php

namespace APP\Module;
class Sms{
    protected $url = 'https://smsc.ru/sys/send.php';
    protected $login = 'altamedplus';
    protected $psw = 'MxiMgxThyG7C';
    public function send($phone, $text)
    {
        return file_get_contents($this->url . "?login={$this->login}&psw={$this->psw}&phones=$phone&mes=$text");
    }
}
