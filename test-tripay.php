<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PaymentSetting;
use App\Services\TripayService;

echo "=== Testing Tripay Configuration ===\n\n";

// Test 1: Get config from database
echo "1. Getting config from database...\n";
$config = PaymentSetting::getTripayConfig();
echo "Environment: " . ($config['environment'] ?? 'NOT SET') . "\n";
echo "API Key: " . (empty($config['api_key']) ? 'EMPTY' : substr($config['api_key'], 0, 10) . '***') . "\n";
echo "Private Key: " . (empty($config['private_key']) ? 'EMPTY' : substr($config['private_key'], 0, 10) . '***') . "\n";
echo "Merchant Code: " . ($config['merchant_code'] ?? 'NOT SET') . "\n";
echo "\n";

// Test 2: Initialize TripayService
echo "2. Initializing TripayService...\n";
try {
    $service = new TripayService();
    echo "✓ Service initialized successfully\n\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 3: Get payment channels
echo "3. Testing API connection...\n";
try {
    $result = $service->getPaymentChannels();
    
    echo "Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
    echo "Message: " . $result['message'] . "\n";
    echo "Channels: " . count($result['data']) . "\n";
    
    if ($result['success'] && !empty($result['data'])) {
        echo "\nAvailable channels:\n";
        foreach (array_slice($result['data'], 0, 5) as $channel) {
            echo "  - " . ($channel['name'] ?? 'Unknown') . " (" . ($channel['code'] ?? 'N/A') . ")\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
