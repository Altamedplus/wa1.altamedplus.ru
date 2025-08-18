<?use APP\Enum\NalogStatus as N?>
<div class='flex-column request-column'>
<p class="fw-600">#<?=$requestId?></p>
<p>Статус: <p class="tab"><?=$statusText?></p></p>
<? if (in_array($status, [N::NEW, N::WORKING])) : ?>
    <div class="flex-column">
        <p class="">Подготовить до: </p>
        <p class="fw-700"><?=$endDate?></p>
        <p class="tab-<?= ($days < 6 ? "red" : "green")?>">Осталось дн.: <?=$days?></p>
    </div>
<?endif;?>
</div>