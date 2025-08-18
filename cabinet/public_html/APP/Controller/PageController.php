<?php

namespace APP\Controller;

use APP\Enum\Menu;
use APP\Module\Auth;
use APP\Enum\MenuHeaderEnum;
use Pet\Controller;
use Pet\Cookie\Cookie;
use Pet\Request\Request;
use Pet\Router\Header;
use Pet\View\View;

class PageController extends Controller
{

    public function __construct()
    {
        $pageInit = in_array(request()->path, ["/",""]) ?  '/home' : request()->path ;
        View::append(["desktop" =>  "$pageInit/desktop.php"]);
        View::append(["menu" => Menu::data((int)Auth::$profile['type'])]);
        // View::append(["headerLink" => MenuHeaderEnum::data()]);
        View::append(['headerButtons' => [
           [
                'tag' => 'button',
                'class' => 'btn-round btn-content-log-in',
                'evt' => 'exit',
                'data' => 'log-in'
            ]
        ]]);
    }
}
