<?php

use APP\Form\Form; ?>

<div class="block-header flex-row">
    <a evt="back" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>
<div class="flex-column-center w-100">
    <form name="clinic/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?= $formInfo['id'] ?? '' ?>">
        <label>Название</label>
        <input type="text" name="name" placeholder="Название клиники" value="<?= $formInfo['name'] ?? '' ?>" />
        <label>Адрес клиники</label>
        <input type="text" name="address" placeholder="Адрес клиники" value="<?= $formInfo['address'] ?? '' ?>" />
        <label>Латинское Название</label>
        <input type="text" name="alias" placeholder="Латинское название" value="<?= $formInfo['alias'] ?? '' ?>" />
        <label>Цвет</label>
        <input type="text" name="color" placeholder="Цвет клиники" value="<?= $formInfo['color'] ?? '' ?>" />
        <label>Битрикс ID</label>
        <input type="text" name="bitrix_id" placeholder="Битрикс ID" value="<?= $formInfo['bitrix_id'] ?? '' ?>" />
        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить':'Редактировать'?></button>
            <? if (!empty($formInfo)): ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="clinicModel"
                    data-redirect="/clinic"
                    data-id="<?=$formInfo['id']?>"></button>
            <? endif ?>
        </div>
    </form>
</div>