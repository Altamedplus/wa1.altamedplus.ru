<?php

use APP\Form\Form; ?>
<div class="block-header flex-row">
    <p><?= $header ?></p>
    <div class="flex-row-center m-10"> 
        <a class="btn btn-plus" href="/license/add"></a>
    </div>
</div>
<div >
    <table 
        name="licenselist"
        statusInfo="1"
        infoHeader="1"
        limitInfo="1"
        limit="10"
        pagination="1"
        countsTab="1"
        showColums="1">
        <thead>
        <tr name="column">
            <th alias="id">ID</th>
            <th alias="name">Название</th>
            <th alias="url_file">Файл</th>
            <th alias="is_actual">Актуально</th>
            <th style="width: 20px;"></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>