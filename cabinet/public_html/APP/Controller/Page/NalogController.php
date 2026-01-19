<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Enum\NalogStatus;
use APP\Model\ClinicModel;
use APP\Model\LicenseModel;
use APP\Model\NalogClinicFilesModel;
use APP\Model\NalogClinicModel;
use APP\Model\NalogModel;
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

    public function downloand()
    {
        $id = supple('id');
        $nalog = new NalogModel((int)$id);
        if (!$nalog->exist()) {
            NotFound::code404([
                'header' => 'Не верные параметры запроса заявки или заявка не найдена'
            ]);
        }

        $nalog->status = NalogStatus::ISSUED;

        $nalogClinic = (new NalogClinicModel())->findM(['nalog_id' => $nalog->id], function (Model $m){
            $m->select(
                'c.*',
                'nalog_clinic.*',
                'nalog_clinic.id id'
            );
            $m->join('clinic c')->on('nalog_clinic.clinic_id = c.id');
            $m->where('no_doc = 0 or no_doc = NULL');
        });

        $filsUrl = [];
        foreach ($nalogClinic as $cl) {

            $cl->status = NalogStatus::ISSUED;
            $nalogClinicFiles = (new NalogClinicFilesModel())->findM(['nalog_clinic_id' => $cl->id]);
            if (!isset($filsUrl[$cl->clinic_id])) $filsUrl[$cl->clinic_id] = [
                'clinic' => $cl,
                'files' => [],
                'license' => new LicenseModel($cl->license_id)
            ];
            foreach ($nalogClinicFiles as $file) {
                $filsUrl[$cl->clinic_id]['files'][] = $file;
            }
        }

        view('page.nalog.init', [
            'header' => "Печать по заявке #" . $nalog->id,
            'files' => $filsUrl,
            'desktop' => '/nalog/block/download.php'
        ]);
    }
}
