<?php

namespace APP\Form\Buttons;

use APP\Form\Form;
use APP\Model\ButtonsModel;
use APP\Module\UI\Fire;
use Pet\Request\Request;

class Add extends Form
{
    public function submit(Request $request)
    {
        $exeptionfield = ['is_url_postfix'];
        $fields = attrs();
        foreach ($fields as $k => $field) {
            if (in_array($k, $exeptionfield)) continue;
            if (empty($field)) {
                $fields[$k] = NULL;
            }
        }
        $isCreate = false;

        if (!empty($fields['id'])) {
            $buttons = new ButtonsModel(['id' => (int)$fields['id']]);
            if ($buttons->exist()) {
                $id = $buttons->id;
                $buttons->setDefault();
                $buttons->set($fields);
                return new Fire('Кнопка изменена');
            }
        }
            unset($fields['id']);
            $id = (new ButtonsModel())->create($fields);
            return [
                'type' => 'redirect',
                'href' => "/buttons/edit/$id"
            ];
    }
}
