<?php

namespace APP\Controller;

use APP\Module\Auth;
use Pet\Controller;
use Pet\Request\Request;
use Pet\Router\Response;

class ForgotController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::$isAuth) {
            Response::redirect('/');
        }
        view('page.forgot.init', [
            'headerButtons' => [
                [
                    'tag' => 'a',
                    'class' => 'btn btn-round btn-content-user-check',
                    'href' => '/register',
                    'data' => 'register',
                    'title' => 'Регистрация'
                ],
                 [
                    'tag' => 'a',
                    'class' => 'btn btn-round btn-content-log-in',
                    'href' => '/login',
                    'data' => 'log-in',
                    'title' => 'Вход/log-in'
                ]
            ]
        ]);
    }
}
