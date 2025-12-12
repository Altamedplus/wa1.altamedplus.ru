<?php

namespace APP\Module\Max;
abstract class Entity
{
    protected $token = '';
    protected $headers = ['Content-Type: application/json'];
    protected $platformApiUrl = 'https://platform-api.max.ru';

    public function __construct($token)
    {
        $this->token = $token;
        $this->headers[] = "Authorization: $token";
    }

    private function curlInit()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        return $curl;
    }

    public function sendMessange($data)
    {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        // curl_setopt($curl, CURLOPT_URL, $this->patformApiUrl .'/');
        curl_setopt($curl, CURLOPT_POST, true);
    }

    public function me()
    {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/me');
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        return [ 'response' => $result ];
    }

    public function subscriptions()
    {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/subscriptions');
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        return [ 'response' => $result ];
    }

    public function subscriptionsAdd(array $data)
    {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/subscriptions');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_POST, true);
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        return [ 'response' => $result ];
    }
}
