<?php

use APP\Controller\AjaxController;
use APP\Controller\Cron\AuthReset;
use APP\Controller\Cron\MessageSend;
use APP\Controller\ForgotController;
use APP\Controller\LoginController;
use APP\Controller\ModalController;
use APP\Controller\Page\ButtonsController;
use APP\Controller\Page\ClinicController;
use APP\Controller\Page\EdnaController;
use APP\Controller\Page\EdnaSampleController;
use APP\Controller\Page\GroupSampleController;
use APP\Controller\Page\HomeController;
use APP\Controller\Page\LicenseController;
use APP\Controller\Page\MessageController;
use APP\Controller\Page\NalogController;
use APP\Controller\Page\SampleController;
use APP\Controller\Page\UsersController;
use APP\Controller\Page\VariableController;
use APP\Controller\Page\SettingController;
use Pet\Router\Router;
use APP\Form\Form;
use APP\Module\Auth;
use APP\Model\Table\Datatable;
use Pet\Router\Error as RE;
use Pet\Router\Response;

// вызов событий по заголовкам если приезжает
// имя заголвка   | в инит можно получить его тело в request->header[имя заголовка]
Router::$event = [
    Form::$action => [Form::class, 'init'],
    Datatable::$action => [function () {
        Auth::init();
        if (!Auth::$isAuth) {
            RE::setHttp(RE::STATUS_HTTP::FORBIDDEN);
            Response::die("Нет авторизации");
        }
        $request = request();
        return (new Datatable())->init($request);
    }]
];

Router::get('/forgot', [ForgotController::class, 'index']);

Router::middleware(
    [Auth::class, 'init']
)->set(
    Router::get('/', [HomeController::class, 'index']),
    Router::get('/login', [LoginController::class, 'index']),
    Router::get('/clinic', [ClinicController::class, 'index']),
    Router::get('/users', [UsersController::class, 'index']),
    Router::get('/buttons', [ButtonsController::class, 'index']),
    Router::get('/sample', [SampleController::class, 'index']),
    Router::get('/group_sample', [GroupSampleController::class, 'index']),
    Router::get('/variable', [VariableController::class, 'index']),
    Router::get('/edna', [EdnaController::class, 'index']),
    Router::get('/message', [MessageController::class, 'index']),
    Router::get('/nalog', [NalogController::class, 'index']),
    Router::get('/license', [LicenseController::class, 'index']),
    Router::get('/edna_sample', [EdnaSampleController::class, 'index']),
    Router::get('/setting', [SettingController::class, 'index']),
    
    Router::get('/clinic/edit/{id}', [ClinicController::class, 'edit']),
    Router::get('/users/edit/{id}', [UsersController::class, 'edit']),
    Router::get('/buttons/edit/{id}', [ButtonsController::class, 'edit']),
    Router::get('/sample/edit/{id}', [SampleController::class, 'edit']),
    Router::get('/group_sample/edit/{id}', [GroupSampleController::class, 'edit']),
    Router::get('/variable/edit/{id}', [VariableController::class, 'edit']),
    Router::get('/edna/edit/{id}', [EdnaController::class, 'edit']),
    Router::get('/license/edit/{id}', [LicenseController::class, 'edit']),
    Router::get('/nalog/edit/{id}/{clinic}', [NalogController::class, 'edit']),

    Router::get('/users/add', [UsersController::class, 'add']),
    Router::get('/clinic/add', [ClinicController::class, 'add']),
    Router::get('/buttons/add', [ButtonsController::class, 'add']),
    Router::get('/sample/add', [SampleController::class, 'add']),
    Router::get('/group_sample/add', [GroupSampleController::class, 'add']),
    Router::get('/variable/add', [VariableController::class, 'add']),
    Router::get('/edna/add', [EdnaController::class, 'add']),
    Router::get('/license/add', [LicenseController::class, 'add']),

    Router::get('/nalog/downloand/{id}', [NalogController::class, 'downloand']),

    Router::post('/ajax/{name}', [AjaxController::class, 'index']),
);
Router::post('/modal', [ModalController::class, 'index']);
Router::get('/cron/message/send', [MessageSend::class, 'index']);
Router::get('/cron/auth/reset', [AuthReset::class, 'index']);
//Router::get('/cron/auth/set', [AuthReset::class, 'up']);