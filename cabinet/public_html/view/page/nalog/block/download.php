<?php

use APP\Enum\NalogStatus;
use APP\Form\Form; ?>

<div class="block-header flex-row">
    <a href="/nalog" class="btn-round-back m-10"></a>

    <div class="flex-column-center h-100">
        <p class="m-5"><?= $header ?></p>
    </div>
</div>

<div class="flex-row mb-15 p-10">
<div class="content-pdf" data-prev-pdf>
</div>
<div class="flex-column-center w-100">
    <? foreach ($files as $cliniciId => $clinic): ?>
        <h3 style="--color-clinic: <?=$clinic['clinic']->color?>;"><?= $clinic['clinic']->name ?></h3>
        <p class="m-0 fw-700">Документы: </p>
        <div class="flex-row-center">
                <p class="p-5 m-5" data-prev-url="<?=$clinic['license']->url_file?>"><?=$clinic['license']->name ?? 'Файл лицензии не найден'?></p>
                <a href="<?=$clinic['license']->url_file?>" class="btn-round-small btn-content-eye"></a>
            </div>
        <? foreach ($clinic['files'] as $fcd): ?>
            <div class="flex-row-center">
                <p class="p-5 m-5" data-prev-url="<?=$fcd->url_file?>"><?=$fcd->origin ?></p>
                <a href="<?=$fcd->url_file?>" class="btn-round-small btn-content-eye"></a>
            </div>
        <? endforeach; ?>
    <? endforeach; ?>
</div>
</div>