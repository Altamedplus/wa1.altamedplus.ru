<?php

namespace APP\Controller\Cron;

use APP\Controller\Api\Max\SubscriptionsController;
use APP\Controller\Api\Telegram\WebHookController;
use APP\Enum\StatusMessage;
use APP\Enum\TypeAutorization;
use APP\Enum\TypeCannel;
use APP\Model\Contact;
use APP\Model\MessageModel;
use APP\Module\Max\Messenger;
use APP\Module\Sms;
use APP\Module\Telegram;
use APP\Module\WhatsApp;
use Pet\Controller;
use Pet\Router\Response;
use PharIo\Manifest\Type;

class MessageSend extends Controller
{

    public function __construct()
    {
        Response::set(Response::TYPE_JSON);
    }


    public function index()
    {
        $messanges = (new MessageModel())->find(['status' => StatusMessage::QUEUE]);
        $resultControl = [];
        foreach ($messanges as $m) {
            $request = [];
            $phone = $m['phone'];
            $mess = new MessageModel(['id' => $m['id']]);
            $result = null;
            $requestId = null;
            $status = StatusMessage::SEND_WA;

            if ($m['type_send'] == TypeCannel::WA) { //отправка в ватсам
                $request = [];
                $data = json_decode($m['data_request'], true);
                if (is_array($data)) {
                    $result = (new WhatsApp())->sendWhatsapp($phone, $data, $request);
                    $resultControl[] = [$request, $result];
                    $requestId = $result['response']['requestId'] ?? null;
                } else {
                    $status = StatusMessage::UNDELIVERED_WA;
                }
            }

            if ($m['type_send'] == TypeCannel::MAX) { //отправка в Макс
                $max = new Messenger();
                $contact = new Contact(['phone' => $phone, 'step_authorization' => TypeAutorization::AUTORIZATION]);
                $userId = $contact->max_user_id;
                if (!empty($userId)) {
                    $request = $m['data_request'];
                    $result =  $max->sendMessangeUser($m['data_request'], $userId);
                    $requestId = $result['response']['message']['body']['mid'] ?? null;
                    $resultControl[] = [$request, $result];
                    if (!empty($requestId)) {
                        $status = StatusMessage::DELIVERED_WA;
                        $resender = [
                            'timestamp' => round(microtime(true) * 1000),
                            'message' => $result['response']['message'],
                            'update_type' => 'message_created'
                        ];
                        // (new SubscriptionsController())->resenderJivo($resender);
                    } else {
                        $status = StatusMessage::UNDELIVERED_WA;
                    }
                } else {
                    $status = StatusMessage::UNDELIVERED_WA;
                    $result = ['description' => 'not auth in max for table Contact'];
                }

            }

            if ($m['type_send'] ==  TypeCannel::SMS) {
                $smsText = json_decode($m['data_request'], true) ?  (json_decode($m['data_request'], true)['text'] ?? '') : $result;
                $result = (new Sms())->send($phone, $smsText);
                $result = json_decode($result, true) ?  json_decode($result, true) : $result;
                if (isset($result['error']) && !empty($result['error'])) {
                    $status = StatusMessage::UNDELIVERED_WA;
                } else {
                    $status = StatusMessage::DELIVERED_WA;
                }
            }

            if ($m['type_send'] == TypeCannel::TELEGRAM) {
                $tg = new Telegram();
                $contact = new Contact(['phone' => $phone, 'tg_step_auth' => TypeAutorization::AUTORIZATION]);
                $userId = $contact->tg_user_id;
                if (!empty($userId)) {
                    $result = $tg->send($userId, $m['data_request']);
                    if ($result['ok']) {
                        $resend = [
                            'update_id' => random_int(637853450, 9999999999),
                            'message' => $result['result']
                        ];
                        // $resend = (new WebHookController())->resenderJivo($resend);
                        $status = StatusMessage::DELIVERED_WA;
                    } else {
                        $status = StatusMessage::UNDELIVERED_WA;
                    }
                } else {
                    $status = StatusMessage::UNDELIVERED_WA;
                    $result = ['description' => 'not auth in tg for table Contact'];
                }
            }

            $mess->set([
                'send_date' => date('Y-m-d'),
                'send_time' => date('h:i:s'),
                'status' => $status,
                'request_id' => $requestId,
                'data_response' => json_encode($result, JSON_UNESCAPED_UNICODE)
            ]);
             usleep(300000); //Задержка 300мс
        }
        header(Response::TYPE_JSON);
        return $resultControl;
    }
}
