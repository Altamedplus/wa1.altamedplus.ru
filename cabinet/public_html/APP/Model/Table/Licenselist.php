<?php
namespace APP\Model\Table;

use APP\Model\LicenseModel;
use APP\Model\Table;

class Licenselist extends LicenseModel implements Table
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
            foreach ($rows as $col => &$row) {
                if ($col == 'is_actual') {
                    $row = $row == 1 ? 'Да': 'Нет';
                }
                if ($col == 'url_file') {
                    $row = '<div class="flex-column" style="width: 190px; height:245px"><embed  style="height:100%" src=' . $row . ' ></embed></div>';
                }
                $row = $row ?: "-";
            }
        }
    }
}
