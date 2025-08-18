<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use Pet\Controller;
use Pet\Request\Request;
use Pet\View\View;

class NotFound extends PageController
{
    public static function code404($param = []):void
    {
        view("page.404.init", $param += ['desktop' => View::gp(".404.desktop")]);
        exit;
    }
}