<?php

use APP\Form\Form; ?>
<div class="flex-column w-100">
    <div class="block-header">
        <p><?= $header ?></p>
    </div>
    <div class="flex-column-center w-100">
        <form name="users/Repassword" csrf-token="<?= Form::csrf() ?>">
            <input ui="password-form" label="Новый пароль" type="password" placeholder="Пароль" name="password" />
            <input ui="password-form" label="Повторить пароль" type="password" placeholder="Повторите" name="password_two" />
            <div class="flex-row">
                <button type="submit" class="btn btn-submit-blue">Изменить пароль</button>
            </div>
        </form>
    </div>
</div>