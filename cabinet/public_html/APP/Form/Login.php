<?php

namespace APP\Form;

use APP\Module\UI\Fire;
use APP\Module\Auth;
use APP\Module\Tool;
use APP\Model\UsersModel;
use Pet\Cookie\Cookie;
use Pet\Request\Request;

class Login extends Form
{
    public function submit(Request $request)
    {
        $fields = attrs();
        foreach ($fields as $k => $field) {
            if (empty($field)) {
                return new Fire("Не все поля заполнены", Fire::ERROR);
            }
        }
        $phone = Form::sanitazePhone((string)$fields['login']);

        if (!Form::validatePhone($phone)) {
            return new Fire("Невалидный номер телефона", Fire::ERROR);
        }

        $User = new UsersModel(['phone' => $phone]);
        if (!$User->isInfo()) {
            return new Fire("Такого пользователя не существует", Fire::ERROR);
        };

        $password =  $User->get('password');
        if (!password_verify(SALT . $fields['password'], $password)) {
            return new Fire("Неверный пароль", Fire::ERROR);
        }

        $token = ['auth' => Tool::tokenRandom()];
        Cookie::set($token);
        $User->set($token);
        return ['type' => 'reload'];
    }
}
