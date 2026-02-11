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
    {   foreach ($items as $k => &$rows) {
            $rows['name'] = $rows['name'] ?: 'Не определенный';
            $rows['phone'] = Form::unsanitazePhone($rows['phone']);
        }
    }
}
