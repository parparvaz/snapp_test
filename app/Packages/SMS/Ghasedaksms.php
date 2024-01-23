<?php

namespace App\Packages\SMS;

final readonly class Ghasedaksms implements ISMS
{

    /**
     * @throws \JsonException
     */
    #[\Override] public function sendSMS(string $message, string $receptor): array
    {
        $endpoint = config('sms.ghasedaksms.base_url') . '/v2/sms/send/simple';

        $res = \Http::withHeader('apikey', config('sms.ghasedaksms.api_key'))
            ->withHeader('cache-control', 'no-cache')
            ->withHeader('content-type', 'application/x-www-form-urlencoded')
            ->post($endpoint, [
                'message' => 'message',
                'receptor' => 'receptor'
            ]);

        if (!json_validate($res->body())) {

        }

        return json_decode($res->body(), true, 512, JSON_THROW_ON_ERROR);
    }
}
