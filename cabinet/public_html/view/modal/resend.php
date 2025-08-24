<?

use APP\Form\Form; ?>
<div class="modal-fones">
    <div class="modal">
        <button class="close-modal"></button>
        <div class="block_header">
            <h3><?= $header ?? "" ?></h3>
        </div>
        <div class="block_body">
            <div class="flex-row-center" >
                <p><?=$content?></p>
            </div>
            <div class="flex-row-center">
                <button class="btn-submit-blue" evt="resend"><b>Отправить</b></button>
                <button class="btn-submit-blue btn-disabled" evt="noResed"><b>Не отправлять</b></button>
            </div>
        </div>
    </div>
</div>