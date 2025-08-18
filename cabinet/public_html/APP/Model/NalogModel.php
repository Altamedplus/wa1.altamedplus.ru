<?php

namespace APP\Model;

use APP\Enum\NalogStatus;
use Pet\Model\Model;

class NalogModel extends Model
{
    protected string $table = 'nalog';

    public static function checkRequestStatus(int $nalogId) {
        $nalog = new self($nalogId);
        $nalogC = (new NalogClinicModel())->findM(['nalog_id' => $nalogId]);
        if (NalogStatus::NEW == $nalog->status) {
            foreach ($nalogC as $cl) {
                if ($cl->status == NalogStatus::WORKING) {
                    $nalog->status = NalogStatus::WORKING;
                }
            }
        }

        if (in_array($nalog->status, [NalogStatus::WORKING, NalogStatus::READY])) {
            $isReady = true;
            foreach ($nalogC as $cl) {
                if ($cl->status != NalogStatus::READY) {
                    $isReady = false;
                }
            }
            $isReady ? $nalog->status = NalogStatus::READY : $nalog->status = NalogStatus::WORKING;
        }
    }
}
