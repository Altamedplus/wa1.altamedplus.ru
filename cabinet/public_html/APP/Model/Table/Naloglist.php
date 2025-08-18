<?php

namespace APP\Model\Table;

use APP\Enum\NalogStatus;
use APP\Enum\VariableType;
use APP\Form\Form;
use APP\Model\NalogClinicModel;
use APP\Model\NalogCommentModel;
use APP\Model\NalogModel;
use APP\Model\Table;
use APP\Module\Auth;
use Pet\Model\Model;
use Pet\View\View;

class Naloglist extends NalogModel implements Table
{
    public $st = 0;
    public $date = [];
    public $dateTime = [];
    public $time = [];

    public function renameFilter(string &$k, array|string &$v): bool
    {

        if (in_array($k, ['nc.clinic_id', 'nc.user_id' , 'nalog.status']) && empty($v[0])) {
            return false;
        }

        if (in_array($k, ['nalog.cdate.from', 'nalog.cdate.to'])){
            $this->dateTime['nalog.cdate'][] =  $v . ($k == 'nalog.cdate.from' ? ' 00:00:00' : ' 23:59:59');
            return false;
        }
        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $this->select(
            'nalog.*',
            "CONCAT('# ', nalog.id) as request",
            'tl.name taxpayer_name',
            'nc.id clinic',
            "nalog.phone as contact",
            "CONCAT(nalog.name, '<br/>', DATE_FORMAT(date_birth, '%d.%m.%Y'),'<br>', 'Налоговый период: ', nalog_year) as data_sick",
            "CONCAT(taxpayer_fio, '<br/>', ' ИНН: ', inn) as data_nalog"
        );
        $this->where($where);
        $this->dateTimeFilter();
        $this->join('nalog_clinic nc')->on("nalog.id = nc.nalog_id AND nc.is_place = 1");
        $this->join('taxpayer_list tl')->on(['tl.type_id','taxpayer_type_id']);
        // file_put_contents('data.sql', $this->toString());
        $this->st = (clone $this)->select("COUNT(*) as st")->fetch(false)['st'];
        $this->groupBy('nalog.id');
        $this->orderBy('nalog.id', "DESC");
    }

    /**
     * behind
     * тут пишем как нужно обработать поля
     * @param  array $items
     * @return void
     */
    public function behind(array &$items): void
    {
        foreach ($items as $k => &$rows) {
            foreach ($rows as $name => &$row) {
                $rows['comment'] = View::getTemplate('template.nalog.btnComment', [
                    'request_id' => $rows['id'],
                    'count' => count((new NalogCommentModel())->find(['nalog_id' => $rows['id']]))
                ]);
                if ($name == 'request') {
                    $endDate = strtotime('+15 day ' . $rows['cdate']);
                    $row = View::getTemplate('template.nalog.request_column', [
                        'requestId' => $rows['id'],
                        'days' => ceil(($endDate - time()) / (60 * 60 * 24)),
                        'endDate' => date('d.m.Y', $endDate),
                        'status' => $rows['status'],
                        'statusText' => NalogStatus::get($rows['status'])
                    ]);
                }
                if ($name == 'contact') {
                    $phone = Form::unsaitazePhone($row);
                    $email = $rows['email'];
                    $row = "<div class='flex-column t-phone'>$phone<br/>$email</div>";
                }
                if ($name == 'cdate') {
                    $row = date('d.m.Y H:i:s', strtotime($row));
                }
                if ($name == 'data_sick') {
                    $row = "<div class='flex-column'>$row</div>";
                }
                if ($name == 'data_nalog') {
                    $taxpayerName = $rows['taxpayer_name'];
                    $row = "<div class='flex-column'>$row<br/>$taxpayerName</div>";
                }
                if ($name == 'clinic') {
                    $clinics = (new NalogClinicModel())->findM(['nalog_id' => $rows['id']], callback: function (Model $m) {
                        $m->select(
                            "clinic.name",
                            "nalog_clinic.is_place",
                            "nalog_clinic.status",
                            "nalog_clinic.user_id",
                            "nalog_clinic.clinic_id",
                            "CONCAT(users.name, ' ', users.surname) as uname"
                        );
                        $m->join('clinic')->on('clinic.id = nalog_clinic.clinic_id');
                        $m->join('users')->on('nalog_clinic.user_id = users.id');
                    });
                    $row = View::getTemplate('template.nalog.clinic', [
                        'clinics' => $clinics,
                        'auth_user_id' => Auth::$profile['id'],
                        'request_id' => $rows['id'],
                        'utype' => Auth::$profile['type']
                        ]);
                }
                $row = $row ?: "-";
            }
        }
    }

    private function dateTimeFilter(): void
    {
        foreach ($this->dateTime as $name => $value) {
            if (!empty($value[0]) && !empty($value[1])) {
                $time_1 = date('Y-m-d H:i:s', strtotime($value[0]));
                $time_2 = date('Y-m-d H:i:s', strtotime($value[1]));
                $this->where(" $name BETWEEN '$time_1' AND '$time_2' ");
            }
            if (!empty($value[0]) && empty($value[1])) {
                $time_1 = date('Y-m-d H:i:s', strtotime($value[0]));
                $this->where(" $name BETWEEN '$time_1' AND (SELECT MAX($name) FROM {$this->table}) ");
            }
        }

        foreach ($this->date as $name => $value) {
            if (!empty($value[0]) && !empty($value[1])) {
                $time_1 = date('Y-m-d', strtotime($value[0]));
                $time_2 = date('Y-m-d', strtotime($value[1]));
                $this->where(" $name BETWEEN '$time_1' AND '$time_2' ");
            }
            if (!empty($value[0]) && empty($value[1])) {
                $time_1 = date('Y-m-d', strtotime($value[0]));
                $this->where(" $name BETWEEN '$time_1' AND (SELECT MAX($name) FROM {$this->table}) ");
            }
        }

        foreach ($this->time as $name => $value) {
            if (!empty($value[0]) && !empty($value[1])) {
                $time_1 =  date('H:i:s', strtotime($value[0]));
                $time_2 =  date('H:i:s', strtotime($value[1]));
                $this->where(" $name BETWEEN '$time_1' AND '$time_2' ");
            }
            if (!empty($value[0]) && empty($value[1])) {
                $time_1 = date('H:i:s', strtotime($value[0]));
                $time_2 = '23:59:59';
                $this->where(" $name BETWEEN '$time_1' AND '$time_2' ");
            }
        }
    }
}
