<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use Pet\Request\Request;
use APP\Enum\UsersType as UT;
use APP\Model\ClinicModel;

class MessageController extends PageController
{

    public function index(Request $request)
    {
        view('page.message.init', [
            "header" => "Отправленные",
            "data" => Auth::$profile,
            "filter" => [
                'id' => $_GET['id'] ?? ''
            ]
        ]);
    }
}
