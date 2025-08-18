<?php
namespace APP\Module\UI;
class Options
{

    public static function show($data, $selected = null):void
    {
        foreach ($data as $k => $value) : ?>
        <option value="<?=$k?>" <?=$k == $selected ? 'selected' : ''?>><?=$value?></option>
        <? endforeach;
    }
}