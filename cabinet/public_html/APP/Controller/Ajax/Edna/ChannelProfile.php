<?php

namespace APP\Controller\Ajax\Edna;

use APP\Controller\AjaxController;
use APP\Module\WhatsApp;

class ChannelProfile extends AjaxController
{

    public function helper()
    {
        $api = (string)attr('api');
        if (empty($api)) {
            return [];
        }
        return (new WhatsApp($api))->getChanalProfile()['response'];
    }
}
