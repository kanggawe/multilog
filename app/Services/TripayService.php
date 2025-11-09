<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected $client;
    protected $config;
    protected $baseUrl;
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;

    public function __construct()
    {
        $this->config = config('tripay');
        $environment = $this->config['environment'];
        
        $this->baseUrl = $this->config[$environment]['base_url'];
        $this->apiKey = $this->config[$environment]['api_key'];
        $this->privateKey = $this->config[$environment]['private_key'];
        $this->merchantCode = $this->config[$environment]['merchant_code'];
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Get available payment channels
     */
    public function getPaymentChannels()
    {
        try {
            $response = $this->client->get('/merchant/payment-channel');
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => $data['success'] ?? false,
                'data' => $data['data'] ?? [],
                'message' => $data['message'] ?? 'Success'
            ];
        } catch (RequestException $e) {
            Log::error('Tripay get payment channels error: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to get payment channels: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a new transaction
     */
    public function createTransaction($paymentData)
    {
        try {
            // Generate signature
            $signature = $this->generateSignature($paymentData);
            
            $payload = [
                'method' => $paymentData['method'],
                'merchant_ref' => $paymentData['merchant_ref'],
                'amount' => $paymentData['amount'],
                'customer_name' => $paymentData['customer_name'],
                'customer_email' => $paymentData['customer_email'],
                'customer_phone' => $paymentData['customer_phone'],
                'order_items' => $paymentData['order_items'],
                'return_url' => $this->config['return_url'],
                'expired_time' => time() + ($this->config['expiry_time'] * 3600), // Convert hours to seconds
                'signature' => $signature
            ];

            // Add optional fields
            if (isset($paymentData['callback_url'])) {
                $payload['callback_url'] = $paymentData['callback_url'];
            } else {
                $payload['callback_url'] = $this->config['callback_url'];
            }

            $response = $this->client->post('/transaction/create', [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => $data['success'] ?? false,
                'data' => $data['data'] ?? [],
                'message' => $data['message'] ?? 'Transaction created'
            ];

        } catch (RequestException $e) {
            Log::error('Tripay create transaction error: ' . $e->getMessage());
            $errorData = json_decode($e->getResponse()->getBody()->getContents(), true);
            
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to create transaction: ' . ($errorData['message'] ?? $e->getMessage())
            ];
        }
    }

    /**
     * Get transaction detail
     */
    public function getTransactionDetail($reference)
    {
        try {
            $response = $this->client->get("/transaction/detail?reference={$reference}");
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => $data['success'] ?? false,
                'data' => $data['data'] ?? [],
                'message' => $data['message'] ?? 'Success'
            ];
        } catch (RequestException $e) {
            Log::error('Tripay get transaction detail error: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to get transaction detail: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate signature for transaction
     */
    private function generateSignature($paymentData)
    {
        $amount = $paymentData['amount'];
        $merchantRef = $paymentData['merchant_ref'];
        $method = $paymentData['method'];
        
        return hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);
    }

    /**
     * Verify callback signature
     */
    public function verifyCallbackSignature($callbackSignature, $merchantRef, $amount)
    {
        $expectedSignature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);
        return hash_equals($expectedSignature, $callbackSignature);
    }

    /**
     * Get transaction status from callback data
     */
    public function processCallback($callbackData)
    {
        try {
            // Verify signature
            $isValidSignature = $this->verifyCallbackSignature(
                $callbackData['signature'] ?? '',
                $callbackData['merchant_ref'] ?? '',
                $callbackData['total_amount'] ?? 0
            );

            if (!$isValidSignature) {
                return [
                    'success' => false,
                    'message' => 'Invalid signature'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'reference' => $callbackData['reference'] ?? '',
                    'merchant_ref' => $callbackData['merchant_ref'] ?? '',
                    'payment_method' => $callbackData['payment_method'] ?? '',
                    'status' => $callbackData['status'] ?? '',
                    'total_amount' => $callbackData['total_amount'] ?? 0,
                    'fee_merchant' => $callbackData['fee_merchant'] ?? 0,
                    'fee_customer' => $callbackData['fee_customer'] ?? 0,
                    'paid_at' => $callbackData['paid_at'] ?? null,
                ],
                'message' => 'Callback processed successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Tripay process callback error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to process callback: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate unique merchant reference
     */
    public function generateMerchantReference($prefix = 'INV')
    {
        return $prefix . '-' . time() . '-' . mt_rand(1000, 9999);
    }

    /**
     * Format amount for Tripay (remove decimal places for IDR)
     */
    public function formatAmount($amount)
    {
        return (int) $amount; // Tripay expects integer for IDR
    }
}