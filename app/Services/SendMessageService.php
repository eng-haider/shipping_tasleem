<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class SendMessageService
{
    public function sendCreateDriverMessage($phoneNumber, $message)
    {
        $msg= [
            "RequestorId" => "Your Requestor Id",
            'number' => $phoneNumber, 
            'text' =>  $message,
        ];
        return Http::withBasicAuth(env('OTP_EMAIL'), env('OTP_PASSWORD'))->withoutVerifying()
        ->post(env('OTP_URL'),$msg);
    }
}

