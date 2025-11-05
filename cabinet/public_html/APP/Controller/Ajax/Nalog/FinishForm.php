<?php

namespace APP\Controller\Ajax\Nalog;

use APP\Controller\AjaxController;
use APP\Module\WhatsApp;
use Pet\View\View;

class FinishForm extends AjaxController
{

    public function helper()
    {
        return [
            'html' => View::getTemplate('template.nalog.finish_form'),
        ];
    }
}