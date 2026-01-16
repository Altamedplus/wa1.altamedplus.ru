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
    protected Messenger $maxMessenger;
    public function index()
    {
        $this->maxMessenger = new Messenger();
        Response::set(Response::TYPE_JSON);
        $data = attr();
        try {
            switch ($data['update_type']) {
                case 'bot_started':
                    $this->botStarted($data['user']['user_id']);
                    break;
                case 'message_created':
                    $this->message($data);
                    break;
            }
        } catch (Exception $e) {
            self::dd($e->getMessage());
        }

        return ['success' => true];
    }


    private function botStarted($userId)
    {
        $contact = new Contact(['max_user_id' => $userId], isNotExistCreate:true);
        if (empty($contact->step_authorization)) {
            $result = $this->maxMessenger->sendMessangeUser([
                'text' => 'Для получения уведолений вам требуется авторизоваться! Напишите ваш номер телефона и вам придет смс кодом и запишите его следующим сообщением.'
            ], $userId);
            $contact->set('step_authorization', TypeAutorization::START);
        }
    }

    private function message($data)
    {
        $userId = $data['message']['sender']['user_id'];
        $contact = new Contact(['max_user_id' => $userId]);
        $text = mb_strtolower(trim($data['message']['body']['text']));

        if (in_array($text, $this->command)) {
            $contact->set('step_authorization', null); // обнуляем авторизацию
            $contact->set('phone', null);
            $this->botStarted($userId);
            return;
        }

        if ((int)$contact->get('step_authorization') == TypeAutorization::START) {
            $phone = Form::sanitazePhone($data['message']['body']['text']);
            if (!Form::validatePhone($phone)) {
                 $this->maxMessenger->sendMessangeUser([
                    'text' => "$phone Номер телефона не валидный"
                ], $userId);
            } elseif (!empty($phone) && (int)$phone === (int)$contact->phone) {
                $this->auth($contact);
                return;
            } else {
                $code = rand(100000, 999999);
                $contact = $contact->reContact($phone);
                $contact->set('code', $code);
                $this->maxMessenger->sendMessangeUser([
                    'text' => "Код отправлен на номер телефона $phone . Если хотите исправить номер телефона напишите " . implode(', ', $this->command)."."
                ], $userId);
                $contact->set('step_authorization', TypeAutorization::CODE);
                $r = (new Sms())->send($phone, "Код авторизации бота в Mаx: $code");
                self::dd("Responce SMSC: " . $r);
                return;
            }
        }

        if ((int)$contact->get('step_authorization')  == TypeAutorization::CODE) {
            $code = trim($data['message']['body']['text']);
            if ($code == $contact->code) {
                $this->auth($contact);
            } else {
                 $this->maxMessenger->sendMessangeUser([
                    'text' => "Неверный код."
                ], $userId);
            }
        }
    }


    private static function dd($data): void
    {
        file_put_contents(__DIR__ . '/debug.txt', print_r($data, true) . "\n", FILE_APPEND);
    }

    private function auth($contact)
    {
        $this->maxMessenger->sendMessangeUser([
            'text' => "Вы успешно авторизовались теперь вы будете получать сообщения о записях и прочее от Альтамед+"
        ], $contact->max_user_id);
        $contact->set('step_authorization', TypeAutorization::AUTORIZATION);
    }
}
