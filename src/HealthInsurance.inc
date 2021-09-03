<?php

namespace CogentHealth\Healthinsurance;

use App\Utils\Options;
use CogentHealth\Healthinsurance\services\HiAuthService;

class HealthInsurance
{
    protected static $message;
    protected static $success = true;
    protected static $httpStatusCode;
    protected static $responseBody;

    private static $username;
    private static $password;
    private static $hiClient;
    private static $clientOptions;
    private static $hostName;

    /**
     *
     * @var Singleton
     */
    private static $instance;

    public function __construct()
    {
        self::$username = HiAuthService::getUsername();
        self::$password = HiAuthService::getPassword();
        self::$hostName =env("HI_API_URL")?env("HI_API_URL"):Options::get('hi_settings')['hi_url']??'';

        self::$clientOptions = [
            'verify' => false,
            'auth' => [
                self::$username,
                self::$password
            ],
            'headers' => [
                'remote-user' => Options::get('hi_settings')['hi_remote_user']??''
            ],
            'base_uri' =>self::$hostName
        ];
        self::$hiClient = new Client(self::$clientOptions);
    }

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
