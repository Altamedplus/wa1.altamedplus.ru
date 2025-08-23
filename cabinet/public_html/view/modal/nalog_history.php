<?

use APP\Form\Form;
use APP\Model\NalogCommentModel;
use APP\Model\NalogModel;
use Pet\Model\Model;
?>
<div class="modal-fones">
    <div class="modal">
        <button class="close-modal"></button>
        <div class="block_header">
            <h3><?= $header ?? "" ?></h3>
            <? if (!empty($headerInfo)): ?>
                <i><?= $headerInfo ?? "" ?></i>
            <? endif; ?>
        </div>
        <div class="block_body">
            <?$historyData = NalogModel::getHistory((int)$id);?>
            <?if(!empty($historyData)):?>
            <? foreach ($historyData as $d => $data): ?>
                <div class="hisroty">
                    <div class="history-name">
                        <b><?= date('d.m.y H:i', strtotime($d)) ?></b>
                        <b><?= $data['user'] ?></b>
                    </div>
                    <div class="history-data">
                        <? foreach ($data['text'] as $text): ?>
                            <p><?= $text ?></p>
                        <? endforeach ?>
                    </div>
                </div>
            <? endforeach ?>
            <?else:?>
                <br/>
                <b class="m-10">Изменения не найдены</b>
                <br/><br/>
            <?endif?>
        </div>
    </div>
</div>