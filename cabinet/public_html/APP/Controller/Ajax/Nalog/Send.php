<?php

namespace APP\Controller\Ajax\Nalog;

use APP\Controller\AjaxController;
use APP\Enum\HistoryType;
use APP\Enum\StatusMessage;
use APP\Enum\TypeAutorization;
use APP\Enum\TypeCannel;
use APP\Form\Form;
use APP\Model\Contact;
use APP\Model\HistoryModel;
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
        $id = (int)attr('id');
        $nalog  = new NalogModel($id);
        $phone = $nalog->phone;
        $sample = new SampleModel(['name' => 'Готовность налог 2026']);
        //$nalogClinic = new NalogClinicModel(['nalog_id' => $nalog->id, 'is_place' => 1]);
        $variable = [
            'fio' => [$nalog->taxpayer_fio],
            'nalogid' => [$nalog->id]
        ];
        $contactMax = new Contact(['phone' => $phone, 'step_authorization' => TypeAutorization::AUTORIZATION]);
        $contactTg = new Contact(['phone' => $phone, 'tg_step_auth' => TypeAutorization::AUTORIZATION]);
        $isWA = true;
        if ($contactMax->exist()) {
            $dataMax = $sample->complectMax($sample->id, $variable, [], null);
            $messangeId = (new MessageModel())->create([
                'phone' => $nalog->phone,
                'data_request' => json_encode($dataMax, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::$profile['id'],
                'sample_id' => $sample->id,
                'status' => StatusMessage::QUEUE,
                'type_send' =>  TypeCannel::MAX,
            ]);
            $isWA = false;
        }
        if ($contactTg->exist()) {
            $dataTg = $sample->complectTelegram($sample->id, $variable, [], null);
            $messangeId = (new MessageModel())->create([
                'phone' => $nalog->phone,
                'data_request' => json_encode($dataTg, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::$profile['id'],
                'sample_id' => $sample->id,
                'status' => StatusMessage::QUEUE,
                'type_send' =>  TypeCannel::TELEGRAM,
            ]);
            $isWA = false;
        }

        if ($isWA) {
            $dataWa = $sample->complectWhatsApp($sample->id, $variable, [], null);
            $messangeId = (new MessageModel())->create([
                'phone' => $nalog->phone,
                'data_request' => json_encode($dataWa, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::$profile['id'],
                'sample_id' => $sample->id,
                'status' => StatusMessage::QUEUE,
            ]);
        }
        // $request = [];
        // $result =  (new WhatsApp())->sendWhatsapp($nalog->phone, $dataWa, $request);


        $nalog->is_send = 1;
        (new HistoryModel())->create([
            'entity_id' => $nalog->id,
            'user_id' => Auth::$profile['id'],
            'type' => HistoryType::ADD,
            'field' => 'nalog.is_send',
            'entity' => 'nalog',
            'new_change' => "Отправил на номер телефона " . Form::unsanitazePhone($nalog->phone)
        ]);
        return [$messangeId];
    }
}
