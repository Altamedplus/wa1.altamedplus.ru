<?php

namespace APP\Form\Clinic;

use APP\Form\Form;
use APP\Module\UI\Fire;
use APP\Model\ClinicModel;
use Pet\Request\Request;

class Add extends Form
{
    public function submit(Request $request)
    {
        $fields = attrs();
        foreach ($fields as $k => $field) {
            if (in_array($k, ['id'])) {
                continue;
            }
            if (empty($field)) {
                return new Fire("Не все поля заполнены", Fire::ERROR);
            }
        }
        $id = $fields['id'];
        unset($fields['id']);
        if (!empty($id)) {
            return $this->edit((int)$id, (array)$fields);
        }
        return $this->add((array)$fields);
    }

    private function edit(int $id, array $fields)
    {
        $clinic = new ClinicModel(['id' => $id]);
        if (!$clinic->exist()) {
            return $this->add($fields);
        }
        $clinic->set($fields);
        return  new Fire('Успешно изменили');
    }

    private function add(array $fields)
    {
        $id = (new ClinicModel())->create($fields);
        return [
            'type' => 'redirect',
            'href' => "/clinic/edit/$id"
        ];
    }
}
