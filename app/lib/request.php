<?php


namespace Framework\lib;


class Request
{
    public static $data;
    public static $strength = 'normal';


    public static function Check($name, $type = 'post')
    {
        return $type == 'get' ?
            ((isset($_GET[$name]) && !empty($_GET[$name])) ? true : false) :
            ((isset($_POST[$name])) ? true : false);
    }

    public static function Get($name = '', $urlDecode = false, $sanitize = false)
    {
        if (self::Check($name, 'get')) {
            if ($urlDecode === true && $sanitize === true) {
                self::$data[$name] = self::Clean(self::Sanitize($_GET[$name]), true);
            } elseif ($urlDecode === true && $sanitize === false) {
                self::$data[$name] = self::Clean($_GET[$name], true);
            } elseif ($sanitize === true && $urlDecode === false) {
                self::$data[$name] = self::Clean(self::Sanitize($_GET[$name]), false);
            } else {
                self::$data[$name] = self::Clean($_GET[$name], false);
            }
        } else {
            self::$data[$name] = null;
        }
        return self::$data[$name];
    }

    public static function Post($name = '', $urlDecode = false, $sanitize = false)
    {
        if (self::Check($name, 'post')) {
            if ($urlDecode === true && $sanitize === true) {
                self::$data[$name] = self::Clean(self::Sanitize($_POST[$name]), true);
            } elseif ($urlDecode === true && $sanitize === false) {
                self::$data[$name] = self::Clean($_POST[$name], true);
            } elseif ($sanitize === true && $urlDecode === false) {
                self::$data[$name] = self::Clean(self::Sanitize($_POST[$name]), false);
            } else {
                self::$data[$name] = self::Clean($_POST[$name], false);
            }
        } else {
            self::$data[$name] = null;
        }
        return self::$data[$name];
    }

    private static function Clean($data, $isUrlEncoded = false)
    {
        return ($isUrlEncoded) ? strip_tags(trim(urldecode($data))) : strip_tags(trim($data));
    }

    private static function Sanitize($data)
    {
        switch (static::$strength) {
            default:
                return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
                break;
            case 'strong':
                return htmlspecialchars($data, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                break;
            case 'strict':
                return urlencode($data);
                break;
        }
    }
}