<?php

use APP\Form\Form; ?>
<div class="block-header flex-row">
    <p><?= $header ?></p>
    <div class="flex-row-center m-10"> 
        <a class="btn btn-plus" href="/sample/add"></a>
    </div>
</div>
<div >
    <table 
        name="samplelist"
        statusInfo="1"
        infoHeader="1"
        limitInfo="1"
        limit="10"
        pagination="1"
        countsTab="1"
        showColums="1"
        is-save-setting ="1"
        >
        <thead>
        <tr name="column">
            <th alias="id">ID</th>
            <th alias="name">Имя шаблона</th>
            <th alias="content_type">Тип сообщения</th>
            <th alias="gname">Группа</th>
            <th alias="text">Текст сообщения</th>
            <th alias="comment">Комментарий</th>
            <th alias="footer">Подпись</th>
            <th style="width: 20px;"></th>
        </tr>

        </thead>
        <tbody></tbody>
    </table>
</div>