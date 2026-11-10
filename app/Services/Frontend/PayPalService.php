<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalService
{
    public function getAccessToken(): string
    {
        $clientId = config("paypal.client_id");
        $clientSecret = config("paypal.client_secret");

        if (!$clientId || !$clientSecret) {
            throw new RuntimeException(
                "PayPal API credentials are not configured."
            );
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->timeout(30)
            ->post(config("paypal.base_url") . "/v1/oauth2/token", [
                "grant_type" => "client_credentials",
            ]);

        $response->throw();

        $accessToken = $response->json("access_token");

        if (!$accessToken) {
            throw new RuntimeException(
                "PayPal did not return an access token."
            );
        }

        return $accessToken;
    }

    public function createOrder(float $amount, string $reference): array
    {
        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(30)
            ->post(config("paypal.base_url") . "/v2/checkout/orders", [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "reference_id" => $reference,
                        "custom_id" => $reference,
                        "amount" => [
                            "currency_code" => config("paypal.currency", "USD"),
                            "value" => number_format($amount, 2, ".", ""),
                        ],
                    ],
                ],
            ]);

        $response->throw();
        $paypalOrder = $response->json();

        if (empty($paypalOrder["id"])) {
            throw new \RuntimeException("PayPal did not return an order ID.");
        }

        return $paypalOrder;
    }
}
