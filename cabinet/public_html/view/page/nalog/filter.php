<? use APP\Enum\NalogStatus;?>
<? use APP\Model\ClinicModel;?>
<? use APP\Model\UsersModel;?>

<tr name="filter">
    <th>
        <div class="flex-colum">
            <input type="text" class="w-100" placeholder="Заявка" value="" />
            <select name="nalog.status" class="w-100">
                <option value="">-</option>
                <? foreach (NalogStatus::data() as $id => $status) : ?>
                    <option value="<?= $id ?>"><?= $status ?></option>
                <? endforeach; ?>
            </select>
        </div>
    </th>
    <th style="max-width: 160px;"><input type="text"  name="nalog.phone" placeholder="Контакты" value="" style="width: 160px;" /></th>
    <th style="max-width: 220px;"><input type="text" placeholder="ФИО пациента" name="nalog.name" value="" sign="LIKE" /></th>
    <th style="max-width: 220px;">
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