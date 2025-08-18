<?php
namespace APP\Model\Table;

use APP\Model\ClinicModel;
use APP\Model\Table;

class Cliniclist extends ClinicModel implements Table
{
    public $st = 0;

    public function renameFilter(string &$k, array|string &$v): bool
    {
        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $this->select('*');
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
        foreach ($items as $k => &$rows) {
            foreach ($rows as &$row) {
                $row = $row ?: "-";
            }
        }
    }
}