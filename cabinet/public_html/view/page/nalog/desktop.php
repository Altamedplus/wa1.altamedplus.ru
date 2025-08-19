<div class="block-header">
    <p><?= $header ?></p>
</div>
<div class="flex-row-center">
    <?php

    use APP\Enum\NalogStatus;
    use APP\Enum\StatusMessage;
    use APP\Form\Form;
    use APP\Model\ClinicModel;
    use APP\Model\SampleModel;
    use APP\Model\UsersModel;

    ?>
    <table
        name="naloglist"
        statusInfo="1"
        infoHeader="1"
        limitInfo="1"
        limit="10"
        pagination="1"
        countsTab="1"
        colors="1"
        showColums="1"
        scrolling="1">
        <thead>
            <? include __DIR__ . '/filter.php'?>
            <tr name="column">
                <th alias="request">Заявка</th>
                <th alias="contact">Контакты</th>
                <th alias="data_sick">Данные пациента</th>
                <th alias="data_nalog">Данные налогоплательщика</th>
                <th alias="cdate">Дата Создания</th>
                <th alias="comment">Коментарии</th>
                <th alias="tools">Действия</th>
                <th alias="clinic">Клиники</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>