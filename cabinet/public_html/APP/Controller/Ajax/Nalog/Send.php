<?php

namespace APP\Controller\Ajax\Nalog;

use APP\Controller\AjaxController;
use APP\Model\NalogClinicModel;
use APP\Model\NalogModel;
use APP\Model\SampleModel;
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
        $dataWa = $sample->complectWhatsApp($sample->id, $variable, [], $nalogClinic->clinic_id);
        $request = [];
        $result =  (new WhatsApp())->sendWhatsapp($nalog->phone, $dataWa, $request);
        $nalog->is_send = 1;
        return [$request, $result];
    }
}
