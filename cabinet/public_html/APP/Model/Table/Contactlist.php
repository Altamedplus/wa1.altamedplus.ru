<?php
namespace APP\Model\Table;

use APP\Form\Form;
use APP\Model\Contact;
use APP\Model\Table;

class Contactlist extends Contact implements Table
{
    public $st = 0;

    public function renameFilter(string &$k, array|string &$v): bool
    {
        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $this->select(
            "COALESCE(GROUP_CONCAT(DISTINCT id ORDER BY id SEPARATOR ' / '),'') as id",
            'phone',
            'COALESCE(MAX(max_user_id), MIN(max_user_id)) as max_user_id',
            'COALESCE(MAX(step_authorization), MIN(step_authorization)) as step_authorization',
            "COALESCE(GROUP_CONCAT(DISTINCT name ORDER BY name SEPARATOR '/'), '') as name",
            "MAX(`update`) as `update`",
            "COALESCE(MAX(code), MIN(code)) as code",
            "COALESCE(MAX(tg_user_id), MIN(tg_user_id)) as tg_user_id",
            "COALESCE(MAX(tg_step_auth), MIN(tg_step_auth)) as tg_step_auth",
            "MIN(cdate) as cdate"
        );
        $this->groupBy('phone');
        $this->st = (clone $this)->select("COUNT(*) as st")->fetch(false)['st'];
    }

    /**
     * behind
     * тут пишем как нужно обработать поля
     * @param  array $items
     * @return void
     */
    public function behind(array &$items): void
    {   foreach ($items as $k => &$rows) {
            $rows['name'] = $rows['name'] ?: 'Не определенный';
            $rows['phone'] = empty($rows['phone']) ? '-' : Form::unsanitazePhone($rows['phone']);
            $rows['cdate'] = date('d.m.Y H:i', strtotime($rows['cdate']));
        }
    }
}
