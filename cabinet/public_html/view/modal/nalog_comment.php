<?

use APP\Form\Form;
use APP\Model\NalogCommentModel;
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
            <div class="body-comment">
                <? foreach ((new NalogCommentModel())->findM(['nalog_id' => $id], callback: function (Model $m) {
                        $m->select(
                            'nalog_comment.*',
                            "CONCAT(users.name ,' ', users.surname) user"
                        );
                        $m->join('users')->on('users.id = nalog_comment.user_id');
                    }) as $comment
                ) : ?>
                    <div class="item-comment">
                        <div class="flex-row">
                          <span class="date"><?= date('d.m.Y H:i', strtotime($comment->cdate)) ?></span>
                          <span class="user"><?= $comment->user ?></span>
                        </div>
                        <p class="text"><?= $comment->comment ?></p>
                    </div>
                <? endforeach; ?>
            </div>
            <div class="flex-column-center">
                <form class="form-type-1" name="<?= $form_name ?>" csrf-token="<?= Form::csrf(true) ?>">
                    <input type="hidden" name="id" value="<?= $id ?>" />
                    <textarea name="comment"></textarea>
                    <button type="submit" class="btn-submit-blue">Новый</button>
                </form>
            </div>
        </div>
    </div>
</div>