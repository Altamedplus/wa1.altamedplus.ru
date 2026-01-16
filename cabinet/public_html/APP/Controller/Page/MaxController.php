<?php

namespace APP\Controller\Page;

use APP\Controller\PageController;
use APP\Module\Max\Messenger;

class MaxController extends PageController
{

    public function index()
    {
         view('page.max.init', [
            "header" => "Max",
            "me" => (new Messenger())->me()['response'],
            'hook' => (new Messenger())->subscriptions()['response'],
            'type' => [
                'user_added',
                'bot_added',
                'bot_removed',
                'message_callback',
                'message_removed',
                'message_created',
                'message_edited',
                'bot_started',
                'chat_title_changed',
                'message_chat_created',
                'user_removed',
            ]
        ]);
    }
}
