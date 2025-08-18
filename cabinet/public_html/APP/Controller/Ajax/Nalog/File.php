<?php

namespace APP\Controller\Ajax\Nalog;

use APP\Controller\AjaxController;
use APP\Module\WhatsApp;
use Pet\View\View;

class File extends AjaxController
{

    public function helper()
    {
        $file = files('file');
        $requestId = attr('requestId');
        $ext = explode('.', $file['name'])[count(explode('.', $file['name'])) - 1];
        $name = uniqid() . '.' . $ext;
        $relat = "/uploads/nalog/$requestId";
        $path = View::DIR_VIEW . $relat;
        $this->saveFile($file['tmp_name'], $name, $path);
        return [
            'url' => URL_WA . "/" . UPLOADS . "nalog/$requestId/$name",
            'path' => $path,
            'relat_path' => UPLOADS . "nalog/$requestId",
            'name' => $name,
            'origin' => $file['name']
        ];
    }
}