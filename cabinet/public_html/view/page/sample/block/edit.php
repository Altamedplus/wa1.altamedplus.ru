<?php

use APP\Enum\CheckNumber;
use APP\Enum\HeaderType;
use APP\Enum\SampleType;
use APP\Enum\UsersType;
use APP\Enum\VariableReserveType;
use APP\Form\Form; ?>
<div class="block-header flex-row">
    <a href="/sample" class="btn-round-back m-10"></a>
    <p><?= $header ?></p>
</div>

<? $TYPE = $formInfo['content_type'] ?? "TEXT" ?>

<div class="flex-column-center w-100">
    <form name="sample/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?= $formInfo['id'] ?? '' ?>">

        <div class="flex-column">
            <label>Название кнопки шаблона</label>
            <input type="text" name="name" placeholder="Название" value="<?= $formInfo['name'] ?? '' ?>">
        </div>

        <div class="flex-column">
            <label>Тип сообщения</label>
            <select name="content_type">
                <? foreach (SampleType::data() as $key => $name) : ?>
                    <option value="<?= $key ?>" <?= (($formInfo['content_type'] ?? "TEXT") == $key ? 'selected' : '') ?>><?= $name ?></option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="flex-column">
            <label>Тип Заголовка</label>
            <select name="header_type">
                <?$TYPE_HEADER = $headerSample?->type ?? "TEXT" ?>
                <? foreach (HeaderType::data() as $key => $name) : ?>
                    <option value="<?= $key ?>" <?=($TYPE_HEADER == $key ? 'selected' : '')?>><?= $name ?></option>
                <? endforeach; ?>
            </select>
            <div class="flex-column <?=($TYPE_HEADER == "TEXT" ? '': 'd-none')?>"  data-header-type="TEXT">
                <input type="text" name="header_text" placeholder="Текст Заголовка" value="<?=$headerSample?->text ?>"></input>
            </div>
            <div class="flex-column <?=($TYPE_HEADER == "DOCUMENT" ? '': 'd-none')?>" data-header-type="DOCUMENT">
                <input type="text" name="document_name" placeholder="Название документа" value="<?=$headerSample?->document_name?>"></input>
                <input type="text" name="document_url" placeholder="Url документа" value="<?=$headerSample?->document_url?>"></input>
            </div>
            <div class="flex-column <?=($TYPE_HEADER == "IMAGE" ? '': 'd-none')?>" data-header-type="IMAGE">
                <input type="text" name="img_url" placeholder="Url фото" value="<?=$headerSample?->img_url?>"></input>
            </div>
             <div class="flex-column <?=($TYPE_HEADER == "VIDEO" ? '': 'd-none')?>" data-header-type="VIDEO">
                <input type="text" name="video_name" placeholder="Название видео"  value="<?=$headerSample?->video_name?>"></input>
                <input type="text" name="video_url" placeholder="Url video" value="<?=$headerSample?->video_url?>"></input>
            </div>
        </div>

        <div class="flex-column ">
            <label>Группа Шаблона</label>
            <select name="group_type">
                <? $gType = $formInfo['group_type'] ?? null ?>
                <? foreach ($groupSample as  $gsample) : ?>
                    <option value="<?= $gsample['id'] ?>" <?= ($gType == $gsample['id'] ? "selected" : "") ?>><?= $gsample['name'] ?></option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="flex-column">
            <label>Тип Пользователя</label>
            <select name="type_users[]" multiple>
                <? foreach (UsersType::data() as $key => $name) : ?>
                    <option value="<?= $key ?>" <?= (in_array($key, $roleSample) ? 'selected' : '') ?>>
                        <?= $name ?>
                    </option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="flex-column">
            <label>Тип Клиники</label>
            <select name="clinics[]" multiple>
                <? foreach ($clinics as  $clinic) : ?>
                    <option value="<?= $clinic['id'] ?>" <?= (in_array($clinic['id'], $clinicsSample) ? 'selected' : '') ?>>
                        <?= $clinic['name'] ?>
                    </option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="flex-column ">
            <label>Тип Кнопки</label>
            <select name="buttons[]" multiple>
                <? foreach ($buttons as  $button) : ?>
                    <option value="<?= $button['id'] ?>" <?= (in_array($button['id'], $buttonsSample) ? 'selected' : '') ?>><?= $button['text'] ?></option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="flex-column ">
            <label>Текст шаблона</label>
            <div class="flex-row">
                <select class="select-small" data-variable>
                    <? foreach ($variables as  $variable) : ?>
                        <option value="<?= $variable['name_uniq'] ?>"><?= $variable['name'] ?></option>
                    <? endforeach; ?>
                    <? foreach (VariableReserveType::data() as  $uniq => $name) : ?>
                        <option value="<?= $uniq ?>"><?= $name ?></option>
                    <? endforeach; ?>
                </select>
                <button type="button" class="btn btn-plus" data-variable-add></button>
            </div>
            <textarea name="text" placeholder="Текст шаблона"><?= ($formInfo['text'] ?? '') ?></textarea>
        </div>

        <div class="flex-column ">
            <label>Комментарий</label>
            <textarea name="comment" placeholder="Коментарий к шаблону"><?=$formInfo['comment']?></textarea>
        </div>
        <div class="flex-column ">
            <label>Подпись</label>
            <input type="hidden" name="footer_type" value="TEXT">
            <input type="text" name="footer" value="<?= $formInfo['footer'] ?? '' ?>">
        </div>
        <div class="flex-column ">
            <label>Параметры </label>
            <select name="check_number">
                <? foreach (CheckNumber::data() as $id => $name) : ?>
                    <option value="<?= $id ?>" <?= $id == $formInfo['check_number'] ? 'selected' : ''?>><?= $name ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="w-100 flex-row-center">
            <button class="btn btn-submit-blue" type="submit"><?= empty($formInfo) ? 'Добавить' : 'Редактировать' ?></button>
            <? if (!empty($formInfo)) : ?>
                <button class="btn-round btn-content-trash"
                    type="button"
                    evt="delete"
                    data-table="sampleModel"
                    data-redirect="/sample"
                    data-id="<?= $formInfo['id'] ?>"></button>
            <? endif ?>
        </div>
    </form>
</div>