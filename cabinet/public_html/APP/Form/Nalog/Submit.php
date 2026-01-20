<?php

namespace APP\Form\Nalog;

use APP\Form\Form;
use APP\Model\ClinicModel;
use APP\Model\NalogClinicFilesModel;
use APP\Model\NalogClinicModel;
use APP\Model\NalogCommentModel;
use APP\Model\NalogModel;
use APP\Module\Auth;
use APP\Module\Mail;
use APP\Module\Write\Pdf;
use Exception;
use Pet\Model\Model;
use Pet\Request\Request;
use Pet\View\View;

class Submit extends Form
{
    public $auth = false;
    public $isCheckToken = false;
    public function submit(Request $request)
    {
        $error = [
            'name' => [],
            'message' => [],
        ];
        $fields = attr() ?: [];
        foreach ($fields as $name => $v) {
            if (empty($v)) {
                $error['name'][] = $name;
                $error['message'][] = 'Не все поля заполнены';
            }
        }
        if (strlen($fields['inn_patient']) != 12) {
            $error['name'][] = 'inn_patient';
            $error['message'][] = 'Количество символов у ИНН не меннее 12';
        }

        if (strlen($fields['inn']) != 12) {
            $error['name'][] = 'inn';
            $error['message'][] = 'Количество символов у ИНН не меннее 12';
        }
        $phone  = Form::sanitazePhone($fields['phone']);
        if (!Form::validatePhone($phone)) {
            $error['name'][] = 'phone';
            $error['message'][] = 'Невалидный номер телефона';
        }
        $email = trim($fields['email']);
        if (!Form::validateEmail($email)) {
            $error['name'][] = 'email';
            $error['message'][] = 'Невалидный email';
        }
        if (empty(($period = self::getPeriod($fields)))) {
            $error['name'][] = 'period';
            $error['message'][] = 'Выберете год получения правки';
        }
        if (!self::isClinic($fields)) {
            $error['name'][] = 'clinics';
            $error['message'][] = 'Выберете кинику';
        }

        if (!empty($error['name'])) {
            return self::errorInput($error['name'], $error['message']);
        }

        $data = [
            'email' => $email,
            'name' => $fields['name'],
            'date_birth' => date('Y-m-d', strtotime($fields['date_birth'])),
            'phone' => $phone,
            'nalog_year' => $period,
            'inn' => trim($fields['inn']),
            'taxpayer_type_id' => $fields['taxpayer'],
            'taxpayer_fio' => trim($fields['fio_nalog']),
            'taxpayer_date_birth' => trim($fields['taxpayer_date_birth']),
            'hash' => uniqid(),
            'inn_patient' => trim($fields['inn_patient'])
        ];
        $nalogId = (new NalogModel())->create($data);
        $clinic = $fields['clinic'] ?? [];
        foreach ($clinic as $id => $v) {
            if ((int)$v === 1) {
                (new NalogClinicModel([
                    'nalog_id' => $nalogId,
                    'clinic_id' => $id,
                    'is_place' =>  0
                ], isNotExistCreate: true));
            }
        }
        try{
            $fields['id'] = $nalogId;
            self::sendMail($data['hash'], $fields);
        }catch(Exception $e){

        }
        // try {
            foreach ((new NalogClinicModel())->findM(
                    ['nalog_id' => $nalogId],
                    callback: function (Model $m) {
                        $m->select(
                            'clinic.legal_name',
                            'clinic.owner',
                            'nalog_clinic.*'
                        );
                        $m->join('clinic')->on('nalog_clinic.clinic_id = clinic.id');
                    })
                as
                $clinic
            ) {
                $pdf =  new Pdf();
                $var = [
                    'name' =>  trim($fields['fio_nalog']),
                    'mail' => $email,
                    'phone' => $phone,
                    'patient' => $fields['name'],
                    'dateB' => date('m.d.Y', strtotime(trim($fields['taxpayer_date_birth']))),
                    'inn' => trim($fields['inn']),
                    'date' =>  date('m.d.Y'),
                    'year' => $period,
                    'legal_name' => $clinic->legal_name,
                    'owner' => $clinic->owner,
                ];
                $pdf->WriteHTML(View::getTemplate('template.nalog.statement.html', $var));
                $path = View::DIR_VIEW . DS . 'uploads' . DS . 'nalog' . DS . $nalogId;
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $nameFile =  uniqid() . '.pdf';
                $file = $path . DS . $nameFile;
                $pdf->Output($file, 'F');
                (new NalogClinicFilesModel([
                    'nalog_clinic_id' => $clinic->id,
                    'url_file' => URL_WA . "/view/uploads/nalog/$nalogId/$nameFile",
                    'path' => $path,
                    'name' => $nameFile,
                    'origin' => "Заявление_" . str_replace(['/',' ', ',', '.','+'], '_' , $fields['fio_nalog']) . '.pdf',
                    'relat' =>  UPLOADS . "nalog/$nalogId/$nameFile",
                ], isNotExistCreate:true));
            }
        // } catch (Exception $e) {

        // }
        return [
            'type' => 'nalog-ok',
            'uniq' => $data['hash']
        ];
    }
    public static function isClinic($fields)
    {
        $isClinic = false;
        foreach ($fields['clinic'] as $id => $v) {
            if ((int)$v === 1) {
                $isClinic = true;
            }
        }
        return $isClinic;
    }

    public static function getPeriod($fields)
    {
        $period = [];
        foreach ($fields['year'] as $year => $isCheck) {
            if ((int)$isCheck === 1) {
                $period[] = $year;
            }
        }
        return implode(", ", $period);
    }

    public static function sendMail($uniq, $fields) {
        $fields['statusUrl'] = "https://www.altamedplus.ru/about/nalogovyy-vychet/status_new.php?hash=$uniq";
        (new Mail())->send(
            trim($fields['email']),
            $fields['fio_nalog'],
            "⚡️ Заявление на подготовку справки для получения налогового вычета принято",
            View::getTemplate('template.nalog.send_mail', $fields)
        );
    }
}
