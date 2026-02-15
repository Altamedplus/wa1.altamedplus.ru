<?php

namespace APP\Controller\Ajax\Home\Get;

use APP\Controller\AjaxController;
use APP\Enum\TypeAutorization;
use APP\Form\Form;
use APP\Model\Contact;
use APP\Module\UI\UI;
use Pet\Model\Model;

class ButSubmit extends AjaxController
{
    public function helper()
    {
        $data = attr();
        $phone = Form::sanitazePhone($data['phone'] ?? '');
        if (!Form::validatePhone($phone)) {
            return [];
        }

        $contacts = (new Contact())->findM(['phone' => $phone]);

        $result = [
            'but' => [],
        ];
        foreach ($contacts as $contact) {
            if ($contact->get('step_authorization') == TypeAutorization::AUTORIZATION) {
                $result['but'][] = UI::showStr([
                    'tag' => 'input',
                    'type' => 'submit',
                    'class' => 'btn  btn-content-max',
                    'name' => 'max',
                    'data-but-send' => '1',
                    'value' => 'Отправить в MAX',
                    'textContent' =>  "Отправить в MAX",
                ]);
            }

            if ($contact->get('tg_step_auth') == TypeAutorization::AUTORIZATION) {
                $result['but'][] = UI::showStr([
                    'tag' => 'input',
                    'type' => 'submit',
                    'class' => 'btn  btn-content-tg',
                    'name' => 'tg',
                    'data-but-send' => '1',
                    'value' => 'Отправить в Телеграмм',
                    'textContent' =>  "Отправить в Телеграмм",
                ]);
            }
        }

        return $result;
    }
}
