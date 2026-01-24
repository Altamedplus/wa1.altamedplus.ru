<?php

namespace APP\Module;

use Pet\Errors\AppException;

class Telegramm
{
    private string $uri = '';
    private string $token = '';
    private $result = [
        'response' => [],
        'send' => []
    ];

    public function __construct()
    {
        if (!defined('TOKEN_TELEGRAMM')) {
            throw new AppException("Not found TOKEN_TELEGRAMM");
        }

        $this->token = TOKEN_TELEGRAM;
    }

    public function sendMessage($data): array
    {
        $this->result['send'][] = $data;
        $data = json_encode($data);

        // подготовка curl
        $curl = curl_init("https://api.telegram.org/bot{$this->token}/sendMessage");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;

        return ['response' => ''];
    }


}
