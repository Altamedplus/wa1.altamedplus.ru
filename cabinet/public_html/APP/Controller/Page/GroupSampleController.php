<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Model\ButtonsModel;
use APP\Model\ClinicModel;
use APP\Model\GroupSampleModel;
use APP\Module\Auth;
use APP\Model\sampleModel;
use Pet\Request\Request;

class GroupSampleController extends PageController
{

    public function index(Request $request)
    {
        view('page.group_sample.init', [
            "header" => "Группы шаблонов",
            "data" => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $group =  new GroupSampleModel(['id'=> $id]);
        if (!$group ->exist()) {
            NotFound::code404([
                 'header' => 'Группа шаблонов не найдна'
            ]);
        }

        view('page.group_sample.init', [
            "header" => "Редактирование группы шаблона",
            "formInfo" => $group->getInfo(),
            'desktop' => '/group_sample/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.group_sample.init', [
            "header" => "Добавление группы шаблона",
            'desktop' => '/group_sample/block/edit.php'
        ]);
    }
}
