<?php

namespace Framework\lib;


class Redirect
{
    public static function Home()
    {
        header('location: ' . HOST_NAME);
        exit();
    }

    public static function NotFound()
    {
        header('location: ' . HOST_NAME . 'notfound');
        exit();
    }

    public static function To($path, $return = false)
    {
        if ($path !== '') {
            if (Request::Check('get', 'returnURL')) {
                self::ReturnURL();
            } else {
                $returnURL = ($return === true) ? '?returnURL=' . CURRENT_URI : null;
                header('location: ' . HOST_NAME . $path . $returnURL);
                exit();
            }
        }
    }

    public static function ReturnURL()
    {
        $returnURL = (Request::Check('returnURL', 'get')) ? Request::Get('returnURL', false, true) : null;
        if ($returnURL) {
            header('location: ' . HOST_NAME.$returnURL);
            exit();
        } else {
            self::Home();
        }
    }
}