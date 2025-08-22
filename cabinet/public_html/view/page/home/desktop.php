<?php

use APP\Form\Form;
use Pet\Cookie\Cookie;

?>
<div class="pannel-left">
    <form name="message/send" csrf-token="<?= Form::csrf() ?>">
        <label>Отправитель</label>
        <input type="hidden" name="id" value="" />
        <select data-clinic name="clinic">
            <? foreach ($clinics as $clinic) : ?>
                <option value="<?= $clinic['id'] ?>" <?= ($clinic['id'] == Cookie::get('select_clinic_id') ? 'selected' : '') ?> data-address="<?= $clinic['address'] ?>"><?= $clinic['name'] ?></option>
            <? endforeach; ?>
        </select>
        <label>Телефон</label>
        <input type="text" name="phone" placeholder="79999999999" data-reload autocomplete="off"></input>
        <div class="dynamic" data-dynamic></div>
        <div class="button" data-button></div>
        <div data-message></div>
        <div class="flex-row-center">
            <button class="btn  btn-content-message-circle" type="submit">Отправить</button>
        </div>
    </form>
</div>
<div class="pannel-right">
    <table
        name="buttonmessanangelist"
        statusInfo="0"
        infoHeader="0"
        limitInfo="0"
        limit="50"
        pagination="0"
        countsTab="0"
        showColums="0">
        <thead>
            <tr name="column">
                <th alias=""></th>
            </tr>
            <tr name="filter">
                <th>
                    <input type="hidden" name="clinic_id" sing="=" value="<?= Cookie::get('select_clinic_id') ?: $clinics[0]['id'] ?>"></input>
                </th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div class="open-message open-deactive">
    <div class="open-content ">
        <div class="open-status">
            <label>Статус</label>
            <input type="date" name="serch-date-status" value="<?=date('Y-m-d')?>">
        </div>
    </div>
    <div class="open-items">
        
    </div>
</div>
</div>
</div>