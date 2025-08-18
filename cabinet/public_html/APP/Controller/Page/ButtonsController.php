<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use APP\Model\ButtonsModel;
use APP\Model\ClinicModel;
use Pet\Request\Request;

class ButtonsController extends PageController
{

    public function index(Request $request)
    {
        view('page.buttons.init', [
            "header" => "Кнопки",
            "data" => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $buttons =  new ButtonsModel(['id'=> $id]);
        if (!$buttons->exist()) {
            NotFound::code404([
                 'header' => 'Кнопка не найдна'
            ]);
        }

        view('page.buttons.init', [
            "header" => "Редактирование кнопки",
            "formInfo" => $buttons->getInfo(),
            'desktop' => '/buttons/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.buttons.init', [
            "header" => "Добавление кнопки",
            'desktop' => '/buttons/block/edit.php'
        ]);
    }
}
