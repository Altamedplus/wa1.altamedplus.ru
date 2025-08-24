<?php

use APP\Enum\CheckNumber;
use APP\Enum\SampleType;
use APP\Enum\UsersType;
use APP\Enum\VariableType;
use APP\Form\Form; ?>
<div class="block-header flex-row">
    <a evt="back" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>

<? $TYPE = $formInfo['content_type'] ?? "TEXT"?>

<div class="flex-column-center w-100">
    <form name="variable/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?=$formInfo['id']??''?>">
        <div class="flex-column">
            <label>Тип переменной</label>
            <select name="type">
                <? foreach (VariableType::data() as $key => $name) : ?>
                    <option value="<?= $key ?>" <?=( $key ==($formInfo['type'] ?? 1)? 'selected': '' )?>><?= $name ?></option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="flex-column ">
            <label>Название</label>
            <input type="text" name="name" value="<?=$formInfo['name'] ?? ''?>">
        </div>
        <div class="flex-column ">
            <label>Уникальное значение</label>
            <input type="text" name="name_uniq" value="<?=$formInfo['name_uniq'] ?? ''?>">
        </div>
         <div class="flex-column ">
            <label>Формат Даты</label>
            <input type="text" name="format" placeholder="d.m.Y" value="<?=$formInfo['format'] ?? ''?>">
        </div>
        <div class="flex-column ">
            <label>Описание</label>
            <textarea  name="description" placeholder="Описание переменной"><?=$formInfo['description'] ?? ''?></textarea>
        </div>
        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить' : 'Редактировать' ?></button>
            <? if (!empty($formInfo)) : ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="variableModel"
                    data-redirect="/variable"
                    data-id="<?= $formInfo['id'] ?>"></button>
            <? endif ?>
        </div>
    </form>
</div>