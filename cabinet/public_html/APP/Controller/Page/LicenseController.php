<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Model\ClinicModel;
use APP\Model\LicenseModel;
use APP\Module\Auth;

class LicenseController extends PageController
{

    public function index()
    {
         view('page.license.init', [
            "header" => "Лицензии",
        ]);
    }

    public function edit(): void
    {
        $id = (int)supple('id');
        $license =  new LicenseModel(['id'=> $id]);
        if (!$license->exist()) {
            NotFound::code404([
                 'header' => 'Лицензия не найдна'
            ]);
        }

        view('page.license.init', [
            "header" => "Редактирование лицензии",
            "formInfo" => $license->getInfo(),
            "clinics" => (new ClinicModel)->find(),
            'desktop' => '/license/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.license.init', [
            "header" => "Добавление лицензии",
            "clinics" => (new ClinicModel)->find(),
            'desktop' => '/license/block/edit.php'
        ]);
    }
}
