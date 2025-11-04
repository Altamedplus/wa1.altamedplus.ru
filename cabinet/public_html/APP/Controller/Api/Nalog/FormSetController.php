<?php

namespace APP\Controller\Api\Nalog;

use APP\Form\Form;
use APP\Model\NalogClinicModel;
use APP\Model\NalogModel;
use APP\Model\Table\Cliniclist;
use APP\Model\TaxpayerListModel;
use Pet\Controller;
use Pet\Router\Response;
use Pet\View\View;

class FormSetController extends Controller
{

    public function options()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        http_response_code(200);
    }

    public function index()
    {
        $this->options();
        $form = (array)attr();
        foreach ($form['taxpayer_list'] as $id => $list) {
            $taxPayer = new TaxpayerListModel(['type_id' => $id], isNotExistCreate: true);
            $taxPayer->set(['name' => $list]);
        }

        $data = [
            'email' => $form['email'],
            'name' => $form['fio'],
            'date_birth' => date('Y-m-d', strtotime($form['date_birth'])),
            'phone' => Form::sanitazePhone($form['phone']),
            'nalog_year' => $form['period'],
            'inn' => $form['taxpayer_inn'],
            'taxpayer_type_id' => $form['taxpayer_type_id'],
            'taxpayer_fio' => $form['taxpayer_fio'],
            'hash' => $form['hash'] ?? ''
        ];
        $nalogId = (new NalogModel())->create($data);

        foreach ($form['clinic_service'] as $bitrixId) {
            (new NalogClinicModel([
                'nalog_id' => $nalogId,
                'clinic_id' => (new Cliniclist(['bitrix_id' => $bitrixId]))->id,
                'is_place' =>  (int)$bitrixId == (int)$form['clinic_receive'] ? 1 : 0
            ], isNotExistCreate: true));
        }
        // клиника без оформления
        if (!in_array((int)$form['clinic_receive'], $form['clinic_service'])) {
            (new NalogClinicModel([
                'nalog_id' => $nalogId,
                'clinic_id' => (new Cliniclist(['bitrix_id' => $form['clinic_receive']]))->id,
                'is_place' => 1,
                'no_doc' => 1,
            ], isNotExistCreate: true));
        }
        return ["ok"];
    }

    public function getWidgetJs()
    {
        header('Content-type: application/javascript');
        $folder = View::DIR_VIEW . '/assets/js';
        $filetimeApi = 0;

        $fileJs = '';
        $fileRoot = '';
        $filetimeApiRoot = 0;
        foreach (scandir($folder) as $file) {
            if (str_contains($file, "api_") && $filetimeApi < ($time = filectime("$folder/$file"))) {
                $filetimeApi = $time;
                $fileJs = $file;
            }
            if (str_contains($file, "root_") && $filetimeApiRoot < ($time = filectime("$folder/$file"))) {
                $filetimeApiRoot = $time;
                $fileRoot = $file;
            }
        }
        echo  file_get_contents("$folder/$fileRoot");
    }

    public function html() {
        view('api.nalogform');
    }
}
