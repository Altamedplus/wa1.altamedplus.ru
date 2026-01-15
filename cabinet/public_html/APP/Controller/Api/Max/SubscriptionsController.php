<?php

namespace APP\Controller\Api\Max;

use APP\Enum\TypeAutorization;
use APP\Form\Form;
use APP\Model\Contact;
use APP\Module\Max\Messenger;
use APP\Module\Sms;
use Exception;
use Pet\Controller;
use Pet\Router\Response;

class SubscriptionsController extends Controller
{
    private $command = [
        'сброс авторизации',
        'сменить номер телефона',
    ];
    public function index()
    {
        Response::set(Response::TYPE_JSON);
        $data = attr();
        try {
            switch ($data['update_type']) {
                case 'bot_started':
                    $this->botStarted($data);
                    break;
                case 'message_created':
                    $this->message($data);
                    break;
            }
        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/debug.txt', $e->getMessage(), FILE_APPEND);
        }

        file_put_contents(__DIR__ . '/debug.txt', print_r($_SERVER, true), FILE_APPEND);
        file_put_contents(__DIR__ . '/debug.txt', print_r(attr(), true), FILE_APPEND);
        return ['success' => true];
    }


    private function botStarted($data)
    {
        $userId = $data['user']['user_id'];
        $contact = new Contact(['max_user_id' => $userId], isNotExistCreate:true);
        if (empty($contact->step_authorization)) {
            $result = (new Messenger())->sendMessangeUser([
                'text' => 'Для получения уведолений вам требуется авторизоваться! Напишите ваш номер телефона и вам придет смс кодом и запишите его следующим сообщением.'
            ], $userId);
            file_put_contents(__DIR__ . '/debug.txt', print_r($result, true), FILE_APPEND);
            $contact->set('step_authorization', TypeAutorization::START);
        }
    }

    private function message($data)
    {
        $userId = $data['message']['sender']['user_id'];
        $contact = new Contact(['max_user_id' => $userId]);
        $text = mb_strtolower(trim($data['message']['body']['text']));

        if (in_array($text, $this->command)) {
            $contact->set('step_authorization',  TypeAutorization::START);
            return;
        }

        if ($contact->step_authorization  == TypeAutorization::START) {
            $phone = Form::sanitazePhone($data['message']['body']['text']);
            if (!Form::validatePhone($phone)) {
                (new Messenger())->sendMessangeUser([
                    'text' => "$phone Номер телефона не валидный"
                ], $userId);
            } else {
                $code = rand(100000, 999999);
                $contact = $contact->reContact($phone);
                $contact->set('code', $code);
                $r = (new Sms())->send($phone, "Код авторизации бота в Mаx: $code");
                $result = (new Messenger())->sendMessangeUser([
                    'text' => "Код отправлен на номер телефона $phone . Если хотите исправить номер телефона напишите ".implode(", ", $this->command)."."
                ], $userId);
                file_put_contents(__DIR__ . '/debug.txt', print_r($result, true) . "\n" . print_r($r, true) . "\n", FILE_APPEND);
                $contact->set('step_authorization', TypeAutorization::CODE);
            }
        }

        if ($contact->step_authorization  == TypeAutorization::CODE) {
            $code = trim($data['message']['body']['text']);
            if ($code == $contact->code) {
                (new Messenger())->sendMessangeUser([
                    'text' => "Вы успешно авторизовались теперь вы будете получать сообщения о записях и прочее от Альтамед+"
                ], $userId);
                $contact->set('step_authorization', TypeAutorization::AUTORIZATION);
            } else {
                (new Messenger())->sendMessangeUser([
                    'text' => "Неверный код."
                ], $userId);
            }
        }
    }
}
