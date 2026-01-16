<?php

namespace APP\Module\Max;

use CURLFile;

abstract class Entity {
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

    public function __construct($token) {
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
        return ['response' => $result];
    }

    public function sendMessangeUser(array|string $data, $userId)
    {
        $json = is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
        return $this->send($json, $this->platformApiUrl . "/messages?user_id=$userId");
    }

    public function me() {

        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/me');
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        return ['response' => $result];
    }

    public function subscriptions() {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/subscriptions');
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        return ['response' => $result];
    }

    public function subscriptionsAdd(array $data) {
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . '/subscriptions');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_POST, true);
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        return ['response' => $result];
    }

    public function getResult(): array
    {
        return $this->result;
    }
    public function getUrlLoad($type)
    {
        return $this->send('', $this->platformApiUrl . "/uploads?type=$type");
    }

    public function load($urlFile, $type): false | string
    {
        $url = $this->getUrlLoad($type)['response']['url'] ?? false;
        if (empty($url)) {
            return false;
        }
        $fileContent = file_get_contents($urlFile);
        if ($fileContent === false) {
            return false;
        }
        $pathTemp = PUBLIC_DIR . DS . 'view' . DS . 'uploads' . DS . 'tmp';
        if (!is_dir($pathTemp)) {
            mkdir($pathTemp, 0777, true);
        }
        $name = md5($urlFile);
        $exp = explode('.', $urlFile);
        $exp = $exp[count($exp)-1];
        $tempFilePath = $pathTemp . DS . $name . "." . $exp;
        file_put_contents($tempFilePath, $fileContent);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data',
            $this->headers[2]
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        $cfile = new CURLFile(
            $tempFilePath,
            mime_content_type($tempFilePath), // Определяем MIME‑тип
            $name
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, ['data' => $cfile]);

        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        unlink($tempFilePath);
        $result = $result['photos'];
        foreach ($result as $k => $token) {
            if (is_array($token) && isset($token['token'])) {
                return $token['token'];
            }
        }
        return false;
    }


    public function longPollingUpdate(array $type = [], $marker = '')
    {
        $get = [];
        $getStr = '';
        if (!empty($type)) {
            $get[] = "type=" . implode(',', $type);
        }
        if (!empty($marker)) {
            $get[] = "marker=$marker";
        }
        if (!empty($get)) {
            $getStr = '?' . implode('&', $get);
        }
        $curl = $this->curlInit();
        curl_setopt($curl, CURLOPT_URL, $this->platformApiUrl . "/updates$getStr");
        $result = curl_exec($curl);
        $result = json_decode($result, true) ?  json_decode($result, true) : $result;
        $this->result['response'][] = $result;
        return ['response' => $result];
    }
}
