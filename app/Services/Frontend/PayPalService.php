<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalService
{
    public function getAccessToken(): string
    {
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new RuntimeException(
                'PayPal API credentials are not configured.'
            );
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->timeout(30)
            ->post(
                config('paypal.base_url') . '/v1/oauth2/token',
                [
                    'grant_type' => 'client_credentials',
                ]
            );

        $response->throw();

        $accessToken = $response->json('access_token');

        if (!$accessToken) {
            throw new RuntimeException(
                'PayPal did not return an access token.'
            );
        }

        return $accessToken;
    }
}