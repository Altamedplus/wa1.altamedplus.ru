<?

use APP\Enum\NalogStatus;
use APP\Enum\UsersType;

?>

<div class="clinic-column">
    <? foreach ($clinics as $clinic): ?>
        <div class="clinic-row">
            <p class=""><span class="clinic-row-name"><?= $clinic->name ?></span></p>
            <? if ($clinic->no_doc == 1): ?>
                <p>Только получение</p>
            <? else: ?>
                <p class="tab"><?= NalogStatus::get($clinic->status) ?></p>
                <p><?= $clinic->uname ?? 'Не назначен' ?></p>
                <? if (
                    $auth_user_id == $clinic->user_id ||
                    NalogStatus::NEW == $clinic->status ||
                    in_array($utype, [UsersType::SENIOR_ADMIN, UsersType::SYSADMIN])
                ) : ?>
                    <a href="/nalog/edit/<?= $request_id ?>/<?= $clinic->clinic_id ?>" type="button" class="btn-round btn-content-edit"></a>
                <? endif; ?>
            <? endif ?>
        </div>
    <? endforeach; ?>
</div>