<?php

namespace App\Packages\SMS;

interface ISMS
{
    public function sendSMS(string $message, string $receptor): array;
}
