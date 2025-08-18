<?php

namespace APP\Controller\Ajax;

use APP\Controller\AjaxController;
use APP\Module\UI\Fire;
use APP\Model\ClinicModel;

class Delete extends AjaxController
{

    public function helper()
    {
        $class = "APP\\Model\\". ucfirst(attr('table'));
        (new $class(['id'=> attr('id')]))->delete();
        return ["Успешно"];
    }
}
