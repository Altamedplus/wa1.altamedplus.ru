<?php

namespace APP\Model;

use Pet\Model\Model;

class ButtonsModel extends Model
{
    protected string $table = 'buttons';

    public function default()
    {
        $this->show(' COLUMNS ');
        $filds = $this->fetch();
        $fildsDefault = [];
        foreach ($filds as $fild) {
            if (in_array($fild['Field'], ['id', 'cdate', 'update'])) {
                continue;
            }
            $fildsDefault[$fild['Field']] = $fild['Default'];
        }
        return $fildsDefault;
    }

    public function setDefault()
    {
        $this->set($this->default());
    }
}
