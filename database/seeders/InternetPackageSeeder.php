<?php

namespace Database\Seeders;

use App\Models\InternetPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternetPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Home 5 Mbps',
                'description' => 'Paket internet untuk rumahan dengan kecepatan 5 Mbps',
                'bandwidth_up' => 1024, // 1 Mbps
                'bandwidth_down' => 5120, // 5 Mbps
                'price' => 150000,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'ip_pool' => 'pool-home',
                'features' => json_encode(['Unlimited Data', 'Fair Usage Policy'])
            ],
            [
                'name' => 'Paket Home 10 Mbps',
                'description' => 'Paket internet untuk rumahan dengan kecepatan 10 Mbps',
                'bandwidth_up' => 2048, // 2 Mbps
                'bandwidth_down' => 10240, // 10 Mbps
                'price' => 250000,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'ip_pool' => 'pool-home',
                'features' => json_encode(['Unlimited Data', 'Gaming Optimized'])
            ],
            [
                'name' => 'Paket Business 20 Mbps',
                'description' => 'Paket internet untuk bisnis dengan kecepatan 20 Mbps',
                'bandwidth_up' => 5120, // 5 Mbps
                'bandwidth_down' => 20480, // 20 Mbps
                'price' => 500000,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'ip_pool' => 'pool-business',
                'features' => json_encode(['Dedicated Support', 'Static IP Available', 'SLA 99.9%'])
            ],
            [
                'name' => 'Paket Enterprise 50 Mbps',
                'description' => 'Paket internet untuk enterprise dengan kecepatan 50 Mbps',
                'bandwidth_up' => 10240, // 10 Mbps
                'bandwidth_down' => 51200, // 50 Mbps
                'price' => 1200000,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'ip_pool' => 'pool-enterprise',
                'features' => json_encode(['24/7 Support', 'Multiple Static IP', 'Priority Support'])
            ],
            [
                'name' => 'Paket Starter 2 Mbps',
                'description' => 'Paket internet murah untuk kebutuhan basic',
                'bandwidth_up' => 512, // 512 Kbps
                'bandwidth_down' => 2048, // 2 Mbps
                'price' => 99000,
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'ip_pool' => 'pool-basic',
                'features' => json_encode(['Basic Support', 'Fair Usage Policy'])
            ]
        ];

        foreach ($packages as $package) {
            InternetPackage::create($package);
        }
    }
}
