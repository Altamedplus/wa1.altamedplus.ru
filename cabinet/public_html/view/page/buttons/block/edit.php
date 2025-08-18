<?php

use APP\Enum\ButtonType;
use APP\Form\Form; ?>
<div class="block-header flex-row">
    <a href="/buttons" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>

<? $TYPE = $formInfo['type'] ?? "URL"?>

<div class="flex-column-center w-100">
    <form name="buttons/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?=$formInfo['id']??''?>">
        <div class="flex-column">
            <label>Тип кнопки</label>
            <select name="type">
                <? foreach (ButtonType::data() as $key => $name) : ?>
                    <option value="<?= $key ?>"  <?=(($formInfo['type'] ?? "URL") == $key? 'selected':'') ?>><?= $name ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="flex-column">
            <label>Текст</label>
            <input type="text" name="text" placeholder="Название кнопки" value="<?= $formInfo['text'] ?? '' ?>" />
        </div>

        <div class="flex-column <?= ($TYPE == 'URL'? '': 'd-none')?>" data-type="URL">
            <label>URL</label>
            <input type="text" name="url" placeholder="Адрес кнопки" value="<?= $formInfo['url'] ?? '' ?>" />
        </div>

        <div class="flex-column <?= $TYPE == 'URL'?'': 'd-none'?>" data-type="URL">
            <label>Динамическая Часть URL Postfix</label>
            <input type="text" name="url_postfix" placeholder="Адрес кнопки Postfix (не обязательно)" value="<?= $formInfo['url_postfix'] ?? '' ?>" />
            <div class="flex-row-center">
                <input ui="unmasked" type="checkbox" label="Использовать Postfix" name="is_url_postfix" <?= ($formInfo['is_url_postfix'] ?? 0) == 1 ? 'checked' :'' ?>/>
            </div>
        </div>
    
        <div class="flex-column <?= ($TYPE == 'PHONE'?'': 'd-none')?>" data-type="PHONE">
            <label>Телефон</label>
            <input type="text" name="phone" placeholder="Телефон" value="<?= $formInfo['phone'] ?? '' ?>" />
        </div>

        <div class="flex-column d-none">
            <label>Имя пакета авторизованного приложения</label>
            <input type="text" name="package_name" placeholder="имя пакета" value="<?= $formInfo['package_name'] ?? '' ?>" />
        </div>

        <div class="flex-column <?= ($TYPE == 'QUICK_REPLY'?'': 'd-none')?>" data-type="QUICK_REPLY">
            <label>Быстрый ответ</label>
            <input type="text" name="payload" placeholder="Быстрый ответ" value="<?= $formInfo['payload'] ?? '' ?>" />
        </div>

         <div class="flex-column <?= ($TYPE == 'OPT' ? '': 'd-none')?>" data-type="OPT">
            <label>Тип действия</label>
            <select name="otp_type">
                <option value="" <?(($formInfo['otp_type']?? null) == null ? 'selected':'')?>>-</option>
                <option value="COPY_CODE" <?(($formInfo['otp_type']?? null) == "COPY_CODE"? 'selected':'')?>>Cкопировать код</option>
                <option value="ONE_TAP" <?(($formInfo['otp_type']?? null) == "ONE_TAP"? 'selected':'')?> >Кнопка авто заполнения</option>
            </select>
        </div>

        <div class="flex-column">
            <label>Цвет кнопки</label>
            <input type="text" name="color" placeholder="Цвет кнопки (не обязательно)" value="<?= $formInfo['color'] ?? '' ?>" />
        </div>

        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить' : 'Редактировать' ?></button>
            <? if (!empty($formInfo)) : ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="buttonsModel"
                    data-redirect="/buttons"
                    data-id="<?= $formInfo['id'] ?>"></button>
            <? endif ?>
        </div>
    </form>
</div>