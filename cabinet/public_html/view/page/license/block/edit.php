<?php

use APP\Form\Form; ?>
<div class="block-header flex-row">
    <a href="/license" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>
<div class="flex-column-center w-100">
    <form name="license/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?= $formInfo['id'] ?? '' ?>">
        <label>Название</label>
        <input type="text" name="name" placeholder="Название" value="<?= $formInfo['name'] ?? '' ?>" />
        <label>Файл</label>
        <div class="flex-row-center w-100">
            <input type="text"  class="w-100" name="url_file" placeholder="Файл" value="<?= $formInfo['url_file'] ?? '' ?>" />
            <label class="btn btn-plus">
                <input type="file" class="d-none" evt="license_file"/>
            </label>
        </div>
         <label>Клиника</label>
        <select name="clinic_id">
            <? foreach ($clinics as $clinic) : ?>
                <option value="<?= $clinic['id'] ?>"><?= $clinic['name'] ?></option>
            <? endforeach; ?>
        </select>
        <div class="flex-row-center">
            <input ui="unmasked" type="checkbox" label="Использовать" name="is_actual" <?= ($formInfo['is_actual'] ?? 0) == 1 ? 'checked' : '' ?> />
        </div>
        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить' : 'Редактировать' ?></button>
            <? if (!empty($formInfo)): ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="licenseModel"
                    data-redirect="/license"
                    data-id="<?= $formInfo['id'] ?>"></button>
            <? endif ?>
        </div>
    </form>
</div>