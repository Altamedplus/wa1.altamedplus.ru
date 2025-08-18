<?php

namespace APP\Form\Sample;

use APP\Form\Form;
use APP\Model\ButtonsModel;
use APP\Model\GroupSampleModel;
use APP\Module\UI\Fire;
use Pet\Request\Request;

class Groupadd extends Form
{
    public function submit(Request $request)
    {
        $fields = attrs();

        if (empty($fields['name'])) {
            return new Fire('Нет названия шаблона');
        }

        if (!empty($fields['id'])) {
            $group = new GroupSampleModel(['id' => (int)$fields['id']]);
            if ($group->exist()) {
                $group->set($fields);
                return new Fire('Группа шаблона изменена');
            }
        }
            unset($fields['id']);
            $id = (new GroupSampleModel())->create($fields);
            return [
                'type' => 'redirect',
                'href' => "/group_sample/edit/$id"
            ];
    }
}
