<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Model\ButtonsModel;
use APP\Model\ButtonsSampleModel;
use APP\Model\ClinicModel;
use APP\Model\ClinicsSampleModel;
use APP\Model\GroupSampleModel;
use APP\Model\HeaderSampleModel;
use APP\Model\RoleSampleModel;
use APP\Module\Auth;
use APP\Model\SampleModel;
use APP\Model\VariableModel;
use APP\Module\Tool;
use Pet\Request\Request;
use Pet\Tools\Tools;

class SampleController extends PageController
{

    public function index(Request $request)
    {
        view('page.sample.init', [
            'header' => 'Шаблоны',
            'data' => Auth::$profile,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int)supple('id');
        $sample =  new SampleModel(['id'=> $id]);
        if (!$sample->exist()) {
            NotFound::code404([
                 'header' => 'Шаблон не найдна'
            ]);
        }

        view('page.sample.init', [
            'header' => 'Редактирование шаблона',
            'formInfo' => $sample->getInfo(),
            'clinics' => (new ClinicModel())->find(),
            'buttons' => (new ButtonsModel())->find(),
            'groupSample' => (new GroupSampleModel())->find(),
            'variables' => (new VariableModel())->find(),
            'roleSample' => Tool::value('user_type', (new RoleSampleModel())->find(['sample_id' => $id])),
            'clinicsSample' => Tool::value('clinic_id', (new ClinicsSampleModel())->find(['sample_id' => $id])),
            'buttonsSample' => Tool::value('buttons_id', (new ButtonsSampleModel())->find(['sample_id' => $id])),
            'headerSample' => new HeaderSampleModel(['sample_id' => $id]),
            'desktop' => '/sample/block/edit.php'
        ]);
    }

    public function add(): void
    {
        view('page.sample.init', [
            'header' => 'Добавление шаблона',
            'clinics' => (new ClinicModel())->find(),
            'buttons' => (new ButtonsModel())->find(),
            'groupSample' => (new GroupSampleModel())->find(),
            'variables' => (new VariableModel())->find(),
            'roleSample' => [],
            'clinicsSample' => [],
            'buttonsSample' => [],
            'headerSample' => [],
            'desktop' => '/sample/block/edit.php'
        ]);
    }
}
