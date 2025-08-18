<?php

namespace APP\Form\Users;

use APP\Enum\StatusMessage;
use APP\Form\Form;
use APP\Model\MessageModel;
use APP\Model\SampleModel;
use APP\Model\UsersModel;
use APP\Module\Auth;
use APP\Module\UI\Fire;
use Pet\Model\Model;
use Pet\Request\Request;

class Add extends Form
{
    public $auth = true;
    public function submit(Request $request)
    {
        $fields = attrs();
        if (empty($fields['phone'])) {
            return new Fire('Требуется ввести телефон', Fire::ERROR);
        }

        if (empty($fields['name'])) {
            return new Fire('Введите имя', Fire::ERROR);
        }

        if (empty($fields['surname'])) {
            return new Fire('Введите Фамилию', Fire::ERROR);
        }
        $phone = Form::sanitazePhone($fields['phone']);


        $id = (int)$fields['id'];
        $user = new UsersModel(['id' => $id]);
        if (!empty($id) && $user->exist()) {
            $count = count((new UsersModel())->find(callback:function (Model $m) use ($id, $phone) {
                $m->where("users.id != $id AND users.phone = $phone");
            }));
            if ($count > 0) {
                return new Fire('Такой пользователь существует', Fire::ERROR);
            }
            if ($user->id == Auth::$profile['id'] && $fields['type'] != $user->type) {
                return new Fire('Попытка сменить тип упользователя у самого себя. Не возможно!', Fire::ERROR);
            }
            $user->set([
                'type' => $fields['type'],
                'phone' => $phone,
                'name' => $fields['name'],
                'surname' => $fields['surname']
            ]);
        } else {
            if ((new UsersModel(['phone' => $phone]))->exist()) {
                return new Fire('Такой пользователь существует', Fire::ERROR);
            }
            $sample = (new SampleModel(['name' => 'Временный пароль']));
            $password = Form::generatePassword();
            if (!$sample->exist()) {
                return new Fire('Нет шаблона с именем Временный пароль', Fire::ERROR);
            }
            $data = $sample->complectWhatsApp($sample->id, ['password' => [$password]]);
            $messangeId = (new MessageModel())->create([
                'phone' => $phone,
                'data_request' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::$profile['id'],
                'sample_id' => $sample->id,
                'status' => StatusMessage::QUEUE,
            ]);
            $id = $user->create([
                'type' => $fields['type'],
                'phone' => $phone,
                'name' => $fields['name'],
                'surname' => $fields['surname'],
                'password' => password_hash(SALT.$password, PASSWORD_DEFAULT),
                'temporary_password' => 1
            ]);
        }

            return [
                'type' => 'redirect',
                'href' => "/users/edit/$id"
            ];
    }
}
