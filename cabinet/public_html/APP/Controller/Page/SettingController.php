<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;

class SettingController extends PageController {
    public function index()
    {
           view('page.setting.init', [
                "header" => "Настройки",
                "headerButtons" => []
            ]
        );
    }
}