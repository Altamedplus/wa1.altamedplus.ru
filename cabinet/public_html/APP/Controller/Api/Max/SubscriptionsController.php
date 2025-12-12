<?php

namespace APP\Controller\Api\Max;
use Pet\Controller;

class SubscriptionsController extends Controller
{
    public function index()
    {
        file_put_contents(__DIR__ . '/debug.txt', print_r($_POST, true), FILE_APPEND);
    }
}
