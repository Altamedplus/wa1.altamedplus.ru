<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use Pet\Request\Request;
use APP\Enum\UsersType as UT;
use APP\Model\ClinicModel;

class HomeController extends PageController
{

    public function index(Request $request)
    {
        if (Auth::$profile['temporary_password'] == 1) {
            $this->rePassword();
            exit;
        }
        view('page.home.init', [
            "header" => "Отправка сообщений",
            "data" => Auth::$profile,
            "clinics" => (new ClinicModel())->find(),
            "headerButtons" => [[
                'tag' => "button",
                'class' => 'btn-round btn-content-messange',
                'data-open' => 'message',
                'tabindex' => '-1'
            ],
             [
                'tag' => 'button',
                'class' => 'btn-round btn-content-log-in',
                'evt' => 'exit',
                'data' => 'log-in',
                'tabindex' => '-1'
            ]
            ],
        ]);
    }

    public function rePassword()
    {
        view('page.home.init', [
            "header" => "Изменение временного пароля",
            "data" => Auth::$profile,
            'desktop' => '/home/block/repassword.php'
        ]);
    }
}
