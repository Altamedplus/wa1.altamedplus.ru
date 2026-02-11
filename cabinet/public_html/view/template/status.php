<?php

use APP\Enum\TypeSend;
use APP\Form\Form;
?>
<div class="open-item open-item-active">
    <span class="open-item-phone flex-row" evt="wa-m"><?=Form::unsanitazePhone($phone)?> <span class="flex" style="width: 20px; hight: 20px;"><?=svg('type.' . mb_strtolower(TypeSend::get((int)$type_send) ?: 'WhatsApp')) ?></span></span>
    <a href="/message/?id=<?=$id?>">
    <span class="open-item-sample"><?=$name?></span>
        <div class="open-item-footer">
            <span class="open-item-time"><?=$time?></span>
            <span class="open-item-status" data-status="<?=$status?>"></span>
        </div>
    </a>
</div>