<?php
namespace APP\Module;

class Tool
{

    public static function sanitazePhone(string $phone): string
    {
        return str_replace(['+', ')','(', '-', ' '], '', $phone);
    }

    public static function tokenRandom():string
    {
        return md5(uniqid().SALT.rand(1, 1000));
    }
    public static function fomatTime(string $time, $format = "hh:mm") {
        $time = explode(':', $time);
        if ($format = "hh:mm") {
            return $time[0] . ':' . $time[1];
        }
    }

    public static function value($key, array $array) :array
    {
        $result = [];
        foreach ($array as $v) {
            $result[] = $v[$key];
        }
        return $result;
    }

    public static function map(array $array, $callback, $isValue = true): mixed
    {
        foreach ($array as $k => $v) {
            if ($callback($k, $v)) {
                return $isValue ? $v : $k;
            }
        }
        return null;
    }
}