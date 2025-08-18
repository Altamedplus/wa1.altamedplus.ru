<?php

namespace APP\Controller\Api\Nalog;

use APP\Model\NalogModel;
use Pet\Controller;
use Pet\View\View;
use APP\Enum\NalogStatus as NS;
use APP\Module\Tool;

class StatusCheckController extends Controller
{

    public function index()
    {
        $hash = attr('hash');
        $nalog = (new NalogModel(['hash' => $hash]));
        if (!$nalog->exist()) {
            View::open('template.nalog.status_error');
            exit;
        }

        $endDate = strtotime('+15 day ' . $nalog->get('cdate'));
        $days = ceil(($endDate - time()) / (60 * 60 * 24));
        $new = $nalog->status == NS::NEW ? 'green-st-1' : 'green-st-2' ;
        $working = $nalog->status == NS::WORKING ? 'green-st-1' : ($nalog->status == NS::READY ? 'green-st-2' : '');
        $ready = $nalog->status == NS::READY ? 'green-st-1' : '';

        $data = [
            'nalog' => $nalog,
            'endDate' => date('d.m.Y', $endDate),
            'days' => $days,
            'ready' => $ready,
            'working' => $working,
            'new' => $new,
            'name' => Tool::strHidden($nalog->taxpayer_fio)
        ];
        View::open('template.nalog.status_check', $data);
    }
}
