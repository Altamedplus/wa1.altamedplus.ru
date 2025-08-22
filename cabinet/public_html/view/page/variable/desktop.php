<?php

use APP\Form\Form; ?>
<div class="block-header flex-row">
    <p><?= $header ?></p>
    <div class="flex-row-center m-10"> 
        <a class="btn btn-plus" href="/variable/add"></a>
    </div>
</div>
<div >
    <table 
        name="variablelist"
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
            <th alias="name">Имя</th>
            <th alias="name_uniq">Уникальное Имя</th>
            <th alias="type">Тип переменной</th>
            <th alias="description">Описание</th>
            <th alias="cdate">Дата Создания</th>
            <th></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>