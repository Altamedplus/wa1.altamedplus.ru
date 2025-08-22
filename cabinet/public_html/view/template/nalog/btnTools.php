<div class='flex-column-center'>
    <? $url = ($isPrint ? "/nalog/downloand/$nalog_id" : "/nalog") ?>
    <a class='btn-round  m-5 btn-content-print <?= !$isPrint ? 'btn-disabled' : '' ?>' href="<?= $url ?>"></a>
    <button evt="sendNalog" data-id="<?= $nalog_id ?>" class='btn-round  m-5 btn-content-wa <?= !$isWa ? 'btn-disabled' : '' ?>' <?= !$isWa ? 'disabled' : '' ?>></button>
    <button
        type="button"
        data-modal="nalog"
        data-header="Комментарии к заявки #<?= $nalog_id ?>"
        data-template="nalog_comment"
        data-form="nalog/Comment"
        data-id="<?= $nalog_id ?>"
        class='btn-round btn-content-message-circle m-5'><span class="tab"><?= $count ?></button>
</div>