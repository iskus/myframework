<?php
namespace core\sources;
class UsefulData
{
    public static $alphabets = [
        'ru' => [
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т',
            'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ],
        'en' => [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z'
        ],
    ];

    public static function getRequest($key = false, $method = 'REQUEST', $type = false)
    {
        if (!$key) return $key;
        $method = strtoupper('_' . $method);
        switch ($method) {
            case '_POST':
                $value = empty($_POST[$key]) ? false : $_POST[$key];
                break;
            case '_GET':
                $value = empty($_GET[$key]) ? false : $_GET[$key];
                break;
            case '_SESSION':
                $value = empty($_SESSION[$key]) ? false : $_SESSION[$key];
                break;
            case '_SERVER':
                $value = empty($_SERVER[$key]) ? false : $_SERVER[$key];
                break;

            case '_REQUEST':
            default:
                $value = empty($_REQUEST[$key]) ? false : $_REQUEST[$key];
                break;

        }
        if ($type) {
            $type = 'is_' . $type;
            return $type($value) ? htmlspecialchars($value) : false;
        }
        return $value;
        //TODO
    }

    public static function occurrence($ip = '', $to = 'utf-8')
    {
        $ip = ($ip) ? $ip : $_SERVER['REMOTE_ADDR'];
        $xml = simplexml_load_file('http://ipgeobase.ru:7020/geo?ip=' . $ip);
        if ($xml->ip->message) {
            if ($to == 'utf-8') {
                return $xml->ip->message;
            } else {
                if (function_exists('iconv')) {
                    return iconv("UTF-8", $to . "//IGNORE", $xml->ip->message);
                } else {
                    return "The library iconv is not supported by your server";
                }
            }
        } else {
            if ($to == 'utf-8') {
                return $xml->ip->region;
            } else {
                if (function_exists('iconv')) {
                    return iconv("UTF-8", $to . "//IGNORE", $xml->ip->region);
                } else {
                    return "The library iconv is not supported by your server";
                }
            }
        }
    }
}