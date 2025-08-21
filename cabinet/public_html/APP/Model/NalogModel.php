<?php

namespace APP\Model;

use APP\Enum\NalogStatus;
use Pet\Model\Model;

class NalogModel extends Model
{
    protected string $table = 'nalog';

    public static function checkRequestStatus(int $nalogId) {
        $nalog = new self($nalogId);
        $nalogC = (new NalogClinicModel())->findM(callback: function (Model $m) use ($nalogId) {
            $m->where("no_doc = 0 or no_doc = NULL AND nalog_id = $nalogId");
        });
        if (NalogStatus::NEW == $nalog->status) {
            foreach ($nalogC as $cl) {
                if ($cl->status == NalogStatus::WORKING) {
                    $nalog->status = NalogStatus::WORKING;
                }
            }
        }

        if (in_array($nalog->status, [NalogStatus::WORKING, NalogStatus::READY, NalogStatus::ISSUED])) {
            $isReady = [];
            $isIssued = [];
            foreach ($nalogC as $cl) {
                if ($cl->status == NalogStatus::READY || $cl->status == NalogStatus::ISSUED) {
                    $isReady[] = 1;
                }
                if ($cl->status == NalogStatus::ISSUED) {
                    $isIssued[] = 1;
                }
            }
            !empty($isReady) && count($isReady) == count($nalogC) ? $nalog->status = NalogStatus::READY : $nalog->status = NalogStatus::WORKING;
            if (!empty($isIssued) && count($isIssued) == count($nalogC)) {
                $nalog->status = NalogStatus::ISSUED;
            }
        }
    }
}
