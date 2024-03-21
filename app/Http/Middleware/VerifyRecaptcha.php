<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        $response = $this->verifyRecaptcha($request->input('recaptcha_token'));

        if (!$response['success']) {
            return response()->json(['error' => 'Invalid reCAPTCHA'], 401);
        }

        return $next($request);
    }

    protected function verifyRecaptcha($token)
    {
        $client = new Client();

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
