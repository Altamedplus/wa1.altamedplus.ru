<?use APP\Form\Form;?>
<div class="modal-fones">
    <div class="modal">
        <div class="block_header">
            <h3><?= $header ?? "" ?></h3>
            <? if (!empty($headerInfo)): ?>
                <i><?= $headerInfo ?? "" ?></i>
            <? endif; ?>
        </div>
        <div class="block_body">
            <form name="<?= $form_name ?>" csrf-token="<?= Form::csrf(true) ?>">

            </form>
        </div>
    </div>
</div>