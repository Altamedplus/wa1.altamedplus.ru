<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Model\VariableModel;
use APP\Module\Auth;
use Pet\Request\Request;

class VariableController extends PageController
{

    public function index(Request $request)
    {
        view('page.variable.init', [
            "header" => "Переменные",
            "data" => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $variable =  new VariableModel(['id'=> $id]);
        if (!$variable ->exist()) {
            NotFound::code404([
                 'header' => 'Перемнная не найдна'
            ]);
        }

        view('page.variable.init', [
            "header" => "Редактирование переменной",
            "formInfo" => $variable->getInfo(),
            'desktop' => '/variable/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.variable.init', [
            "header" => "Добавление переменной",
            'desktop' => '/variable/block/edit.php'
        ]);
    }
}
