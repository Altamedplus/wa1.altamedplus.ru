<?php

namespace APP\Model\Table;

use APP\Module\Auth;
use Model\ModelInterface\ModelInterface;
use Model\Table;
use Pet\Errors\AppException;
use Pet\Model\Model;
use Pet\Request\Request;
use Pet\Router\Response;
use Pet\Tools\Tools;


class Datatable
{
    public static $action = "datatable";
    public Table|Model $model;
    protected $namespace = "APP\\Model\\Table\\";
    public $result = [
        "item" => [],
        "pages" => [
            'all' => 0,
        ],
    ];

    /**
     * init
     *
     * @param  Request $request
     * @return array
     */
    final public function init(Request $request):array
    {
        $nameTable = str_replace(".", "\\", $request->header[self::$action]);
        $nameTable = ucfirst($nameTable);
        $nameClass = $this->namespace.$nameTable;

        if (!class_exists($nameClass)) {
            throw new AppException("Нет такого класса таблицы $nameClass", E_ERROR);
        }

        $this->model = new $nameClass();
        $this->datatables(json_decode(attr('table'), true));
        $this->model->behind($this->result['item']);
        Response::set(Response::TYPE_JSON);

        return $this->result;
    }


    /**
     * datatables
     *
     * @param array $filter
     * @return void
     */
    public function datatables(array $filter): void
    {
        $pages = (object)$filter['pages'];
        $renameSearch = [];

        foreach ($filter['search'] as $k => $v) {
            if (!$this->model->renameFilter($k, $v, $filter['search'])) {
                continue;
            }
            $renameSearch[$k] = $v;
        }

        $filter['search'] = $renameSearch;
        $where = self::separateFilter($filter);

        $this->result['item'] = $this->model->find(callback: function (Model $m) use ($filter, $where, $pages) {
            $this->model->getDatatable($filter, $where);
            $countAll = $this->model->st ?? (clone $m)->select("COUNT(*) as st")->fetch(false)['st'];
            $this->result['pages']['all'] =  $countAll ?? 0;
            $m->limit($pages->limit ?? 10);
            $offset = (($pages->count - 1) * ($pages->limit ?? 0)) ?: 0;
            if (!empty($offset)) {
                $m->offset($offset);
            }
        });
    }

    /**
     * separateFilter
     *
     * @param  array $filter
     * @param  string $separate
     * @return string
     */
    public static function separateFilter(array $filter, string $separate = " AND "): string
    {
        if (!key_exists("search", $filter)) {
            return "";
        }

        return implode(" $separate ", Tools::filter($filter['search'], function ($k, $v) {
            if (gettype($v) == 'array' && key_exists('sign', $v)) {
                $value = $v['value'];
                $value =  $v['sign'] == "LIKE" ? " '%$value%' " : " '$value' ";
                return $k . " " . $v['sign'] . $value;
            }

            if (gettype($v) == 'array') {
                return $k . " IN (" . implode(", ", $v) . ")";
            }
            return $k . " = '$v'";
        }));
    }
}
