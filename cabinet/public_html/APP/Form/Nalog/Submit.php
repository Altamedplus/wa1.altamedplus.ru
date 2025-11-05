<?php

namespace APP\Form\Nalog;

use APP\Form\Form;
use APP\Model\NalogClinicModel;
use APP\Model\NalogCommentModel;
use APP\Model\NalogModel;
use APP\Module\Auth;
use Pet\Request\Request;

class Submit extends Form
{
    public $auth = false;
    public $isCheckToken = false;
    public function submit(Request $request)
    {
        $error = [
            'name' => [],
            'message' => [],
        ];
        $fields = attr() ?: [];
        foreach ($fields as $name => $v) {
            if (empty($v)) {
                $error['name'][] = $name;
                $error['message'][] = 'Не все поля заполнены';
            }
        }
        if (strlen($fields['inn']) != 12) {
            $error['name'][] = 'inn';
            $error['message'][] = 'Количество символов у ИНН не меннее 12';
        }
        $phone  = Form::sanitazePhone($fields['phone']);
        if (!Form::validatePhone($phone)) {
            $error['name'][] = 'phone';
            $error['message'][] = 'Невалидный номер телефона';
        }
        $email = trim($fields['email']);
        if (!Form::validateEmail($email)) {
            $error['name'][] = 'email';
            $error['message'][] = 'Невалидный email';
        }
        if (empty(($period = self::getPeriod($fields)))) {
            $error['name'][] = 'period';
            $error['message'][] = 'Выберете год получения правки';
        }
        if (!self::isClinic($fields)) {
            $error['name'][] = 'clinics';
            $error['message'][] = 'Выберете кинику';
        }

        if (!empty($error['name'])) {
            return self::errorInput($error['name'], $error['message']);
        }

        $data = [
            'email' => $email,
            'name' => $fields['name'],
            'date_birth' => date('Y-m-d', strtotime($fields['date_birth'])),
            'phone' => $phone,
            'nalog_year' => $period,
            'inn' => trim($fields['inn']),
            'taxpayer_type_id' => $fields['taxpayer'],
            'taxpayer_fio' => trim($fields['fio_nalog']),
            'hash' => uniqid(),
        ];
        $nalogId = (new NalogModel())->create($data);
        $clinic = $fields['clinic'] ?? [];
        foreach ($clinic as $id => $v) {
            if ((int)$v === 1) {
                (new NalogClinicModel([
                    'nalog_id' => $nalogId,
                    'clinic_id' => $id,
                    'is_place' =>  0
                ], isNotExistCreate: true));
            }
        }

        return ['uniq' => $data['hash']];
    }
    public static function isClinic($fields)
    {
        $isClinic = false;
        foreach ($fields['clinic'] as $id => $v) {
            if ((int)$v === 1) {
                $isClinic = true;
            }
        }
        return $isClinic;
    }

    public static function getPeriod($fields)
    {
        $period = [];
        foreach ($fields['year'] as $year => $isCheck) {
            if ((int)$isCheck === 1) {
                $period[] = $year;
            }
        }
        return implode(", ", $period);
    }
}
