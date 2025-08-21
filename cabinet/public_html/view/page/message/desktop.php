<?php

use APP\Enum\StatusMessage;
use APP\Form\Form;
use APP\Model\ClinicModel;
use APP\Model\SampleModel;
use APP\Model\UsersModel;

 ?>
<div class="block-header flex-row">
    <p><?= $header ?></p>
</div>
<div>
    <table
        name="messagelist"
        statusInfo="1"
        infoHeader="1"
        limitInfo="1"
        limit="10"
        pagination="1"
        countsTab="1"
        colors="1"
        showColums="1"
        scrolling="1"
        >
        <thead>
            <tr name="filter">
                <th alias="id">
                    <input type="number" min="1" name="message.id" placeholder="ID" value="<?= $filter['id'] ?>" sign="=" />
                </th>
                <th alias="phone">
                    <input name="phone" placeholder="Телефон" value="<?= $filter['phone'] ?>" sign="LIKE" />
                </th>
                <th alias="cdate">
                    <div class="row">
                        <b>C</b>
                        <input type="datetime-local" name="message.cdate.from" value="<?= $filter['cdate'] ?? '' ?>" />
                    </div>
                    <div class="row">
                        <b>По</b>
                        <input type="datetime-local" name="message.cdate.to" value="<?= $filter['cdate'] ?? '' ?>" />
                    </div>
                </th>
                <th alias="send_date">
                    <div class="row">
                        <b>C</b>
                        <input type="date" name="message.send_date.from" value="<?= $filter['send_date'] ?? '' ?>" />
                    </div>
                    <div class="row">
                        <b>По</b>
                        <input type="date" name="message.send_date.to" value="<?= $filter['send_date'] ?? '' ?>" />
                    </div>
                </th>
                <th alias="send_time">
                    <div class="row">
                        <b>C</b>
                        <input type="time" name="message.send_time.from" value="<?= $filter['send_time'] ?? '' ?>" />
                    </div>
                    <div class="row">
                        <b>По</b>
                        <input type="time" name="message.send_time.to" value="<?= $filter['send_time'] ?? '' ?>" />
                    </div>
                </th>
                <th alias="status">
                    <select name="message.status">
                        <option value="">-</option>
                        <? foreach (StatusMessage::data() as $id => $name) : ?>
                            <option value="<?= $id ?>"><?= $name ?></option>
                        <? endforeach; ?>
                    </select>
                </th>
                <th alias="sample_name">
                     <select name="message.sample_id" >
                        <option value="">-</option>
                        <? foreach ((new SampleModel())->findM() as $sample) : ?>
                            <option value="<?= $sample->id ?>"><?= $sample->name ?></option>
                        <? endforeach; ?>
                    </select>
                </th>
                <th alias="request_id">
                    <input type="text" name="message.request_id" placeholder="request_id" value="" />
                </th>
                <th alias="clinic_name">
                     <select name="message.clinic_id" >
                        <option value="">-</option>
                        <? foreach ((new ClinicModel())->findM() as $clinic) : ?>
                            <option value="<?= $clinic->id ?>"><?= $clinic->name ?></option>
                        <? endforeach; ?>
                    </select>
                </th>
                <th alias="user_name">
                     <select name="message.user_id" >
                        <option value="">-</option>
                        <? foreach ((new UsersModel())->findM() as $user) : ?>
                            <option value="<?= $user->id ?>"><?=  $user->name ." ".$user->surname ?></option>
                        <? endforeach; ?>
                    </select>
                </th>
            </tr>
            <tr name="column">
                <th alias="id">ID</th>
                <th alias="phone">Телефон</th>
                <th alias="cdate">Дата создания</th>
                <th alias="send_date">Дата отправки</th>
                <th alias="send_time">Время отправки</th>
                <th alias="status">Статус Сообщения</th>
                <th alias="sample_name">Шаблон</th>
                <th alias="request_id">Присвоеный ID Edna</th>
                <th alias="clinic_name">Клиника</th>
                <th alias="user_name">Отправитель</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>