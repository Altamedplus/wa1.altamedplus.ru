<?php
namespace APP\Model\Table;

use APP\Enum\UsersType;
use APP\Model\Table;
use APP\Model\UsersModel;

class Userslist extends UsersModel implements Table
{
    public $st = 0;

    public function renameFilter(string &$k, array|string &$v): bool
    {
        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $this->select(
            'id',
            'name',
            'surname',
            'phone',
            'type',
            'cdate',
        );
        $this->st = (clone $this)->select("COUNT(*) as st")->fetch(false)['st'];
    }

    /**
     * behind
     * тут пишем как нужно обработать поля
     * @param  array $items
     * @return void
     */
    public function behind(array &$items): void
    {
        foreach ($items as $k => &$rows)
            {
            foreach ($rows as $n => &$row) {
                switch ($n) {
                    case 'type':
                        $row = UsersType::get($row) ?? '-';
                        break;
                    default:
                        $row = $row ?: "-";
                }
            }
        }
    }
}