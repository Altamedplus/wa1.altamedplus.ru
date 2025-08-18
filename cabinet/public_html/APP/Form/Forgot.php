<?php

namespace APP\Form;

use APP\Enum\StatusMessage;
use APP\Model\MessageModel;
use APP\Model\SampleModel;
use APP\Model\UsersModel;
use APP\Module\UI\Fire;
use APP\Module\Mail;
use Pet\Request\Request;

class Forgot extends Form
{
    public function submit(Request $request)
    {
        $phone = self::sanitazePhone(attr('login'));
        if (!Form::validatePhone($phone)) {
            return new Fire("Неверный телефон", Fire::ERROR);
        }
        $user = new UsersModel(['phone' => $phone]);
        if (!$user->exist()) {
             return new Fire("Такоко пользователя не существует. Обратитесь в Тех. Поддержку", Fire::ERROR);
        }
        $password = Form::generatePassword();
        $sample = (new SampleModel(['name' => 'Временный пароль']));
        $password = Form::generatePassword();
        if (!$sample->exist()) {
            return new Fire('Нет шаблона с именем Временный пароль. Обратитесь в Тех. Поддержку', Fire::ERROR);
        }
        $user->set([
            'temporary_password' => 1,
            'password' => password_hash(SALT.$password, PASSWORD_DEFAULT)
        ]);
        $data = $sample->complectWhatsApp($sample->id, ['password' => [$password]]);
        $messangeId = (new MessageModel())->create([
            'phone' => $phone,
            'data_request' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'user_id' => $user->id,
            'sample_id' => $sample->id,
            'status' => StatusMessage::QUEUE,
        ]);

        return new Fire('Пароль выслан в WhatsApp');
    }
}
