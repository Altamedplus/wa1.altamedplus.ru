<?php

namespace APP\Form\Nalog;

use APP\Form\Form;
use APP\Model\NalogCommentModel;
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
        if (!empty($error['name'])) {
            return self::errorInput($error['name'], $error['message']);
        }

    }
}
