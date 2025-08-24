<?php

use APP\Form\Form; ?>
<div class="block-header flex-row">
    <a evt="back" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>
<div class="flex-row">
<div class="flex-column channel-infornation" data-channel>
</div>

<div class="flex-column-center w-100">
    <form name="edna/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?= $formInfo['id'] ?? '' ?>">
        <label>Каскад ID</label>
        <input type="text" name="cascade_id" placeholder="Каскад ID" value="<?= $formInfo['cascade_id'] ?? '' ?>" />
        <label>Ключ API</label>
        <input type="text" name="api" placeholder="Ключ API" value="<?= $formInfo['api'] ?? '' ?>" />
        <div class="flex-row-center">
            <input ui="unmasked" type="checkbox" label="Использовать" name="is_actual" <?= ($formInfo['is_actual'] ?? 0) == 1 ? 'checked' :'' ?>/>
        </div>
        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить':'Редактировать'?></button>
            <? if (!empty($formInfo)): ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="ednaModel"
                    data-redirect="/edna"
                    data-id="<?=$formInfo['id']?>"></button>
            <? endif ?>
        </div>
    </form>
</div>
</div>