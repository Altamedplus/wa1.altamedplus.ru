<?php

use APP\Form\Form;
use APP\Enum\UsersType;

?>
<div class="block-header flex-row">
    <a evt="back" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>
<div class="flex-column-center w-100">
    <form name="users/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?= $formInfo['id'] ?? '' ?>" />
        <label>Имя</label>
        <input type="text" name="name" placeholder="Имя" value="<?= $formInfo['name'] ?? '' ?>" />
        <label>Фамилия</label>
        <input type="text" name="surname" placeholder="Фамилия" value="<?= $formInfo['surname'] ?? '' ?>" />
        <label>Телефон</label>
        <input type="text" name="phone" placeholder="Телефон" value="<?= $formInfo['phone'] ?? '' ?>" data-phonemask="1"/>
        <label>Тип пользователя</label>
        <select name="type">
            <? foreach (UsersType::data() as $t => $type): ?>
                <option value="<?=$t?>"  <?=($t == $formInfo['type'] ? "selected" : "") ?>><?= $type?></option>
            <? endforeach; ?>
        </select>
        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить' : 'Редактировать' ?></button>
            <? if (!empty($formInfo)): ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="usersModel"
                    data-redirect="/users"
                    data-id="<?= $formInfo['id'] ?>"></button>
            <? endif ?>
        </div>
    </form>
</div>