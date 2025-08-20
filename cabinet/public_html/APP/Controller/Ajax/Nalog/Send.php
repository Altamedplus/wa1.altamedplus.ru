<?php

namespace APP\Controller\Ajax\Nalog;

use APP\Controller\AjaxController;
use APP\Enum\StatusMessage;
use APP\Model\MessageModel;
use APP\Model\NalogClinicModel;
use APP\Model\NalogModel;
use APP\Model\SampleModel;
use APP\Module\Auth;
use APP\Module\WhatsApp;
use Pet\View\View;

class Send extends AjaxController
{

    public function helper()
    {
        $nalog  = new NalogModel(attr('id'));
        $sample = new SampleModel(['name' => 'Готовность справок Налогового вычета']);
        $nalogClinic = new NalogClinicModel(['nalog_id' => $nalog->id, 'is_place' => 1]);
        $variable = [
            'fio' => [$nalog->taxpayer_fio],
            'nalog_id' => [$nalog->id]
        ];
        $data = $sample->complectWhatsApp($sample->id, $variable, [], $nalogClinic->clinic_id);
        // $request = [];
        // $result =  (new WhatsApp())->sendWhatsapp($nalog->phone, $dataWa, $request);
        $messangeId = (new MessageModel())->create([
            'phone' => $nalog->phone,
            'data_request' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'clinic_id' => $nalogClinic->clinic_id,
            'user_id' => Auth::$profile['id'],
            'sample_id' => $sample->id,
            'status' => StatusMessage::QUEUE,
        ]);

        $nalog->is_send = 1;
        return [$messangeId];
    }
}
