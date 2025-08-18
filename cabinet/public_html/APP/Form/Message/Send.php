<?php

namespace APP\Form\Message;

use APP\Enum\StatusMessage;
use APP\Form\Form;
use APP\Model\ButtonsModel;
use APP\Module\UI\Fire;
use APP\Model\EdnaModel;
use APP\Model\MessageModel;
use APP\Model\SampleModel;
use APP\Module\Auth;
use APP\Module\WhatsApp;
use Pet\Request\Request;

class Send extends Form
{
    public $auth = true;
    public function submit(Request $request)
    {
        $sample_id = (int)attr('id');
        $fields = (array)attr();
        $clinicId = attr('clinic');
        $phone = Form::sanitazePhone(attr('phone'));
        $variables = [];
        $result = [];
        $buttons = attr('button');
        foreach ($fields as $name => $field) {
            if (preg_match('/var_([A-Za-z0-9]{1,})/', $name, $m)) {
                $variables[$m[1]] = $field;
                unset($fields[$name]);
            }
        }
        $data = (new SampleModel)->complectWhatsApp($sample_id, $variables, $buttons, $clinicId);
        // $request = [];
        // $result =  (new WhatsApp())->sendWhatsapp($phone, $data, $request);
        $messangeId = (new MessageModel())->create([
            'phone' => $phone,
            'data_request' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'clinic_id' => $clinicId,
            'user_id' => Auth::$profile['id'],
            'sample_id' => $sample_id,
            'status' => StatusMessage::QUEUE,
        ]);

        return new Fire('Сообщение поставлено в очередь на отправку');
    }
}
