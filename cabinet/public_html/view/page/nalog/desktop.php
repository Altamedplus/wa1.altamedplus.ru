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
            <tr name="filter">
                <th>
                    <div class="flex-colum">
                        <input type="text"  class="w-100" placeholder="Заявка" value="" />
                        <select name="nc.status" class="w-100">
                            <option value="">-</option>
                            <? foreach (NalogStatus::data() as $id => $status) : ?>
                                <option value="<?= $id ?>"><?= $status ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                </th>
                <th><input type="text" placeholder="79999999999" value="" style="width: 90px;" /></th>
                <th><input type="text" placeholder="ФИО пациента" name="nalog.name" value="" sign="LIKE" /></th>
                <th>
                    <div class="flex-column">
                        <input type="text" placeholder="ФИО налогоплательщика" name="nalog.taxpayer_fio" sign="LIKE" value="" />
                        <input type="text" placeholder="ИНН" name="nalog.inn" sign="LIKE" value="" />
                    </div>
                </th>
                <th>
                    <div class="row">
                        <b>C</b>
                        <input type="date" name="nalog.cdate.from" value="<?= $filter['cdate'] ?? '' ?>" />
                    </div>
                    <div class="row">
                        <b>По</b>
                        <input type="date" name="nalog.cdate.to" value="<?= $filter['cdate'] ?? '' ?>" />
                    </div>
                </th>
                <th></th>
                <th>
                    <div class="flex-row">
                        <div class="flex-colum m-10">
                            <label>По клиникам</label>
                            <select name="nc.clinic_id">
                                <option value="">-</option>
                                <? foreach ((new ClinicModel())->findM() as $clinic) : ?>
                                    <option value="<?= $clinic->id ?>"><?= $clinic->name ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>



                        <div class="flex-colum m-10">
                            <label class="">Исполнитель</label>
                            <select name="nc.user_id" style="min-width: 100px">
                                <option value="">-</option>
                                <? foreach ((new UsersModel())->findM() as $user) : ?>
                                    <option value="<?= $user->id ?>"><?= $user->name . " " . $user->surname  ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                </th>
            </tr>
            <tr name="column">
                <th alias="request">Заявка</th>
                <th alias="contact">Контакты</th>
                <th alias="data_sick">Данные пациента</th>
                <th alias="data_nalog">Данные налогоплательщика</th>
                <th alias="cdate">Дата Создания</th>
                <th alias="comment">Коментарии</th>
                <th alias="clinic">Клиники</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>