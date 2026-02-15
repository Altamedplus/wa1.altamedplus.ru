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
        //self::dd($data);
        // self::dd([$_POST,  file_get_contents('php://input')]);
        try {
            switch ($data['update_type']) {
                case 'bot_started':
                    $this->botStarted($data['user']['user_id'], $data);
                    break;
                case 'message_created':
                    $this->message($data);
                    break;
                default:
                    $this->resenderJivo($data);
                    break;
            }
        } catch (Exception $e) {
            self::dd($e->getMessage());
        }
        //  self::dd($this->maxMessenger->getResult());
        return ['success' => true];
    }


    private function botStarted($userId, $data) {
        $contact = new Contact(['max_user_id' => $userId], isNotExistCreate: true);
        if (empty($contact->step_authorization)) {
            $result = $this->maxMessenger->sendMessangeUser([
                'text' => 'Для получения сообщений от «Альтамед+» вам необходимо авторизоваться. \n\r Напишите ваш номер следующим сообщением и нажмите отправить. Вам придет СМС-сообщение с кодом подтверждения, который нужно будет ввести в этом чате. В дальнейшем авторизация больше не потребуется. ☺️'
            ], $userId);
            $contact->set('step_authorization', TypeAutorization::START);
            $firstName = $data['message']['sender']['first_name'] ?? '';
            $lastName = $data['message']['sender']['last_name'] ?? '';
            $contact->set('name', "$firstName $lastName");
        }
    }

    private function message($data) {
        $userId = $data['message']['sender']['user_id'];
        $contact = new Contact(['max_user_id' => $userId]);

        //  если такого контакта нет
        if (!$contact->exist()) {
            $this->botStarted($userId, $data);
            return;
        }

        $text = mb_strtolower(trim($data['message']['body']['text']));
       // self::dd(['text' => $text, 'is' => in_array($text, $this->command) ? '']);
        if (in_array($text, $this->command)) {
            $contact->set('step_authorization', null); // обнуляем авторизацию
            $contact->set('phone', null);
            $this->botStarted($userId, $data);
            return;
        }

        $step = (int)$contact->get('step_authorization');

        if ($step == TypeAutorization::START) {
            $phone = Form::sanitazePhone($data['message']['body']['text']);
            if (!Form::validatePhone($phone)) {
                $this->maxMessenger->sendMessangeUser([
                    'text' => "$phone Номер телефона не валидный"
                ], $userId);
            } else {
                $this->sendCode($contact, $phone, $userId);
            }
            return;
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
        } else {
            $this->resenderJivo($data);
        }

    }

    public function sendCode($contact, $phone, $userId): void
    {
        $code = rand(100000, 999999);
        $contact->set('code', $code);
        $this->maxMessenger->sendMessangeUser([
            'text' => "Код с номером подтверждения отправлен в СМС на номер $phone. Если вы неверно указали номер телефона напишите следующим сообщением \"сброс авторизации\" без кавычек и процедура авторизации начнется сначала.",
        ], $userId);
        $contact->set('step_authorization', TypeAutorization::CODE);
        $contact->set('phone', $phone);
        $r = (new Sms())->send($phone, "Код авторизации бота в Mаx: $code");
        //self::dd("Responce SMSC: " . $r);
    }


    public static function dd($data): void
    {
        file_put_contents(__DIR__ . '/debug.txt', print_r($data, true) . "\n", FILE_APPEND);
    }

    private function auth($contact)
    {
        $this->maxMessenger->sendMessangeUser([
            'text' => "Авторизация прошла успешно! Теперь мы на связи: пишите нам в чат, а мы будем заранее напоминать о визитах к врачу и подсказывать, как к ним подготовиться.",
        ], $contact->max_user_id);
        $contact->set('step_authorization', TypeAutorization::AUTORIZATION);
    }

    public function info(){
        $this->maxMessenger = new Messenger();
        Response::set(Response::TYPE_JSON);
        Response::die($this->maxMessenger->subscriptions());
    }


    public function resenderJivo($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($curl, CURLOPT_URL, 'https://joint.jivo.ru/Kj9mP4vN8xL2qR7t/f9LHodD0cOIMO1nE4s82Q63DkbPsDc3gmgN_M-iOZDcSSJjVDNUDsQRHBAnlyqGIFdM0vL9YXaCcx04cFC6i');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_exec($curl);
    }
}
