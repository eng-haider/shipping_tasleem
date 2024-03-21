<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class OtpSmsService
{
    public function sendOtp($phoneNumber, $otp)
    {
        $msg= [
            "RequestorId"=>"Your Requestor Id",
            'number' => $phoneNumber, // Replace with the actual phone number
            'text' =>  "Your OTP is $otp . please use this code to complete verification" ,
        ];
        $response = Http::withBasicAuth(env('OTP_EMAIL'), env('OTP_PASSWORD'))->withoutVerifying()
        ->post(env('OTP_URL'),$msg);
    }
}

