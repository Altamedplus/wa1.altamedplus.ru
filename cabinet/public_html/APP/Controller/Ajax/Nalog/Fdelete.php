<?php

namespace APP\Controller\Ajax\Nalog;

use APP\Controller\AjaxController;
use APP\Module\WhatsApp;
use Pet\View\View;

class Fdelete extends AjaxController
{
    public function helper()
    {
        $file = attr('path');
        $name = attr('name');
        $file .= DS  . $name;
        if (file_exists($file)) {
            unlink($file);
        }
        return ['ok'];
    }
}
