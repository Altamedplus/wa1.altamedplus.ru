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
use PhpParser\Node\Expr\Cast\Object_;

class SubscriptionsController extends Controller {
    private $command = [
        'сброс авторизации',
        'сменить номер телефона',
        'начать авторизацию с начала'
    ];
    protected Messenger $maxMessenger;
    public function index()
    {
        $this->maxMessenger = new Messenger();
        Response::set(Response::TYPE_JSON);
        $data = attr();
        // self::dd($data);
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
        //  self::dd($this->maxMessenger->getResult());
        return ['success' => true];
    }


    private function botStarted($userId) {
        $contact = new Contact(['max_user_id' => $userId], isNotExistCreate: true);
        if (empty($contact->step_authorization)) {
            $result = $this->maxMessenger->sendMessangeUser([
                'text' => 'Для получения уведолений вам требуется авторизоваться! Напишите ваш номер телефона и вам придет смс кодом и запишите его следующим сообщением.'
            ], $userId);
            $contact->set('step_authorization', TypeAutorization::START);
        }
    }

    private function message($data) {
        $userId = $data['message']['sender']['user_id'];
        $contact = new Contact(['max_user_id' => $userId]);

        //  если такого контакта нет
        if (!$contact->exist()) {
            $this->botStarted($userId);
            return;
        }

        $text = mb_strtolower(trim($data['message']['body']['text']));
       // self::dd(['text' => $text, 'is' => in_array($text, $this->command) ? '']);
        if (in_array($text, $this->command)) {
            $contact->set('step_authorization', null); // обнуляем авторизацию
            //$contact->set('phone', null);
            $this->botStarted($userId);
            return;
        }

        $step = (int)$contact->get('step_authorization');

        if ($step == TypeAutorization::START) {
            $phone = Form::sanitazePhone($data['message']['body']['text']);
            if (!Form::validatePhone($phone)) {
                $this->maxMessenger->sendMessangeUser([
                    'text' => "$phone Номер телефона не валидный"
                ], $userId);
            } elseif (!empty($phone) && (int)$phone === (int)$contact->phone) {
                $this->auth($contact);
            } else {
                $this->sendCode($contact, $phone, $userId);
            }
        } elseif ($step == TypeAutorization::CODE) {
            $code = trim($data['message']['body']['text']);
            if ($code == $contact->code) {
                $this->auth($contact);
            } else {
                $this->maxMessenger->sendMessangeUser([
                    'text' => "Неверный код.",
                    "attachments" => [
                        [
                            "type" => "inline_keyboard",
                            "payload" => [
                                'buttons' => [
                                    [
                                        [
                                            'type' => 'message',
                                            'text' => 'Начать авторизацию с начала',
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ], $userId);
            }
        }
    }

    public function sendCode($contact, $phone, $userId): void
    {
        $code = rand(100000, 999999);
        $contact->set('code', $code);
        $typeCommand = implode(', ', $this->command);
        $this->maxMessenger->sendMessangeUser([
            'text' => "Код отправлен на номер телефона $phone. Если хотите исправить номер телефона напишите $typeCommand.",
        ], $userId);
        $contact->set('step_authorization', TypeAutorization::CODE);
        $contact->set('phone', $phone);
        $r = (new Sms())->send($phone, "Код авторизации бота в Mаx: $code");
        self::dd("Responce SMSC: " . $r);
    }


    public static function dd($data): void
    {
        file_put_contents(__DIR__ . '/debug.txt', print_r($data, true) . "\n", FILE_APPEND);
    }

    private function auth($contact)
    {
        $this->maxMessenger->sendMessangeUser([
            'text' => "Вы успешно авторизовались теперь вы будете получать сообщения о записях и прочее от Альтамед+",
        ], $contact->max_user_id);
        $contact->set('step_authorization', TypeAutorization::AUTORIZATION);
    }
}
