<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use APP\Model\ClinicModel;
use Pet\Request\Request;

class ClinicController extends PageController
{

    public function index(Request $request)
    {
        view('page.clinic.init', [
            "header" => "Клиники",
            "data" => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $clinic =  new ClinicModel(['id'=> $id]);
        if (!$clinic->exist()) {
            NotFound::code404([
                 'header' => 'Клиника не найдна'
            ]);
        }

        view('page.clinic.init', [
            "header" => "Редактирование клиники",
            "formInfo" => $clinic->getInfo(),
            'desktop' => '/clinic/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.clinic.init', [
            "header" => "Добавление клиники",
            'desktop' => '/clinic/block/edit.php'
        ]);
    }
}