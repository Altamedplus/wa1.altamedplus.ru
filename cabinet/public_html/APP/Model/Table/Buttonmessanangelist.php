<?php
namespace APP\Model\Table;

use APP\Model\SampleModel;
use APP\Model\Table;
use APP\Model\VariableModel;
use APP\Module\Auth;

class Buttonmessanangelist extends SampleModel implements Table
{
    public $st = 0;

    public function renameFilter(string &$k, array|string &$v): bool
    {
        return true;
    }

    public function getDatatable(array $filters, string $where): void
    {
        $clinicId = $filters['search']['clinic_id'] ?? null;
        $this->select(
            'sample_messange_wa.id',
            'sample_messange_wa.name',
            'sample_messange_wa.comment',
            'group_type',
            'gs.name gname'
        );

        $this->join('group_sample gs')->on('gs.id = sample_messange_wa.group_type');
        $this->join('role')->on('role.sample_id = sample_messange_wa.id');
        $this->join('clinics_sample cs')->on('cs.sample_id = sample_messange_wa.id');
        if (!empty($clinicId)) {
            $this->where("cs.clinic_id = $clinicId");
        }
        $typeUser = Auth::$profile['type'];
        $this->where("role.user_type = $typeUser");
        $this->groupBy('sample_messange_wa.id');
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
                $row = $row ?: "-";
            }
        }
    }

}