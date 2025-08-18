<div class="flex-column">
    <?

use APP\Enum\NalogStatus;

 foreach ($clinics as $clinic):?>
    <div class="clinic-row">
        <p class="<?=$clinic->is_place == 1 ? 'fa fa-star' : ''?>"><span class="clinic-row-name"><?=$clinic->name?></span></p>
        <p class="tab"><?=NalogStatus::get($clinic->status)?></p>
        <p><?=$clinic->uname ?? 'Не назначен' ?></p>
        <? if (empty($clinic->uname) || $auth_user_id == $clinic->user_id) : ?>
            <a href="/nalog/edit/<?=$request_id?>/<?=$clinic->clinic_id?>" type="button" class="btn-round btn-content-edit"></a>
        <? endif;?>
    </div>
    <? endforeach;?>
</div>