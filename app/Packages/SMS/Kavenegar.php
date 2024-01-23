<?php

namespace App\Packages\SMS;

use Exception;
use Http;
use Override;

final readonly class Kavenegar implements ISMS
{

    /**
     * @throws \JsonException
     */
    #[Override] public function sendSMS(string $message, string $receptor): array
    {
        $endpoint = config('sms.kavenegar.base_url') . '/v1/' . config('sms.kavenegar.api_key') . '/sms/send.json';

        $res = Http::post($endpoint, [
            'message' => 'message',
            'receptor' => 'receptor'
        ]);

        if (!json_validate($res->body())){
             throw new Exception("Json validation failed");
        }

        return json_decode($res->body(), true, 512, JSON_THROW_ON_ERROR);
    }
}
