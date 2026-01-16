<?php

namespace APP\Module\Max;
abstract class Entity
{
    protected $token = '';
    protected $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    protected $platformApiUrl = 'https://platform-api.max.ru';
    private $result = [
        'response' => [],
        'send' => []
    ];


    public function getApiUrl(): string
    {
        return $this->platformApiUrl;
    }

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

    public function send(string $data, $url)
    {
        $curl = $this->curlInit();
        $this->result['send'][] = $data;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        return [ 'response' => $result ];
    }

    public function sendMessangeUser($data, $userId)
    {
        $data['format'] = ($data['format'] ?? "html");
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this->send($json, $this->platformApiUrl . "/messages?user_id=$userId");
    }

    public function me()
    {

        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/me');
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        return [ 'response' => $result ];
    }

    public function subscriptions()
    {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/subscriptions');
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
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
        $this->result['response'][] = $result;
        return [ 'response' => $result ];
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
