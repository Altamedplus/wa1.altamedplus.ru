<?php
namespace APP\Model\Table;

use APP\Enum\StatusMessage;
use APP\Form\Form;
use APP\Model\MessageModel;
use APP\Model\Table;

class Messagelist extends MessageModel implements Table
{
    public $st = 0;
    private $dateTime = [
        'message.cdate' => []
    ];

    private $time = [
        'message.send_time' => []
    ];

    private $date = [
        'message.send_date' => []
    ];

    public function renameFilter(string &$k, array|string &$v): bool
    {
        if (in_array($k, ['message.status', 'message.sample_id', 'message.clinic_id', 'message.user_id']) && empty($v[0])) {
            return false;
        }
        if ($k == 'message.phone') {
            $v['value'] =  Form::sanitazePhone($v['value']);
            if (empty($v['value'])) return false;
        }
        if (in_array($k, ['message.cdate.from', 'message.cdate.to'])){
            $this->dateTime['message.cdate'][] = $v;
            return false;
        }

        if (in_array($k, ['message.send_date.to', 'message.send_date.from'])) {
             $this->date['message.send_date'][] = $v;
             return false;
        }

        if (in_array($k, ['message.send_time.to', 'message.send_time.from'])) {
             $this->time['message.send_time'][] = $v;
             return false;
        }

        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $this->select(
            'message.*',
            'c.name clinic_name',
            "CONCAT(u.name, ' ', u.surname ) user_name",
            'smw.name sample_name',
        );
        $this->join('clinic c')->on(['c.id', 'message.clinic_id']);
        $this->join('users u')->on(['u.id', 'message.user_id']);
        $this->join('sample_messange_wa smw')->on(['smw.id', 'message.sample_id']);
        $this->where($where);
        $this->dateTimeFilter();
        // file_put_contents('data.sql',$this->toString());
        $this->st = (clone $this)->select("COUNT(*) as st")->fetch(false)['st'];
        $this->orderBy('message.cdate', "DESC");
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
                $this->where(" $name BETWEEN '$time_1' AND (SELECT MAX($name) FROM message) ");
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
                $this->where(" $name BETWEEN '$time_1' AND (SELECT MAX($name) FROM message) ");
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
                if ($name == 'status') {
                    $row = StatusMessage::get((int)$row);
                }
                if ($name == 'cdate') {
                    $row = date('d.m.Y H:i:s', strtotime($row));
                }
                if ($name == 'send_date') {
                    $row = $row ? date('d.m.Y', strtotime($row)): '-';
                }
                if ($name == 'sample_name') {
                    $row = '<a href="/sample/edit/'.$rows['sample_id'].'" >'.$row.'</a>';
                }

                if ($name == 'data_request' && !empty($row)) {
                    $row = "<div class=\"table-message\">" . (new MessageModel($rows['id']))->getHtml() . "</div>";
                }
                $row = $row ?: "-";
            }
        }
    }
}