<?php

namespace APP\Form\Variable;

use APP\Form\Form;
use APP\Model\VariableModel;
use APP\Module\UI\Fire;
use Pet\Request\Request;

class Add extends Form
{
    public function submit(Request $request)
    {
        $fields = attrs();
        foreach ($fields as $k => $field) {
            if (empty($field)) {
                $fields[$k] = NULL;
            }
        }
        $id = (int)$fields['id'];
        unset($fields['id']);

        if (!empty($id)) {
            $variable = new VariableModel(['id' => $id]);
            if ($variable->exist()) {
                $id = $variable->id;
                $variable->set($fields);
                return new Fire('Кнопка изменена');
            }
        }
            $id = (new VariableModel())->create($fields);
            return [
                'type' => 'redirect',
                'href' => "/variable/edit/$id"
            ];
    }
}