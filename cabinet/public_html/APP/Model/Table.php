<?php

namespace APP\Model;

use Pet\Model\Model;

interface Table
{
    /**
     * renameFilter

     * @param  string $k
     * @param  array|string $v
     * @return void
     */
    public function renameFilter(string &$k, array|string &$v):bool;
    public function getDatatable(array $filters, string $where): void;
    public function behind(array &$items): void;
}
