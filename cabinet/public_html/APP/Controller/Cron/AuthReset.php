<?php

namespace APP\Controller\Cron;

use APP\Enum\UsersType;
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
}
