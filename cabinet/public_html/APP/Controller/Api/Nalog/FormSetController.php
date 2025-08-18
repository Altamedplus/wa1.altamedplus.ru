<?php

namespace APP\Controller\Api\Nalog;

use APP\Form\Form;
use APP\Model\NalogClinicModel;
use APP\Model\NalogModel;
use APP\Model\Table\Cliniclist;
use APP\Model\TaxpayerListModel;
use Pet\Controller;

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
            'taxpayer_fio' => $form['taxpayer_fio']
        ];
        $nalogId = (new NalogModel())->create($data);
        $hash =  hash('sha256', $nalogId . implode('', $data)); // создаем hash
        (new NalogModel($nalogId))->set('hash', $hash);

        foreach ($form['clinic_service'] as $bitrixId) {
            (new NalogClinicModel([
                'nalog_id' => $nalogId,
                'clinic_id' => (new Cliniclist(['bitrix_id' => $bitrixId]))->id,
                'is_place' =>  (int)$bitrixId == (int)$form['clinic_receive'] ? 1 : 0
            ], isNotExistCreate: true));
        }
        return ["ok"];
    }
}
