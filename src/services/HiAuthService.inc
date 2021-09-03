<?php
namespace CogentHealth\Healthinsurance\services;

use App\Utils\Options;

class HiAuthService
{
    protected static $password;
    protected static $username;

    /**
     *
     * @var Singleton
     */
    private static $instance;

    public function __construct()
    {
        self::$username =  Options::get('hi_settings')['hi_username'] ?? '';
        self::$password =  Options::get('hi_settings')['hi_password'] ?? '';
    }

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getUsername()
    {
        self::init();
        return self::$username;
    }

    public static function getPassword()
    {
        self::init();
        return self::$password;
    }
}
