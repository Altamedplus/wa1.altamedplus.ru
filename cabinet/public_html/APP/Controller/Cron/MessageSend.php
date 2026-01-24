<?php

namespace APP\Controller\Cron;

use APP\Enum\StatusMessage;
use APP\Model\Contact;
use APP\Model\MessageModel;
use APP\Module\Max\Messenger;
use APP\Module\WhatsApp;
use Pet\Controller;
use Pet\Router\Response;

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

            if ($m['type_send'] == 0) { //отправка в ватсам
                $data = json_decode($m['data_request'], true);
                $result = (new WhatsApp())->sendWhatsapp($phone, $data, $request);
                $resultControl[] = [$request, $result];
                $requestId = $result['response']['requestId'] ?? null;
            }

            if ($m['type_send'] == 1) { //отправка в Макс
                $max = new Messenger();
                $contact = new Contact(['phone' => $phone]);
                $userId = $contact->max_user_id;
                if (!empty($userId)) {
                    $request = $m['data_request'];
                    $result =  $max->sendMessangeUser($m['data_request'], $userId);
                    $requestId = $result['response']['message']['body']['mid'] ?? null;
                    $resultControl[] = [$request, $result];
                    if (empty($requestId)) {
                        $status = StatusMessage::DELIVERED_WA;
                    } else {
                        $status = StatusMessage::UNDELIVERED_WA;
                    }
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
