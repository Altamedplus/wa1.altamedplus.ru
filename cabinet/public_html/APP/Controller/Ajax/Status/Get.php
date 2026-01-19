<?php

namespace APP\Controller\Ajax\Status;

use APP\Controller\AjaxController;
use APP\Enum\StatusMessage;
use APP\Model\MessageModel;
use APP\Module\Auth;
use Pet\Model\Model;
use Pet\View\View;

class Get extends AjaxController
{

    public function helper()
    {
        $data = attr();
        $date = $data['date'];
        $userId = Auth::$profile['id'];

        $result = (new MessageModel())->find(callback: function (Model $m) use ($date, $userId) {
            $m->select(
                "message.id",
                "message.status",
                "message.type_send",
                "message.phone",
                "DATE_FORMAT(message.cdate, '%H:%i') AS time",
                "smw.name"
            );
            $m->join('sample_messange_wa smw')->on(['smw.id', 'message.sample_id']);
            $m->where("message.user_id = $userId");
            $m->where("message.cdate BETWEEN '$date 00:00:00' AND '$date 23:59:59'");
            $m->orderBy("message.cdate", "DESC");
        });
        $html = '';
        foreach ($result as $arg) {
            $arg['status'] = $this->getStatus($arg['status']);
            $html .= View::getTemplate('template.status', $arg);
        }

        return ['html' => $html];
    }

    private function getStatus($status)
    {
        if (in_array($status, [StatusMessage::QUEUE, StatusMessage::SEND_WA, StatusMessage::SENT_WA])) {
            return 'send';
        }
        if (StatusMessage::DELIVERED_WA == $status) {
            return 'delivered';
        }
        if (StatusMessage::READ_WA == $status) {
            return 'read';
        }
        if (StatusMessage::UNDELIVERED_WA == $status) {
            return 'error';
        }
        return 'send';
    }
}