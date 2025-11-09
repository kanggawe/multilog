<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Budi Santoso',
                'address' => 'Jl. Merdeka No. 123, Kelurahan Merdeka, Kecamatan Central, Jakarta',
                'phone' => '081234567890',
                'email' => 'budi.santoso@email.com',
                'id_card' => '3171234567890123',
                'status' => 'active',
                'join_date' => '2024-01-15',
                'deposit' => 100000,
                'notes' => 'Customer pertama, pembayaran selalu tepat waktu'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'address' => 'Jl. Sudirman No. 456, Kelurahan Makmur, Kecamatan Selatan, Jakarta',
                'phone' => '081234567891',
                'email' => 'siti.nurhaliza@email.com',
                'id_card' => '3171234567890124',
                'status' => 'active',
                'join_date' => '2024-02-01',
                'deposit' => 150000,
                'notes' => 'Pengguna aktif, sering upgrade paket'
            ],
            [
                'name' => 'Ahmad Wijaya',
                'address' => 'Jl. Thamrin No. 789, Kelurahan Sejahtera, Kecamatan Utara, Jakarta',
                'phone' => '081234567892',
                'email' => 'ahmad.wijaya@email.com',
                'id_card' => '3171234567890125',
                'status' => 'active',
                'join_date' => '2024-02-15',
                'deposit' => 200000,
                'notes' => 'Pelanggan bisnis, membutuhkan support khusus'
            ],
            [
                'name' => 'Dewi Sartika',
                'address' => 'Jl. Gatot Subroto No. 321, Kelurahan Bahagia, Kecamatan Barat, Jakarta',
                'phone' => '081234567893',
                'email' => 'dewi.sartika@email.com',
                'id_card' => '3171234567890126',
                'status' => 'suspended',
                'join_date' => '2024-03-01',
                'deposit' => 50000,
                'notes' => 'Suspended karena tunggakan 2 bulan'
            ],
            [
                'name' => 'Rini Susanti',
                'address' => 'Jl. HR Rasuna Said No. 654, Kelurahan Harmoni, Kecamatan Timur, Jakarta',
                'phone' => '081234567894',
                'email' => 'rini.susanti@email.com',
                'id_card' => '3171234567890127',
                'status' => 'active',
                'join_date' => '2024-03-15',
                'deposit' => 75000,
                'notes' => 'Customer baru, masih dalam masa trial'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
