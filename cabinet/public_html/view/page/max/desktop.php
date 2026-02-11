<div class="block-header flex-row">
    <p><?= $header ?></p>
</div>
<table
    name="contactlist"
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
            <th alias="phone">Телефон</th>
            <th alias="max_user_id">Макс ID</th>
            <th alias="name">Название</th>
            <th alias="step_authorization">Шаг авторизации</th>
            <th alias="cdate">Дата создания</th>
            <th style="width: 20px;"></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>