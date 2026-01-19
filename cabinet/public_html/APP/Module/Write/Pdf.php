<?php
namespace APP\Module\Write;

use Exception;
use Mpdf\Mpdf;
use Pet\View\View;

class Pdf extends Mpdf
{

    public function __construct($params = [])
    {
        $paramsInit = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 12,
            'default_font' => 'dejavusans', // Поддержка кириллицы
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
        ] + $params;
        parent::__construct($params);
    }
}
