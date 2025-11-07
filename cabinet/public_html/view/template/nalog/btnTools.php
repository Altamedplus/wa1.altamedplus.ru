<div class='flex-column-center'>
    <? $url = ($isPrint ? "/nalog/downloand/$nalog_id" : "/nalog") ?>
    <button
        type="button"
        title="История заявки"
        data-modal="history"
        data-header="История к заявки #<?= $nalog_id ?>"
        data-template="nalog_history"
        data-form=""
        data-id="<?= $nalog_id ?>"
        class='btn-round btn-content-eye m-5'><span class="tab"><?=$historyCount?></span>
    </button>
    <button
        type="button"
        data-modal="nalog"
        title="Комментарии"
        data-header="Комментарии к заявкe #<?= $nalog_id ?>"
        data-template="nalog_comment"
        data-form="nalog/Comment"
        data-id="<?= $nalog_id ?>"
        class='btn-round btn-content-message-circle m-5'><span class="tab"><?= $count ?>
    </button>
    <button title="Отправка о готовности"  evt="sendNalog" data-id="<?= $nalog_id ?>" class='btn-round  m-5 btn-content-wa <?= !$isWa ? 'btn-disabled' : '' ?>' <?= !$isWa ? 'disabled' : '' ?>></button>
    <a title="Печать всех документов" class='btn-round  m-5 btn-content-print <?= !$isPrint ? 'btn-disabled' : '' ?>' href="<?= $url ?>"></a>
</div>