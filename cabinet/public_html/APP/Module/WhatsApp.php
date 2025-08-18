<?php
namespace APP\Module;

use APP\Model\EdnaModel;

class WhatsApp
{
    const URL_EDNA_CASCADE_SCHEDULE = URL_EDNA_CASCADE_SCHEDULE;
    const URL_EDNA_CHANNEL_PROFILE  = URL_EDNA_CHANNEL_PROFILE;

    public array $headers = ['Content-Type: application/json'];
    public $cascadeId = '';
    public $ednaType = 'PHONE';

    public function __construct(?string $api = null, ?int $cascadeId = null)
    {
        if (empty($api)) {
            $this->initParamsBase();
        }
        $this->headers[] = "x-api-key:{$api}";
    }

    public function initParamsBase()
    {
        $edna = (new EdnaModel(['is_actual' => 1]));
        $api = $edna?->api;
        $this->cascadeId = $edna?->cascade_id;
         $this->headers[1] = "x-api-key:{$api}";
    }
    /**
     * send
     *
     * @param  array $data
     * @param  string $url
     * @return array
     */
    private function send(array $data, string $url): array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        $result = curl_exec($curl);
        $result = json_decode($result, true)?  json_decode($result, true) : $result;
        return [ 'response' => $result ];
    }

    /**
     * sendWhatsapp
     *
     * @param  array $body
     * @param  mixed $request
     * @return array
     */
    public function sendWhatsapp(string $phone, array $body, mixed &$request): array
    {
        $data = [
            'requestId' => uniqid().uniqid().str_replace([' ','.'], '', microtime()),
            'cascadeId' => $this->cascadeId,
            'subscriberFilter' => [
                'type' => $this->ednaType,
                'address' => $phone
            ],
            'content' => [
                'whatsappContent' => $body,
            ]
        ];
        $request = $data;
        return $this->send($data, self::URL_EDNA_CASCADE_SCHEDULE);
    }

    public function getChanalProfile()
    {
        $url = self::URL_EDNA_CHANNEL_PROFILE . '?types=WHATSAPP';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        $result = json_decode($result, true)?  json_decode($result, true) : $result;
        return [ 'response' => $result ];
    }

    public function sendSMS(string $phone, array $body, mixed &$request): array
    {
        $data = [
            'requestId' => uniqid().str_replace(' ', '', microtime()),
            'cascadeId' => $this->cascadeId,
            'subscriberFilter' => [
                'type' => $this->ednaType,
                'address' => $phone
            ],
            'content' => [
                'smsContent' => $body,
            ]
        ];
        $request = $data;
        return $this->send($data, self::URL_EDNA_CASCADE_SCHEDULE);
    }
}
