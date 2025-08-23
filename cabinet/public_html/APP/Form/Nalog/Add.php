<?php

namespace APP\Form\Nalog;

use APP\Enum\HistoryType;
use APP\Enum\NalogStatus;
use APP\Enum\UsersType;
use APP\Form\Form;
use APP\Model\ClinicModel;
use APP\Model\HistoryModel;
use APP\Model\NalogClinicFilesModel;
use APP\Model\NalogClinicModel;
use APP\Model\NalogModel;
use APP\Model\UsersModel;
use APP\Module\Auth;
use APP\Module\Tool;
use APP\Module\UI\Fire;
use Pet\Request\Request;

class Add extends Form
{
    public $auth = true;
    public $entity_id = null;
    public $subentity_id = null;

    public function submit(Request $request)
    {
        $nalogId = attr('id');
        $this->entity_id = $nalogId;
        $clinic_id = supple('clinic');
        $status = attr('status');
        if (Auth::$profile['type'] == UsersType::ADMIN) {
            $status = ($status == NalogStatus::NEW ? NalogStatus::WORKING : $status);
        }
        $license = Request::$attribute['licence'] ?? null;
        if (empty($license)) {
            return new Fire('Добавьте лицензию для клиники в раздел лицензий', Fire::ERROR);
        }
        $nalog = new NalogModel($nalogId);
        if (!$nalog->exist()) {
            return new Fire('Заявка не найдена', Fire::ERROR);
        }

        $history =  $nalog->getInfo();
        unset($history['update']);
        unset($history['cdate']);

        $nalogClinic = new NalogClinicModel(['nalog_id' => $nalogId, 'clinic_id' => $clinic_id]);
        $this->subentity_id = $nalogClinic->clinic_id;

        $nalogClinicFiles = (new NalogClinicFilesModel())->find(['nalog_clinic_id' => $nalogClinic->id]);
        $this->complectFile($nalogClinicFiles, $nalogClinic->id);
        $data = [
            'status' => $status,
            'user_id' => Auth::$profile['id'],
            'license_id' => $license,
        ];
        $this->historyEdit($nalogClinic, $data, 'nalog_clinic');
        $nalogClinic->set($data);

        NalogModel::checkRequestStatus((int)$nalogId);
        $nalog->reboot();
        $this->historyEdit($nalog, $history, 'nalog');
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
                $this->history(['<a href="' . $fileUrl[$i] . '">' . $fileOrigin[$i] . '</a>'], 'nalog_clinic_files.name', HistoryType::ADD);
            }

            // Удалить
            if (!in_array($item, $fileName) && in_array($item, $fileNameN)) {
                foreach ($nalogClinicFiles as $row) {
                    if ($row['name'] == $item) {
                        $filesNC = new NalogClinicFilesModel(['id' => $row['id']]);
                        $this->deleteFile($row['path'], $row['name']);
                        $filesNC->delete();
                        $this->history([' <b>' . $filesNC->origin . '</b>'], 'nalog_clinic_files.name', HistoryType::DELETE);
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

    private function history($change, $field, $type = HistoryType::EDIT)
    {
        (new HistoryModel())->create([
            'entity' => 'nalog',
            'entity_id' => $this->entity_id,
            'subentity_id' => $this->subentity_id,
            'type' => $type,
            'field' => $field,
            'new_change' => $change[0],
            'old_change' => $change[1] ?? null,
            'user_id' => Auth::$profile['id']
        ]);
    }

    private function historyEdit($model, $data, $prefix = '')
    {
        $ex = empty($prefix) ? '' : '.';
        foreach ($data as $field => $value) {
            $prefixx = $prefix . $ex . $field;
            $old = $model->{$field};
            if (empty($old) && !empty($value)) {
                $this->history([$value, $old], $prefixx, HistoryType::ADD);
            } elseif (!empty($old) && empty($value)) {
                $this->history([$value, $old], $prefixx, HistoryType::DELETE);
            } elseif ($old != $value) {
                $this->history([$value, $old], $prefixx);
            }
        }
    }
}
