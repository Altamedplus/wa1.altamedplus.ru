<?php

namespace APP\Form\Message;

use APP\Enum\CheckNumber;
use APP\Enum\StatusMessage;
use APP\Enum\TypeCannel;
use APP\Form\Form;
use APP\Module\UI\Fire;
use APP\Model\MessageModel;
use APP\Model\SampleModel;
use APP\Module\Auth;
use Pet\Cookie\Cookie;
use Pet\Request\Request;
use PharIo\Manifest\Type;

class Send extends Form
{
    public $auth = true;
    public function submit(Request $request)
    {
        $sample_id = (int)attr('id');
        $fields = (array)attr();
        $clinicId = attr('clinic');
        $typesCannel = [TypeCannel::SMS => isset($fields['sms']), TypeCannel::WA => isset($fields['wa']), TypeCannel::TELEGRAM =>  isset($fields['tg']), TypeCannel::MAX => isset($fields['max'])];
        $phone = Form::sanitazePhone(attr('phone'));
        if (!Form::validatePhone($phone)) {
            return new Fire('Не валидный телефон', Fire::ERROR);
        }
        foreach ($fields as $key => $field) {
            if (str_contains($key, 'var_',)) {
                foreach ($fields[$key] as  $value) {
                    if (empty($value)) {
                        return new Fire('Не все поля заполнены', Fire::ERROR);
                    }
                }
            }
        }
        $isPhoneResend =  Form::sanitazePhone((Cookie::get('resend') ?: '')) == $phone;
        $variables = [];
        $result = [];
        $buttons = attr('button');
        $sample = new SampleModel($sample_id);
        $isMessage = (new MessageModel())->exist(['phone' => $phone, 'sample_id' => $sample_id]);

        if ($sample->check_number == CheckNumber::NO_REQUEST && $isMessage) {
            return new Fire('Запрещена повторная отправка', Fire::ERROR);
        }
        if ($sample->check_number == CheckNumber::ASK && $isMessage && !$isPhoneResend) {
            return [
                'type' => 'modal',
                'template' => 'resend',
                'header' => 'Повторная отправка!',
                'content' => 'Такой тип сообщения уже был отправлен на этот номер! Вы уверены что хотите отправить?',
                'callbackModal' => 'initResendModal'
            ];
        }

        foreach ($fields as $name => $field) {
            if (preg_match('/var_([A-Za-z0-9]{1,})/', $name, $m)) {
                $variables[$m[1]] = $field;
                unset($fields[$name]);
            }
        }

        $sendData = [
            'phone' => $phone,
            'clinic_id' => $clinicId,
            'user_id' => Auth::$profile['id'],
            'sample_id' => $sample_id,
            'status' => StatusMessage::QUEUE,
        ];
        foreach ($typesCannel as $type => $isSend) {
            if (!$isSend) continue;
            if ($type == TypeCannel::MAX) {
                $sendData['data_request'] = json_encode((new SampleModel())->complectMax($sample_id, $variables, $buttons, $clinicId), JSON_UNESCAPED_UNICODE);
                $sendData['type_send'] = $type;
            }
            if ($type == TypeCannel::WA) {
                $sendData['data_request'] = json_encode((new SampleModel())->complectWhatsApp($sample_id, $variables, $buttons, $clinicId), JSON_UNESCAPED_UNICODE);
                $sendData['type_send'] = $type;
            }
            if ($type == TypeCannel::TELEGRAM) {
                $sendData['data_request'] = json_encode((new SampleModel())->complectTelegram($sample_id, $variables, $buttons, $clinicId), JSON_UNESCAPED_UNICODE);
                $sendData['type_send'] = $type;
            }
            if ($type == TypeCannel::SMS) {
                $dataSms = (new SampleModel())->complectSms($sample_id, $variables, $buttons, $clinicId);
                if (empty($dataSms['text'])) {
                    return new Fire('Шаблон смс Пуст !!! Заполниете текст смс в шаблоне', Fire::ERROR);
                }
                $sendData['data_request'] = json_encode($dataSms, JSON_UNESCAPED_UNICODE);
                $sendData['type_send'] = $type;
            }
        }
        $messangeId = (new MessageModel())->create($sendData);

        return new Fire('Сообщение поставлено в очередь на отправку');
    }
}
