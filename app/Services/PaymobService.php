<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobService
{
    private $secretKey;

    private $publicKey;

    private $cardIntegrationId;

    public function __construct()
    {
        $this->publicKey = config('services.paymob.public_key');
        $this->secretKey = config('services.paymob.secret_key');
        $this->cardIntegrationId = (int) config('services.paymob.card_integration_id');
    }

    public function createIntention($amount, $billingData, $customerData, $redirectUrl): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token '.$this->secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://accept.paymob.com/v1/intention/', [
                'amount' => $amount * 100, // Paymob expects the amount in piasters
                'currency' => 'EGP',
                'payment_methods' => [$this->cardIntegrationId],
                'items' => [[
                    'name' => 'EasyCook Order',
                    'amount' => $amount * 100,
                    'description' => 'Subscription payment',
                    'quantity' => 1,
                ]],
                'billing_data' => $billingData,
                'customer' => $customerData,
                'redirect_url' => $redirectUrl,
                'extras' => ['source' => 'easycook'],
            ]);

            $data = $response->json();

            Log::info('Paymob intention response', [
                'status' => $response->status(),
                'response' => $data,
            ]);

            if ($response->successful() && isset($data['client_secret'])) {
                return [
                    'client_secret' => $data['client_secret'],
                    'intention_id' => $data['id'] ?? null,
                    'order_id' => $data['intention_order_id'] ?? null,
                ];
            }

            Log::error('Paymob intention failed', [
                'status' => $response->status(),
                'response' => $data,
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Paymob intention exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    public function getCheckoutUrl($clientSecret): string
    {
        return "https://accept.paymob.com/unifiedcheckout/?publicKey={$this->publicKey}&clientSecret={$clientSecret}";
    }
}
