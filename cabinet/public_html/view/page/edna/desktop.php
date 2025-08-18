<?php

use APP\Form\Form; ?>
<div class="block-header flex-row">
    <p><?= $header ?></p>
    <div class="flex-row-center m-10"> 
        <a class="btn btn-plus" href="/edna/add"></a>
    </div>
</div>
<div >
    <table 
        name="ednalist"
        statusInfo="1"
        infoHeader="1"
        limitInfo="1"
        limit="10"
        pagination="1"
        countsTab="1"
        colors = "1"
        showColums="1">
        <thead>
        <tr name="column">
            <th alias="id">ID</th>
            <th alias="cascade_id">Каскад</th>
            <th alias="api">Ключ Api</th>
            <th alias="is_actual">Используется</th>
            <th alias="chanal">Используется</th>
            <th style="width: 20px;"></th>
        </tr>

        </thead>
        <tbody></tbody>
    </table>
</div>