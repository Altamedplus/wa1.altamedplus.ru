<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use APP\Model\UsersModel;
use Pet\Request\Request;

class UsersController extends PageController
{

    public function index(Request $request)
    {
        view('page.users.init', [
            "header" => "Пользователи",
            "data" => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $user =  new UsersModel(['id'=> $id]);
        if (!$user->exist()) {
            NotFound::code404([
                 'header' => 'Пользователь не найден'
            ]);
        }

        view('page.users.init', [
            "header" => "Редактирование пользователя",
            "formInfo" => $user->getInfo(),
            'desktop' => '/users/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.users.init', [
            "header" => "Добавление пользователя",
            'desktop' => '/users/block/edit.php'
        ]);
    }
}