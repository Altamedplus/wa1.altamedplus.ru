<?php
namespace APP\Enum;

use APP\Enum\UsersType as UT;
use APP\Module\Auth;
use Pet\View\View;

class Menu
{
    const HOME = 1;
    const CLINIC = 2;
    const USERS = 3;
    const BUTTONS = 4;
    const VARIABLE = 5;
    const SAMPLE = 6;
    const GROUP_SAMPLE = 7;
    const EDNA = 8;
    const MESSAGE = 9;
    const NALOG = 10;
    const LICENSE = 11;
    const EDNA_SAPMLE = 12;
    const SETTING = 13;

    public static function data($UT = UT::SYSADMIN): array
    {
        $page = [
            self::HOME => (object)['url' => '/', 'name' => 'Сообщения', 'icon' => 'menu.message'],
            self::MESSAGE => (object)['url' => '/message', 'name' => 'Отправленные', 'icon' => 'menu.mail'],
            self::CLINIC => (object)['url' => '/clinic', 'name' => 'Клиники', 'icon' => 'menu.home'],
            self::USERS => (object)['url' => '/users', 'name' => 'Пользователи', 'icon' => 'menu.users'],
            self::BUTTONS => (object)['url' => '/buttons', 'name' => 'Кнопки', 'icon' => 'menu.minus-square'],
            self::VARIABLE => (object)['url' => '/variable', 'name' => 'Переменные', 'icon' => 'menu.check-square'],
            self::SAMPLE => (object)['url' => '/sample', 'name' => 'Шаблоны', 'icon' => 'menu.credit-card'],
            self::GROUP_SAMPLE => (object)['url' => '/group_sample', 'name' => 'Группы Шаблонов', 'icon' => 'menu.layout'],
            self::NALOG => (object)['url' => '/nalog', 'name' => 'Налоговый вычет', 'icon' => 'menu.file-edit'],
            self::LICENSE => (object)['url' => '/license', 'name' => 'Лицензии', 'icon' => 'menu.file-text'],
            self::EDNA => (object)['url' => '/edna', 'name' => 'Edna api', 'icon' => 'menu.union'],
            self::EDNA_SAPMLE => (object)['url' => '/edna_sample', 'name' => 'Edna Шаблоны', 'icon' => 'menu.download-cloud'],
            self::SETTING => (object)['url' => '/setting', 'name' => 'Настройки', 'icon' => 'menu.settings'],
        ];
        if (Auth::$profile['type'] == UT::ADMIN) {
            unset($page[self::EDNA], $page[self::GROUP_SAMPLE], $page[self::SAMPLE], $page[self::CLINIC], $page[self::BUTTONS], $page[self::USERS], $page[self::VARIABLE], $page[self::EDNA_SAPMLE]);
        }
        if (Auth::$profile['type'] == UT::SENIOR_ADMIN) {
            unset($page[self::EDNA], $page[self::GROUP_SAMPLE], $page[self::SAMPLE], $page[self::CLINIC], $page[self::BUTTONS], $page[self::VARIABLE],$page[self::EDNA_SAPMLE]);
        }
        if (Auth::$profile['type'] == UT::MARKETING) {
            unset($page[self::EDNA], $page[self::GROUP_SAMPLE], $page[self::SAMPLE], $page[self::CLINIC], $page[self::BUTTONS], $page[self::USERS], $page[self::VARIABLE], $page[self::EDNA_SAPMLE]);
        }
        if (Auth::$profile['type'] == UT::DOCTOR) {
            unset($page[self::EDNA], $page[self::GROUP_SAMPLE], $page[self::SAMPLE], $page[self::CLINIC], $page[self::BUTTONS], $page[self::USERS], $page[self::VARIABLE], $page[self::EDNA_SAPMLE]);
        }
        return $page;
    }
}