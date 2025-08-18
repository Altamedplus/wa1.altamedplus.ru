<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use APP\Model\EdnaModel;
use APP\Model\ClinicModel;
use Pet\Request\Request;

class EdnaController extends PageController
{

    public function index(Request $request)
    {
        view('page.edna.init', [
            "header" => "Edna",
            "data" => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $edna =  new EdnaModel(['id'=> $id]);
        if (!$edna->exist()) {
            NotFound::code404([
                 'header' => 'Кнопка не найдна'
            ]);
        }

        view('page.edna.init', [
            "header" => "Редактирование данных Edna",
            "formInfo" => $edna->getInfo(),
            'desktop' => '/edna/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.edna.init', [
            "header" => "Добавить новую Edna",
            'desktop' => '/edna/block/edit.php'
        ]);
    }
}
