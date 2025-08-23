<?php

namespace APP\Module;

use APP\Enum\HistoryType;
use APP\Enum\NalogStatus;
use APP\Model\ClinicModel;
use APP\Model\HistoryModel;
use APP\Model\LicenseModel;
use APP\Model\UsersModel;
use Pet\Cookie\Cookie;
use Pet\Model\Model;
use Pet\Router\Response;

class HistoryFields
{
    public HistoryModel|null $h = null;
    private int $type;

    public function __construct(HistoryModel $h) {
        $this->h = $h;
        $this->type = (int)$this->h->get('type');
    }

    public function get()
    {
        $h = $this->h;
        return match ($this->type) {
            HistoryType::ADD => $this->getFildsTextAdd($h->get('field'), $h->get('new_change')),
            HistoryType::EDIT => $this->getFildsTextChange($h->get('field'), $h->get('new_change'), $h->get('old_change')),
            HistoryType::DELETE => $this->getFildsTextDelete($h->get('field'), $h->get('new_change')),
            default => ''
        };
    }

    public function getFildsTextChange($field, $new, $old) {
        $h = $this->h;
        return $this->getName($field) . ' ' . match ($field) {
            'nalog_clinic.status' => "<b> " . (new ClinicModel($h->get('subentity_id')))?->name . ' </b> c ' . NalogStatus::get($old) . ' на <b>' . NalogStatus::get($new) . "</b>",
            'nalog.status' => '<b>#' . $h->entity_id . '</b> c ' . NalogStatus::get($new) . ' на <b>' . NalogStatus::get($old). "</b>",
            'nalog_clinic.license_id' => "<b>" . (new ClinicModel($h->get('subentity_id')))?->name . '</b> c ' . (new LicenseModel($new))?->name . ' на <b>' .  (new LicenseModel($old))?->name . " </b>",
            default => "c $old на $new"
        };
    }
    public function getFildsTextAdd($field, $new)
    {
        $h = $this->h;
        return $this->getName($field) . ' ' . match ($field) {
            'nalog.is_send' => " $new",
            'nalog_clinic_files.name' =>  " в клинике <b>" . (new ClinicModel($h->get('subentity_id')))?->name . '</b> добавлен ' . $new ,
            'nalog_clinic.license_id' => "<b>" . (new ClinicModel($h->get('subentity_id')))?->name . '</b> добавлена ' . (new LicenseModel($new))?->name,
            'nalog_clinic.user_id' => " добавлен:  <b> " .(new UsersModel($new))->getNameOrigin() . "</b> в клинике <b> " . (new ClinicModel($h->get('subentity_id')))?->name . "</b>",
            default => "добавлен $new"
        };
    }

    public function getFildsTextDelete($field, $new){
        $h = $this->h;
        return $this->getName($field) . ' ' . match ($field) {
            'nalog_clinic_files.name' =>  " в клинике <b>" . (new ClinicModel($h->get('subentity_id')))?->name . '</b> удален ' . $new ,
            default => "удален $new"
        };
    }

    public function getName($field)
    {
        return match ($field) {
            'nalog_clinic.status' => 'Статус клиники',
            'nalog.status' => 'Статус заявки',
            'nalog_clinic_files.name' => 'Документ',
            'nalog_clinic.license_id' => 'Лицензия',
            'nalog_clinic.user_id' => "Пользователь",
            'nalog.is_send' => 'Сообщение: ',
            default => 'Неизвестное поле'
        };
    }
}