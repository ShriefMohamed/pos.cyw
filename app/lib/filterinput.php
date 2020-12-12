<?php


namespace Framework\lib;


trait FilterInput
{
    private static function Check($value)
    {
        return empty($value) ? false : $value;
    }

    public static function Int($value)
    {
        return is_int(self::Check(intval($value))) ? intval(filter_var($value, FILTER_SANITIZE_NUMBER_INT)) : false;
    }

    public static function Float($value)
    {
        return is_float(self::Check(floatval($value))) ? floatval(filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) : false;
    }

    public static function String($value)
    {
        return is_string(self::Check($value)) ? filter_var($value, FILTER_SANITIZE_STRING) : false;
    }

    public static function CleanString($value)
    {
//        return is_string(self::Check($value)) ? preg_replace('/[^A-z0-9 -]+/', '', $value) : false;
        return is_string(self::Check($value)) ? preg_replace('/[^A-Za-z0-9 -]/', '', $value) : false;
    }

    public static function Email($value)
    {
        return self::Check($value) ? filter_var($value, FILTER_VALIDATE_EMAIL) : false;
    }
}