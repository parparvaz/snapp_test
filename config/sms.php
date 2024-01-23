<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'driver' => 'kavenegar',

    'ghasedaksms' => [
        'base_url' => 'http://api.ghasedak.me',
        'api_key' => env('SMS_GHASEDAK_API_KEY'),
    ],

    'kavenegar' => [
        'base_url' => 'https://api.kavenegar.com',
        'api_key' => env('SMS_GHASEDAK_API_KEY'),
    ],

];
