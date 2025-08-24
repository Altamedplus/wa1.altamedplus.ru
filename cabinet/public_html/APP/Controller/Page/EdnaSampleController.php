<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Auth;
use APP\Model\EdnaModel;
use APP\Model\ClinicModel;
use APP\Module\WhatsApp;
use Pet\Request\Request;

class EdnaSampleController extends PageController
{

    public function index(Request $request)
    {
        $wa = new WhatsApp();
        $response = $wa->getChanalProfile();
        $subjectId = $response['response'][0]['subjectId'];
        $response2 =  $wa->getSample([
            'subjectId' => $subjectId,
            'matcherTypes' => ["USER"]
        ]);

        view('page.edna_sample.init', [
            "header" => "Edna Шаблоны",
            "data" => Auth::$profile,
            "sample" => $response2['response']
        ]);
    }
}