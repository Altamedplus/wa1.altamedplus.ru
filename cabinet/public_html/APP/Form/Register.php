<?php

namespace APP\Form;

use APP\Module\UI\Fire;
use APP\Module\Auth;
use APP\Module\Mail;
use APP\Module\Newtell;
use APP\Module\Smsru as ModuleSmsru;
use APP\Module\Tool;
use Model\UsersModel;
use Pet\Cookie\Cookie;
use Pet\Request\Request;
use Pet\Tools\Tools;

class Register extends Form
{
    public function submit(Request $request)
    {
        $fields = attrs();
        if(isset($fields['code'])){ // Подтверждение почты
            return $this->confirmEmail(trim($fields['email']), trim($fields['code']));
        }
        foreach ($fields as $k => $field) {
            if (empty($field)) {
                return new Fire("Не все поля заполнены", Fire::ERROR);
            }
        }
        if (!Form::validateEmail($fields['email'])) {
            return new Fire("Не верный email", Fire::ERROR);
        }

        if ($fields['password'] != $fields['password-two']) {
            return new Fire("Пароли не совподают", Fire::ERROR);
        }
        if (($validate = Form::validatePassword($fields['password']))) {
            return new Fire($validate, Fire::ERROR);
        }

        $user = (new UsersModel(['email'=> trim($fields['email'])]));
        if ($user->exist() && (int)$user->get('confirmed_email') == 1) {
            return new Fire("Такой пользователь существует", Fire::ERROR);
        }
        $code = rand(100000, 999999);
        $data = [
                'name' => trim($fields['name']),
                'surname' => trim($fields['surname']),
                'email' =>  trim($fields['email']),
                'password' => password_hash(SALT . $fields['password'], PASSWORD_DEFAULT),
                'password_length' => strlen($fields['password']),
                'code' => $code
        ];
        !$user->exist() ? $user->create($data) : $user->set($data);
        $user = $user->reboot();

        (new Mail())->send($user->email, $user->name, 'Подтвердите Email', "Ваш код подтверждения: <b>$code</b>");
        return [
            'type' => 'modal',
            'template' => 'confirmation',
            'header' => 'Подтвердите email',
            'form_name' => 'register',
            'email' => $user->email
        ];
    }

    public static function confirmEmail($email, $code)
    {
        $user = new UsersModel(['email'=>$email, 'code'=>$code]);
        if ($user->exist()) {
            $user->set(['confirmed_email' => 1]);
            return [
                'type' => 'redirect',
                'href' => '/login'
            ];
        }
        return new Fire('Неверный код', Fire::ERROR);
        
    }
}
