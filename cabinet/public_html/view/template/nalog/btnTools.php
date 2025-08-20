<div clas='flex-column'>
    <? $url = ($isPrint ? "/nalog/downloand/$nalog_id" : "/nalog") ?>
    <a class='btn-round btn-content-print <?= !$isPrint ? 'btn-disabled' : '' ?>'  href="<?=$url?>"></a>
    <button evt="sendNalog" data-id="<?=$nalog_id ?>" class='btn-round btn-content-wa <?= !$isWa ? 'btn-disabled' : '' ?>'   <?= !$isWa ? 'disabled' : '' ?>></button>
</div>