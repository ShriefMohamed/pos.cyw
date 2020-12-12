<?php


namespace Framework\Lib;


class Session extends \SessionHandler
{
    private $sessionName = 'CYW';
    private $sessionSavePath = SESSIONS_PATH;
    private $sessionRenewTime = 1;
    private static $cipher;

    public function __construct()
    {
        session_name($this->sessionName);
        session_save_path($this->sessionSavePath);
        session_set_save_handler($this, true);
        self::$cipher = new Cipher();
    }

    /* Initialize Sessions */
    public function Initiate()
    {
        if (session_id() === '') {
            if (session_start()) {
                $this->Check();
            }
        }
    }

    /* Private Functions */
    private function Check()
    {
        if (self::Exists('session_start_time')) {
            if (SERVER_TIMESTAMP - self::Get('session_start_time') > ($this->sessionRenewTime * 60)) {
                $this->Renew();
            }
        } else {
            $this->SetStartTime();
        }
    }

    private function Renew()
    {
        $this->SetStartTime();
        return session_regenerate_id();
    }

    private function SetStartTime()
    {
        if (!self::Exists('session_start_time')) {
            self::Set('session_start_time', SERVER_TIMESTAMP);
        }
    }
    /* END Private */


    /* Sessions Function */
    public static function Exists($key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    public static function Set($key, $value)
    {
        if ($key !== '' && $value !== '') {
            $_SESSION[$key] = self::$cipher->Encrypt($value);
        }
    }

    public static function Append($key, $value)
    {
        if ($key && $value) {
            $session = self::Get($key) ?: [];
            $session[] = $value;
            $_SESSION[$key] = self::$cipher->Encrypt($session);
        }
    }

    public static function Get($key)
    {
        return ((self::Exists($key) !== false) && !empty($_SESSION[$key])) ? self::$cipher->Decrypt($_SESSION[$key]) : false;
    }

    public static function Remove($key)
    {
        if ($key !== '') {
            if (self::Exists($key)) {
                unset($_SESSION[$key]);
            }
        }
    }

    public static function KillAll()
    {
        session_destroy();
    }
    /* End Sessions Function */

    /* Cookies Functions */
    public static function CookieExists($key)
    {
        return isset($_COOKIE[$key]);
    }

    public static function SetCookie($key, $value, $expire = 60)
    {
        if ($key !== '' && $value !== '') {
            setcookie($key, self::$cipher->Encrypt($value), SERVER_TIMESTAMP + $expire*60, '/');
        }
    }

    public static function GetCookie($key)
    {
        if (self::CookieExists($key) && !empty($_COOKIE[$key])) {
            return self::$cipher->Decrypt($_COOKIE[$key]);
        }
    }

    public static function RemoveCookie($key)
    {
        if ($key) {
            if (self::CookieExists($key)) {
                setcookie($key, '', SERVER_TIMESTAMP - 3600);
            }
        }
    }
    /* End Cookies Functions */
}