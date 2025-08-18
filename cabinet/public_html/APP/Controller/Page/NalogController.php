<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Model\ClinicModel;
use APP\Model\LicenseModel;
use APP\Model\NalogClinicFilesModel;
use APP\Model\NalogClinicModel;
use APP\Module\Auth;
use Pet\Model\Model;
use Pet\Request\Request;

class NalogController extends PageController
{

    public function index(Request $request)
    {
        view('page.nalog.init', [
            "header" => "Налоговый вычет",
            "data" => Auth::$profile,
        ]);
    }

    public function edit()
    {
        $clinic = new ClinicModel(['id' => supple('clinic')]);
        $nalogId = supple('id');
        $license = (new LicenseModel())->findM(['clinic_id' => $clinic->id], function (Model $m){
            $m->orderBy('is_actual', 'DESC');
        });
        $nalogClinic = new NalogClinicModel(['nalog_id' => $nalogId, 'clinic_id' => $clinic->id]);
        $nalogClinicFiles = (new NalogClinicFilesModel())->findM(['nalog_clinic_id' => $nalogClinic->id]);

        if (!$clinic->exist() || !$nalogClinic->exist() || empty($nalogId)) {
            NotFound::code404([
                'header' => 'Не верные параметры запроса заявки или заявка не найдена'
            ]);
        }

        if (empty($nalogClinicFiles)) {
            $nalogClinicFiles[] = (object)[
                'nalog_clinic_id' => '',
                'url_file' => '',
                'path' => '',
                'name' => '',
                'origin' => '',
                'relat' => '',
            ];
        }

         view('page.nalog.init', [
            'header' => "Заявка #" . $nalogId,
            'headerClinic' =>  $clinic->name,
            'nalogClinic' => $nalogClinic,
            'id' => $nalogId,
            'licenses' => $license,
            'files' => $nalogClinicFiles,
            'desktop' => '/nalog/block/edit.php'
        ]);
    }
}
