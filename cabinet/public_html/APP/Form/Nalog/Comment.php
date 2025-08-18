<?php

namespace APP\Form\Nalog;

use APP\Form\Form;
use APP\Model\NalogCommentModel;
use APP\Module\Auth;
use Pet\Request\Request;

class Comment extends Form
{
    public $auth = true;
    public function submit(Request $request)
    {
        $id = attr('id');
        (new NalogCommentModel())->create([
            'user_id' => Auth::$profile['id'],
            'nalog_id' => attr('id'),
            'comment' => attr('comment')
        ]);

        return [
            'type' => 'modal',
            'template' => 'nalog_comment',
            'header' => 'Комментарии к заявки #' . $id,
            'id' => $id,
            'form_name' => 'nalog/Comment',
            'callbackClose' => 'reloadTable'
        ];
    }
}
