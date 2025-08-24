<?php

namespace APP\Form;

use APP\Module\Auth;
use APP\Module\Tool;
use Error;
use Exception;
use Normalizer;
use Pet\Controller;
use Pet\Errors\AppException;
use Pet\Request\Request;
use Pet\Router\Error as RE;
use Pet\Router\Response;
use Pet\Router\Router;
use Pet\Session\Session;
use Pet\Tools\Tools;

class Form extends Controller
{
    public static $action = 'form-name';
    public $fields = [];
    public $auth = false;

    final public static function init(Request $request)
    {
        $nameForm = implode('\\', Tools::filter(explode('/', $request->header[self::$action]), fn($k, $v)=>ucfirst($v)));
        $nameClass = "APP\\Form\\$nameForm";

        if (!class_exists($nameClass)) {
            throw new AppException("Нет такого класса фoрмы $nameClass", E_ERROR);
        }
        if (attr('csrf-token') == Session::get('csrf-token')) {
            unset(Request::$attribute['csrf-token']);
            Response::set(Response::TYPE_JSON);
            $formClass = new $nameClass();
            if ($formClass->auth) {
                Auth::init();
            }
            return $formClass->submit($request);
        } else {
            RE::setHttp(RE::STATUS_HTTP::FORBIDDEN);
            Response::die("Hе действительный токен csrf или проблема с сессиями на сервере");
        }
    }

    final public static function csrf($current = false): string
    {
        if ($current) {
            return (new Session())->get('csrf-token');
        }
        $token = bin2hex(random_bytes(32));
        (new Session())->set(['csrf-token' => $token]);
        return $token;
    }

    final public static function normalizerFields(): array
    {
        $result = [];
        foreach (attrs() as $k => $attr) {
            $result[str_replace('_', '.', $k)] = $attr;
        }
        return $result;
    }

    final public static function validatePassword($password):string|false
    {
        if (strlen($password) < 8) {
            return "Пароль должен содержать не менее 8 символов.";
        }

        if (!preg_match('/\d/', $password)) {
            return "Пароль должен содержать хотя бы одну цифру.";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return "Пароль должен содержать хотя бы одну заглавную букву.";
        }

        if (!preg_match('/[a-z]/', $password)) {
            return "Пароль должен содержать хотя бы одну строчную букву.";
        }

        if (!preg_match('/[\W_]/', $password)) {
            return "Пароль должен содержать хотя бы один специальный символ.";
        }
        return false;
    }

    final public static function validateEmail($email):string|false
    {
        if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            return true;
        }
        return false;
    }

    final public static function generatePassword(int $length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?';
        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, $maxIndex);
            $password .= $chars[$index];
        }

        return $password;
    }

    /**
     * generatePasswordMask
     *
     * @param  int $length
     * @return string
     */
    final public static function generatePasswordMask(int $length = 0): string{

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= '&#8226 ';
        }
        return $str;
    }

    /**
     * validatePhone
     *
     * @param  string $phone
     * @return bool
     */
    final public static function validatePhone(string $phone): bool
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) == 11) {
            if ($digits[0] == '7' || $digits[0] == '8') {
                return true;
            }
        }
        return false;
    }

    /**
     * sanitazePhone
     *
     * @param string $phone
     * @return string
     */
    final public static function sanitazePhone(string $phone): string
    {
        $phone = str_replace(['+', '(', ')', '-', ' '], '', trim($phone));
        $phone[0] = '7';
        return $phone;
    }

     /**
     * sanitazePhone
     *
     * @param string $phone
     * @return string
     */
    final public static function unsaitazePhone(string $phone): string
    {
        $p = str_replace(['+', '(', ')', '-', ' '], '', trim($phone));
        $p[0] = '7';

        return "+" . $p[0] . '(' . $p[1] . $p[2] . $p[3] . ')' . $p[4] . $p[5] . $p[6] . '-' . $p[7] . $p[8] . '-' . $p[9] . $p[10];
    }
}
