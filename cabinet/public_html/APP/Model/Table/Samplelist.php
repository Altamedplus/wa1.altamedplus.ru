<?php
namespace APP\Model\Table;

use APP\Model\SampleModel;
use APP\Model\Table;
use APP\Model\VariableModel;

class Samplelist extends SampleModel implements Table
{
    public $st = 0;

    public function renameFilter(string &$k, array|string &$v): bool
    {
        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $this->select(
            'sample_messange_wa.*',
            'gs.name gname',
        );
         $this->join('group_sample gs')->on('gs.id = sample_messange_wa.group_type');
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
            foreach ($rows as $name => &$row) {
                if ($name == 'text') {
                    $row = $this->replaseVariable($row);
                }
                $row = $row ?: "-";
            }
        }
    }
}
