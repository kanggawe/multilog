<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentSetting extends Model
{
    protected $fillable = [
        'gateway_name',
        'environment',
        'api_key',
        'private_key',
        'merchant_code',
        'callback_url',
        'is_active',
        'config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    /**
     * Encrypt sensitive data before saving
     */
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setPrivateKeyAttribute($value)
    {
        $this->attributes['private_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setMerchantCodeAttribute($value)
    {
        $this->attributes['merchant_code'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt sensitive data when accessing
     */
    public function getApiKeyAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPrivateKeyAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMerchantCodeAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get active payment gateway by name
     */
    public static function getActiveGateway($gatewayName)
    {
        return self::where('gateway_name', $gatewayName)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get Tripay configuration
     */
    public static function getTripayConfig()
    {
        $setting = self::where('gateway_name', 'tripay')->first();
        
        if ($setting) {
            return [
                'api_key' => $setting->api_key,
                'private_key' => $setting->private_key,
                'merchant_code' => $setting->merchant_code,
                'environment' => $setting->environment,
                'callback_url' => $setting->callback_url,
            ];
        }

        // Fallback to .env if no database setting
        return [
            'api_key' => env('TRIPAY_API_KEY'),
            'private_key' => env('TRIPAY_PRIVATE_KEY'),
            'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
            'environment' => env('TRIPAY_ENVIRONMENT', 'sandbox'),
            'callback_url' => env('TRIPAY_CALLBACK_URL'),
        ];
    }
}

