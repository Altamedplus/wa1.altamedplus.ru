<?php

namespace APP\Form\Sample;

use APP\Form\Form;
use APP\Model\ButtonsModel;
use APP\Model\ButtonsSampleModel;
use APP\Model\ClinicsSampleModel;
use APP\Model\HeaderSampleModel;
use APP\Model\RoleSampleModel;
use APP\Model\SampleModel;
use APP\Module\UI\Fire;
use Pet\Request\Request;

class Add extends Form {
    public $exeptionFilds = [
        'type_users',
        'clinics',
        'buttons',
        'id',
        'header_type',
        'header_text',
        'document_name',
        'document_url',
        'img_url',
        'video_name',
        'video_url'
    ];
    public function submit(Request $request) {
        $fields = attr();
        if (!empty($fields['id'])) {
            $id = $fields['id'];
            unset($fields['id']);
            return $this->edit((int)$id, (array)$fields);
        }
        return $this->add((array)$fields);
    }
    private function edit(int $id, array $fields) {
        $sample = new SampleModel(['id' => $id]);
        if (!$sample->exist()) {
            return $this->add($fields);
        }
        foreach ($fields as $name => $field) {
            if (in_array($name, $this->exeptionFilds)) {
                ${$name} = $field;
                unset($fields[$name]);
            }
        }
        $sample->set($fields);
        $header = new HeaderSampleModel(['sample_id' => $id], true);
        if ($header->exist()) {
            $header->setDefault();
        }
        $header->set([
            'type' => $header_type,
            'text' =>  $header_text ?: null,
            'img_url' => $img_url ?: null,
            'video_url' => $video_url ?: null,
            'video_name' => $video_name ?: null,
            'document_url' => $document_url ?: null,
            'document_name' => $document_name ?: null,
            'sample_id' => $id
        ]);

        (new RoleSampleModel())->ifExistDelete(['sample_id' => $id]);
        (new ClinicsSampleModel())->ifExistDelete(['sample_id' => $id]);
        (new ButtonsSampleModel())->ifExistDelete(['sample_id' => $id]);

        foreach ($type_users as $typeId) {
            new RoleSampleModel(['sample_id' => $id, 'user_type' => $typeId], true);
        }
        foreach ($clinics as $clinicId) {
            new ClinicsSampleModel(['sample_id' => $id, 'clinic_id' => $clinicId], true);
        }
        foreach ($buttons as $buttonsId) {
            new ButtonsSampleModel(['sample_id' => $id, 'buttons_id' => $buttonsId], true);
        }
        return [
            'type' => 'redirect',
            'href' => "/sample/edit/$id"
        ];
    }

    private function add(array $fields) {
        foreach ($fields as $name => $field) {
            if (in_array($name, $this->exeptionFilds)) {
                ${$name} = $field;
                unset($fields[$name]);
            }
        }

        $id = (new SampleModel())->create($fields);
        if (empty($id)) {
            return new Fire('Что-то пошло не так', Fire::ERROR);
        }

        (new HeaderSampleModel(['sample_id' => $id]))->create(
            [
                'type' => $header_type,
                'text' =>  $header_text ?: null,
                'img_url' => $img_url ?: null,
                'video_url' => $video_url ?: null,
                'video_name' => $video_name ?: null,
                'document_url' => $document_url ?: null,
                'document_name' => $document_name ?: null,
                'sample_id' => $id
            ]
        );

        if (empty($id)) {
            return new Fire('Произошла ошибка создания шаблона', Fire::ERROR);
        }
        foreach ($type_users as $typeId) {
            new RoleSampleModel(['sample_id' => $id, 'user_type' => $typeId], true);
        }
        foreach ($clinics as $clinicId) {
            new ClinicsSampleModel(['sample_id' => $id, 'clinic_id' => $clinicId], true);
        }
        foreach ($buttons as $buttonsId) {
            new ButtonsSampleModel(['sample_id' => $id, 'buttons_id' => $buttonsId], true);
        }
        return [
            'type' => 'redirect',
            'href' => "/sample/edit/$id"
        ];

        return $fields;
    }
}
