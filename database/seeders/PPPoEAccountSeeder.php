<?php

namespace Database\Seeders;

use App\Models\PPPoEAccount;
use App\Models\Customer;
use App\Models\InternetPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PPPoEAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customers and packages
        $customers = Customer::all();
        $packages = InternetPackage::all();

        if ($customers->count() == 0 || $packages->count() == 0) {
            return;
        }

        $accounts = [
            [
                'customer_id' => $customers->first()->id,
                'internet_package_id' => $packages->where('name', 'like', '%Home 5%')->first()?->id ?? $packages->first()->id,
                'username' => 'usr0001',
                'password' => 'pass123',
                'status' => 'active',
                'last_login' => Carbon::now()->subHours(2),
                'last_ip' => '192.168.100.10',
                'bytes_in' => 2147483648, // 2 GB
                'bytes_out' => 536870912, // 512 MB
                'profile_name' => 'paket-home-5mbps',
                'notes' => 'Account aktif, penggunaan normal'
            ],
            [
                'customer_id' => $customers->skip(1)->first()->id,
                'internet_package_id' => $packages->where('name', 'like', '%Home 10%')->first()?->id ?? $packages->skip(1)->first()->id,
                'username' => 'usr0002',
                'password' => 'mypass456',
                'status' => 'active',
                'last_login' => Carbon::now()->subMinutes(30),
                'last_ip' => '192.168.100.11',
                'bytes_in' => 5368709120, // 5 GB
                'bytes_out' => 1073741824, // 1 GB
                'profile_name' => 'paket-home-10mbps',
                'static_ip' => '192.168.100.50',
                'notes' => 'Customer bisnis dengan static IP'
            ],
            [
                'customer_id' => $customers->skip(2)->first()->id,
                'internet_package_id' => $packages->where('name', 'like', '%Business%')->first()?->id ?? $packages->skip(2)->first()->id,
                'username' => 'usr0003',
                'password' => 'bizpass789',
                'status' => 'active',
                'last_login' => Carbon::now()->subDays(1),
                'last_ip' => '192.168.100.12',
                'bytes_in' => 10737418240, // 10 GB
                'bytes_out' => 2147483648, // 2 GB
                'profile_name' => 'paket-business-20mbps',
                'static_ip' => '192.168.100.100',
                'expires_at' => Carbon::now()->addMonths(1),
                'notes' => 'Account bisnis dengan bandwidth tinggi'
            ],
            [
                'customer_id' => $customers->skip(3)->first()->id,
                'internet_package_id' => $packages->where('name', 'like', '%Starter%')->first()?->id ?? $packages->last()->id,
                'username' => 'usr0004',
                'password' => 'trial123',
                'status' => 'suspended',
                'last_login' => Carbon::now()->subDays(5),
                'last_ip' => '192.168.100.13',
                'bytes_in' => 1073741824, // 1 GB
                'bytes_out' => 268435456, // 256 MB
                'profile_name' => 'paket-starter-2mbps',
                'expires_at' => Carbon::now()->subDays(2), // Already expired
                'notes' => 'Suspended karena tunggakan pembayaran'
            ]
        ];

        foreach ($accounts as $accountData) {
            PPPoEAccount::create($accountData);
        }
    }
}
