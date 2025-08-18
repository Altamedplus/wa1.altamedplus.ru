<?php

namespace APP\Controller\Api\Edna;

use APP\Enum\StatusMessage;
use APP\Model\MessageModel;
use APP\Module\Auth;
use Pet\Controller;
use Pet\Request\Request;
use Pet\Router\Response;

class StatusController extends Controller
{
    public function index()
    {
        $data = attr();
        $requestId = $data['requestId'] ?? null;
        if ($requestId) {
            $messange = new MessageModel(['request_id' => $requestId]);
            if ($messange->exist()) {
                $messange->set(['status' => StatusMessage::getEdna($data['status'])]);
            }
        }
        return json_encode('HTTP/1.1 200 OK');
    }
}
