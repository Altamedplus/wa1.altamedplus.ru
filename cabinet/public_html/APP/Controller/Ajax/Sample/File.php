<?php

namespace APP\Controller\Ajax\Sample;

use APP\Controller\AjaxController;
use APP\Module\WhatsApp;
use Pet\View\View;

class File extends AjaxController
{

    public function helper()
    {
        $file = files('file');
        $ext = explode('.', $file['name'])[count(explode('.', $file['name'])) - 1];
        $name = uniqid() . '.' . $ext;
        $relat = "/uploads/sample";
        $path = View::DIR_VIEW . $relat;
        $this->saveFile($file['tmp_name'], $name, $path);
        return [
            'url' => URL_WA . "/" . UPLOADS . "sample/$name",
            'path' => $path,
            'relat_path' => UPLOADS . "sample",
            'name' => $name,
            'origin' => $file['name']
        ];
    }
}
