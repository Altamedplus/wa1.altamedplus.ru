<?php

use Pet\Cookie\Cookie;
?>
<div class="block-header flex-row">
    <p><?= $header ?></p>
</div>
<div>
    <div class="flex-row">
       <input ui="unmasked" type="checkbox" label="Чистить буфер после отправки сообщения" name="clearBuf" <?= Cookie::get('clear-buf') ? 'checked' : ''?>/>
    </div>
</div>