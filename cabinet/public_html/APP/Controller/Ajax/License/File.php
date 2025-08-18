<?php

namespace APP\Controller\Ajax\License;

use APP\Controller\AjaxController;
use APP\Module\WhatsApp;
use Pet\View\View;

class File extends AjaxController
{

    public function helper()
    {
        $file = files('file');

        $ext = explode('.', $file['name'])[count(explode('.', $file['name'])) - 1];
        $name = uniqid().'.'.$ext;
        $this->saveFile($file['tmp_name'], $name, View::DIR_VIEW."/uploads/lisence");
        return [
            'url' => URL_WA ."/". UPLOADS ."lisence/$name",
            'path' =>  View::DIR_VIEW."/uploads/lisence",
            'relat_path' => UPLOADS ."/lisence/",
            'name' => $name
        ];
    }
}
