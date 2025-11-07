<?php

namespace APP\Model;

use APP\Enum\NalogStatus;
use APP\Module\HistoryFields;
use Pet\Model\Model;

class NalogModel extends Model
{
    protected string $table = 'nalog';

    public static function checkRequestStatus(int $nalogId) {
        $nalog = new self($nalogId);
        $nalogC = (new NalogClinicModel())->findM(callback: function (Model $m) use ($nalogId) {
            $m->where("nalog_id = $nalogId");
        });
        foreach ($nalogC as $cl) {
            if (in_array($cl->status, [NalogStatus::WORKING, NalogStatus::READY, NalogStatus::ISSUED])) {
                $nalog->status = NalogStatus::WORKING;
            }
        }

        $isReady = [];
        $isIssued = [];
        $isNew = [];
        foreach ($nalogC as $cl) {
            if ($cl->status == NalogStatus::READY || $cl->status == NalogStatus::ISSUED) {
                $isReady[] = 1;
            }
            if ($cl->status == NalogStatus::ISSUED) {
                $isIssued[] = 1;
            }
            if ($cl->status == NalogStatus::NEW) {
                $isNew[] = 1;
            }
        }

        !empty($isReady) && count($isReady) == count($nalogC) ? $nalog->status = NalogStatus::READY
            : (!empty($isNew) && count($isNew) == count($nalogC) ? $nalog->status = NalogStatus::NEW :
                $nalog->status = NalogStatus::WORKING);
        if (!empty($isIssued) && count($isIssued) == count($nalogC)) {
            $nalog->status = NalogStatus::ISSUED;
        }
    }

    public static function getHistory(int $nalogId) {
        $data = [];
        $history = (new HistoryModel())->findM([
            'entity' => 'nalog',
            'entity_id' => $nalogId
        ], callback: function (Model $m) {
            $m->select(
                'history.*',
                "CONCAT(users.name, ' ' , users.surname) user_name",
            );
            $m->join('users')->on('history.user_id = users.id');
        });
        foreach ($history as $i => $h) {
            if (!isset($data[$h->cdate])) $data[$h->cdate] = [
                'user' => $h->user_name,
                'text' => []
            ];
            $data[$h->cdate]['text'][] = (new HistoryFields($h))->get();
        }
        return $data;
    }
}
