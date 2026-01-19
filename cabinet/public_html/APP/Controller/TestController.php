<?php

namespace APP\Controller;

use APP\Module\Write\Pdf;
use Pet\Controller;
use Pet\View\View;

class TestController extends Controller
{
    public function index() {
        $pdf =  new Pdf();
        $var = [
            'name' => 'Иванов Иван Ивановичь',
            'phone' => 79775956853,
            'patient' => 'Иванов Иван Иванович',
            'dateB' => date('m.d.Y'),
            'inn' => 94488394394893,
            'date' =>  date('m.d.Y'),
            'year' => '2013, 2014'
        ];
        $pdf->WriteHTML(View::getTemplate('template.nalog.statement.html', $var));
        $pdf->Output('document.pdf', 'I');
        // return [];
    }
}