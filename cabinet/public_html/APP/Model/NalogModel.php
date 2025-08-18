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

        if (NalogStatus::WORKING == $nalog->status) {
            $isReady = true;
            foreach ($nalogC as $cl) {
                if ($cl->status != NalogStatus::READY) {
                    $isReady = false;
                }
            }
            if ($isReady) $nalog->status = NalogStatus::READY;
        }
    }
}
