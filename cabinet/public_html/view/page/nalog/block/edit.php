<?php

use APP\Enum\NalogStatus;
use APP\Form\Form;
use APP\Module\Tool;

 ?>
<div class="block-header flex-row">
    <a evt="back" class="btn-round-back m-10"></a>

    <div class="flex-column">
        <p class="m-5"><?= $header ?></p>
        <i class="m-5 fs-12"><?= $headerClinic ?></i>
    </div>
</div>
<div class="flex-column-center w-100">
    <form name="nalog/Add" class="form-type-1" csrf-token="<?= Form::csrf() ?>">
        <input type="hidden" name="id" value="<?= $id ?>" />
        <label>Статус </label>
        <select name="status">
            <? foreach (NalogStatus::data() as $id => $status) : ?>
                <option value="<?= $id ?>" <?=($id == $nalogClinic->status ? "selected" : "" )?>><?= $status ?></option>
            <? endforeach; ?>
        </select>
        <label>Лицензия</label>
        <select name="licence">
            <? foreach ($licenses as $license): ?>
                <option value="<?= $license->id ?>"><?= $license->name ?></option>
            <? endforeach; ?>
        </select>
        <div class="document-contaner">
            <div class="document-block">
                <? foreach ($files as $file) : ?>
                <div class="document-item">
                    <div class="flex-column">
                        <label name="file-label" title="Документ:" ><?=$file->origin ?? ''?></label>
                        <input type="hidden" data-path name="file_path[]" value="<?=$file->path ?? ''?>"></input>
                        <input type="hidden" data-origin name="file_origin[]" value="<?=$file->origin ?? ''?>"></input>
                        <input type="hidden" data-name name="file_name[]" value="<?=$file->name ?? ''?>"></input>
                        <input type="hidden" data-relat name="file_relat[]" value="<?=$file->relat ?? ''?>"></input>
                        <input type="hidden" data-url name="file_url[]" value="<?= Tool::urlSanitaze($file->url_file) ?? ''?>"></input>
                    </div>
                    <label class="btn btn-plus-small">
                        <input class="d-none" type="file" evt="add-file" />
                    </label>
                    <button type="button" class="btn-round-small btn-content-trash" evt="delete-field"></button>
                    <button type="button" class="btn-round-small btn-content-eye" evt="eye-field"></button>
                </div>
                <? endforeach; ?>
            </div>
            <div class="flex-row-center document-btn">
                <button type="button" class="btn btn-plus" evt="add-field"></button>
            </div>
        </div>
        <div class="flex-row-center m-10">
            <button type="submit" class="btn btn-submit-blue">Сохранить</button>
        </div>
    </form>
</div>