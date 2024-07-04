<?php

namespace HiveGame\Util;

class SessionManager
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function get($key)
    {
        self::startSession();
        return $_SESSION[$key] ?? null;
    }

    public static function set($key, $value)
    {
        self::startSession();
        $_SESSION[$key] = $value;
    }

    public static function unset($key)
    {
        self::startSession();
        unset($_SESSION[$key]);
    }

    public static function destroySession()
    {
        self::startSession();
        session_unset();
        session_destroy();
        $_SESSION = [];
    }
}
