<?php

namespace App\Services;

use App\Models\PaymentSetting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $environment;

    public function __construct()
    {
        // Load configuration from database or .env
        $config = PaymentSetting::getTripayConfig();
        
        $this->environment = $config['environment'] ?? 'sandbox';
        $this->apiKey = $config['api_key'];
        $this->privateKey = $config['private_key'];
        $this->merchantCode = $config['merchant_code'];
        
        // Set base URL based on environment
        $this->baseUrl = $this->environment === 'production' 
            ? 'https://tripay.co.id/api/'
            : 'https://tripay.co.id/api-sandbox/';
        
        // Check if credentials are set
        if (empty($this->apiKey) || empty($this->privateKey) || empty($this->merchantCode)) {
            Log::warning('Tripay credentials not configured. Please configure payment settings in the admin panel.');
        }
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
            'verify' => false, // Bypass SSL verification for local development
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
            // Check if credentials are configured
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'data' => [],
                    'message' => 'Tripay API Key not configured. Please configure payment settings in the admin panel.'
                ];
            }

            $url = $this->baseUrl . 'merchant/payment-channel';
            
            Log::info('Tripay API Request', [
                'full_url' => $url,
                'api_key' => substr($this->apiKey, 0, 10) . '***'
            ]);

            $response = $this->client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'success' => $data['success'] ?? false,
                'data' => $data['data'] ?? [],
                'message' => $data['message'] ?? 'Success'
            ];
        } catch (RequestException $e) {
            $errorMessage = $e->getMessage();
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
            
            // Extract more specific error from response if available
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($responseBody, true);
                $errorMessage = $errorData['message'] ?? $errorMessage;
            }
            
            Log::error('Tripay get payment channels error', [
                'status' => $statusCode,
                'message' => $errorMessage,
                'url' => $this->baseUrl . 'merchant/payment-channel'
            ]);
            
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to get payment channels (HTTP ' . $statusCode . '). Please check your Tripay configuration.'
            ];
        }
    }

    /**
     * Create a new transaction
     */
    public function createTransaction($paymentData)
    {
        try {
            // Get callback URL from database or use default
            $setting = PaymentSetting::getActiveGateway('tripay');
            $callbackUrl = $setting ? $setting->callback_url : route('tripay.callback');
            $returnUrl = $paymentData['return_url'] ?? route('dashboard');
            $expiryTime = $paymentData['expired_time'] ?? (time() + (24 * 3600)); // Default 24 hours
            
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
                'return_url' => $returnUrl,
                'expired_time' => $expiryTime,
                'signature' => $signature,
                'callback_url' => $callbackUrl
            ];

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