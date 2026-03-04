<?php

namespace APP\Controller\Api\Telegram;

use APP\Enum\TypeAutorization;
use APP\Form\Form;
use APP\Model\Contact;
use APP\Module\Sms;
use APP\Module\Telegram;
use Pet\Controller;
use Pet\Router\Response;
use Pet\Router\Router;

class WebHookController extends Controller
{
    private Telegram $tg;
    public function index()
    {
        $this->tg = (new Telegram());

        $data = attr();
        //self::dd($data);

        $userId = $data['message']['from']['id'] ?? null;
        if (!empty($userId)) {
            $contact = (new Contact())->findM(['tg_user_id' => $userId])[0] ?? new Contact();
            if (!$contact->exist() || empty($contact->get('tg_step_auth'))) {
                $this->started($userId);
                return;
            }
            if ($contact->tg_step_auth === TypeAutorization::START) {
                $phone = Form::sanitazePhone($data['message']['text']);
                if (!Form::validatePhone($phone)) {
                    $this->tg->sendMessage($userId, "$phone Номер телефона не валидный (формат Пример 79999999999)");
                } else {
                    $this->code($userId, $contact, $phone);
                }
            } elseif ($contact->tg_step_auth === TypeAutorization::CODE) {
                $code = trim($data['message']['text']);
                if ($code == $contact->code) {
                    $this->auth($contact);
                } else {
                    $result = $this->tg->sendMessage($userId, "Неверный код.", [
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => '🚀 Сброс авторизации', 'callback_data' => 'started'],
                                ]
                            ],
                        ])
                    ]);
                }
            } else {
                $this->resenderJivo($data);
                sleep(1);
            }
        }
        $userCallbackId =  $data['callback_query']['from']['id'] ?? null;
        if (!empty($userCallbackId)) {
            if ('started' == $data['callback_query']['data']) {
                $this->tg->sendMessage($userCallbackId, 'Авторизация сброшена! Начните сначала');
                $this->started($userCallbackId);
                return;
            } else {
                $contact = (new Contact())->findM(['tg_user_id' => $userCallbackId, 'tg_step_auth' => TypeAutorization::AUTORIZATION])[0] ?? new Contact();
                if ($contact->exist()) {
                    $this->resenderJivo($data);
                }
            }
        }

    }

    public function setwebHook()
    {
        dd((new Telegram())->setWebhook('https://wa1.altamedplus.ru/api/telegram/webhook'));
    }
    public function info()
    {
        Response::set(Response::TYPE_JSON);
        Response::die((new Telegram())->getWebhookInfo());
    }

    public static function dd($data): void
    {
        file_put_contents(__DIR__ . '/debug.txt', print_r($data, true) . "\n", FILE_APPEND);
    }

    private function auth($contact)
    {
        $this->tg->sendMessage($contact->tg_user_id, "Авторизация прошла успешно! Теперь мы на связи: пишите нам в чат, а мы будем заранее напоминать о визитах к врачу и подсказывать, как к ним подготовиться.");
        $contact->set('tg_step_auth', TypeAutorization::AUTORIZATION);
    }

    private function started($userId)
    {
        $contact = (new Contact())->findM(['tg_user_id' => $userId])[0] ?? new Contact(['tg_user_id' => $userId], isNotExistCreate:true);
        $contact->set(['tg_step_auth' => TypeAutorization::START]);
        $this->tg->sendMessage($userId, 'Для получения уведомлений от Альтамед+ требуется авторизация напишите номер телефона  в ответ на сообщение');
    }
    private function code($userId, $contact, $phone)
    {
        $code = rand(100000, 999999);
        $contact->set('code', $code);
        $contact->set('tg_step_auth', TypeAutorization::CODE);
        $contact->set('phone', $phone);
        $result =  $this->tg->sendMessage($userId, "Код с номером подтверждения отправлен в СМС на номер $phone. Если вы неверно указали номер телефона напишите следующим сообщением \"сброс авторизации\" без кавычек и процедура авторизации начнется сначала.", [
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '🚀 Сброс авторизации', 'callback_data' => 'started'],
                    ]
                ],
                'one_time_keyboard' => true

            ],  JSON_UNESCAPED_UNICODE)
        ]);
        //self::dd($result);
        (new Sms())->send($phone, "Код авторизации бота в Телеграмм: $code");
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
        curl_setopt($curl, CURLOPT_URL, "https://joint.jivo.ru/EKJNVVuMFshpdzJT/7422941003:AAGOxt5_mrEhNhyoYZarTAt28NWKXdDu5KM");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_exec($curl);
    }

}