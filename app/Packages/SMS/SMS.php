<?php

namespace App\Packages\SMS;

use Exception;

class SMS
{
    const string KAVENEGAR = 'kavenegar';
    const string GHASEDAKSMS = 'ghasedaksms';
    private static string $driver;
    private static ?self $instance = null;
    private static ISMS $sms;

    public function __construct()
    {
        self::$driver = config('sms.driver');
    }

    /**
     * @throws Exception
     */
    public static function __callStatic($method, $arguments)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$sms = self::$instance::run();
        }

        if (method_exists(self::$sms, $method)) {
            return call_user_func_array([self::$sms, $method], $arguments);
        }

        throw new Exception("Method {$method} not found in the logger class.");
    }

    /**
     * @throws Exception
     */
    public static function run(): ISMS
    {
        return match (self::$driver) {
            self::KAVENEGAR => new Kavenegar(),
            self::GHASEDAKSMS => new Ghasedaksms(),
            default => throw new Exception(sprintf("Unsupported log driver: {%s}", self::$driver)),
        };
    }
}
