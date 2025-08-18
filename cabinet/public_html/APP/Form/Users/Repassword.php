<?php

namespace APP\Form\Users;

use APP\Form\Form;
use APP\Model\UsersModel;
use APP\Module\Auth;
use APP\Module\UI\Fire;
use Pet\Request\Request;

class Repassword extends Form
{
    public $auth = true;
    public function submit(Request $request)
    {
        $fields = attrs();
        if (empty($fields['password'])) {
            return new Fire("Поле пароля должно быть запонено", Fire::ERROR);
        }
        if ($fields['password'] != $fields['password_two']) {
            return new Fire("Пароли не совподают", Fire::ERROR);
        }

        if ($valid = Form::validatePassword($fields['password'])) {
            return new Fire($valid, Fire::ERROR);
        }
        (new UsersModel(['id' => Auth::$profile['id']]))->set([
            'temporary_password' => 2,
            'password' => password_hash(SALT . $fields['password'], PASSWORD_DEFAULT),
        ]);

        return [
            'type' => 'redirect',
            'href' => "/"
        ];
    }
}
