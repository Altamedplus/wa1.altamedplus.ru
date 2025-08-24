<?php

use APP\Enum\ButtonType;
use APP\Form\Form; ?>
<div class="block-header flex-row">
    <a evt="back" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>

<div class="flex-column-center w-100">
    <form name="sample/groupAdd" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?=$formInfo['id']??''?>">
        
        <div class="flex-column">
            <label>Название группы</label>
            <input type="text" name="name" placeholder="Название" value="<?= $formInfo['name'] ?? '' ?>" />
        </div>

        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить' : 'Редактировать' ?></button>
            <? if (!empty($formInfo)) : ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="groupSampleModel"
                    data-redirect="/group_sample"
                    data-id="<?= $formInfo['id'] ?>"></button>
            <? endif ?>
        </div>
    </form>
</div>