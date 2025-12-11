<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;

class MaxController extends PageController
{

    public function index()
    {
         view('page.max.init', [
            "header" => "Max",
        ]);
    }
}
