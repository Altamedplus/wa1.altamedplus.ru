<?php
use APP\Form\Form;
?>
<div class="open-item open-item-active">
    <a href="/message/?id=<?=$id?>">
        <span class="open-item-phone"><?=Form::unsaitazePhone($phone)?></span>
        <span class="open-item-sample"><?=$name?></span>
        <div class="open-item-footer">
            <span class="open-item-time"><?=$time?></span>
            <span class="open-item-status" data-status="<?=$status?>"></span>
        </div>
    </a>
</div>