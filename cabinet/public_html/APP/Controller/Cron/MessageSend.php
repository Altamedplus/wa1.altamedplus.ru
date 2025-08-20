<?php
namespace APP\Controller\Cron;

use APP\Enum\StatusMessage;
use APP\Model\MessageModel;
use APP\Module\Auth;
use APP\Module\WhatsApp;
use Pet\Controller;
use Pet\Errors\AppException;
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
            $data = json_decode($m['data_request'], true);
            $result = (new WhatsApp())->sendWhatsapp($phone, $data, $request);
            $resultControl[] = [$request, $result];
            $mess = new MessageModel(['id' => $m['id']]);
            $mess->set([
                'send_date' => date('Y-m-d'),
                'send_time' => date('h:i:s'),
                'status' => StatusMessage::SEND_WA,
                'request_id' => $result['response']['requestId'] ?? null,
                'data_response' => json_encode($result, JSON_UNESCAPED_UNICODE)
            ]);
            usleep(300000); //Задержка 300мс
        }
        header(Response::TYPE_JSON);
        return $resultControl;
    }
}
