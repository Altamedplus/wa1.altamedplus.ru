<?php

namespace APP\Controller\Cron;

use APP\Enum\UsersType;
use APP\Form\Form;
use APP\Model\UsersModel;
use Pet\Controller;
use Pet\Model\Model;
use Pet\Router\Response;

class AuthReset extends Controller
{
    public function __construct()
    {
        Response::set(Response::TYPE_JSON);
    }

    public function index()
    {
        $users = (new UsersModel())->findM(callback: function (Model $m) {
            $m->where('type', [UsersType::ADMIN, UsersType::DOCTOR, UsersType::MARKETING]);
        });
        foreach ($users as $user) {
            $user->auth = null;
        }
        return ['Сброшена авторизация количество пользователей : ' . count($users)];
    }

    public function up(){
        $file = file_get_contents(ROOT . DS . 'unclude.json');
        $filejs = json_decode($file, true);
        $data = $filejs['data'];
        foreach ($data as $juser) {
            $names  = explode(" ", $juser['name']);
            $name = $names[0] ?? "";
            $surname = $names[1] ?? "";
            $phone = Form::sanitazePhone($juser['telefone']);
            $userisfone = new UsersModel(['phone' => $phone]);
            if ($userisfone->exist()) {
                continue;
            }
            $role = explode('/', $juser['role'])[0] ?? 'admin';
            $type = match ($role) {
                'senior_admin' => UsersType::SENIOR_ADMIN,
                'marketing' => UsersType::MARKETING,
                default => UsersType::ADMIN
            };

            $password = password_hash(SALT . "testwa1", PASSWORD_DEFAULT);
            (new UsersModel())->create([
                "name" => $name,
                'phone' => $phone,
                'type' => $type,
                'surname' => $surname,
                'password' => $password,
                'temporary_password' => 1
            ]);
        }
    }
}
