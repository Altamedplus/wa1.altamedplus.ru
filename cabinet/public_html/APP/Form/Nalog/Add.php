<?php

namespace APP\Form\Nalog;

use APP\Form\Form;
use APP\Model\NalogClinicFilesModel;
use APP\Model\NalogClinicModel;
use APP\Model\NalogCommentModel;
use APP\Model\NalogModel;
use APP\Module\Auth;
use APP\Module\Tool;
use Pet\Model\Model;
use Pet\Request\Request;
use Pet\Tools\Tools;

class Add extends Form
{
    public $auth = true;
    public function submit(Request $request)
    {
        $nalogId = attr('id');
        $clinic_id = supple('clinic');
        $status = attr('status');

        $nalogClinic = new NalogClinicModel(['nalog_id' => $nalogId, 'clinic_id' => $clinic_id]);
        $nalogClinicFiles = (new NalogClinicFilesModel())->find(['nalog_clinic_id' => $nalogClinic->id]);
        $this->complectFile($nalogClinicFiles, $nalogClinic->id);

        $nalogClinic->set([
            'status' => $status,
            'user_id' => Auth::$profile['id'],
            'license_id' => attr('licence'),
        ]);
        NalogModel::checkRequestStatus((int)$nalogId);
        return [
            'type' => 'reload'
        ];
    }

    private function complectFile(array $nalogClinicFiles, int $nalogClinicId)
    {
        $fileName = (array)attr('file_name');
        $filePath = (array)attr('file_path');
        $fileUrl = (array)attr('file_url');
        $fileRelat = (array)attr('file_relat');
        $fileOrigin = (array)attr('file_origin');

        $fileNameN = Tool::value('name', $nalogClinicFiles);

        $merge = array_merge($fileName, $fileNameN);
        foreach ($merge as $item) {
            if (empty($item)) continue;

            // добавить
            if (in_array($item, $fileName) && !in_array($item, $fileNameN)) {
                $i = Tool::map($fileName, fn($k, $v)=> $v == $item, false);

                (new NalogClinicFilesModel())->create([
                    'nalog_clinic_id' => $nalogClinicId,
                    'path' => $filePath[$i] ?? '',
                    'url_file' => $fileUrl[$i] ?? '',
                    'name' => $item,
                    'origin' => $fileOrigin[$i] ?? '',
                    'relat' => $fileRelat[$i] ?? ''
                ]);
            }

            // Удалить
            if (!in_array($item, $fileName) && in_array($item, $fileNameN)) {
                foreach ($nalogClinicFiles as $row) {
                    if ($row['name'] == $item) {
                        (new NalogClinicFilesModel(['id' => $row['id']]))->delete();
                        $this->deleteFile($row['path'], $row['name']);
                    }
                }
            }
        }
    }

    private function deleteFile($path, $name)
    {
        $path = $path . DS . $name;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
