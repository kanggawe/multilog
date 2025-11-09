<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = \App\Models\Customer::all();
        $packages = \App\Models\InternetPackage::all();

        if ($customers->isEmpty() || $packages->isEmpty()) {
            $this->command->error('Please run Customer and InternetPackage seeders first');
            return;
        }

        // Create subscriptions for each customer
        foreach ($customers as $customer) {
            $package = $packages->random();
            
            // Create subscription
            $subscription = \App\Models\Subscription::create([
                'customer_id' => $customer->id,
                'internet_package_id' => $package->id,
                'start_date' => now()->subMonths(rand(1, 6)),
                'end_date' => now()->addYear(),
                'monthly_fee' => $package->price,
                'status' => 'active'
            ]);

            // Create invoices for the last 3 months
            for ($i = 2; $i >= 0; $i--) {
                $invoiceDate = now()->subMonths($i)->startOfMonth();
                $dueDate = $invoiceDate->copy()->addDays(15);
                
                // Generate unique invoice number manually
                $year = $invoiceDate->format('Y');
                $month = $invoiceDate->format('m');
                $invoiceCount = \App\Models\Invoice::count() + 1;
                $invoiceNumber = 'INV' . $year . $month . str_pad($invoiceCount, 4, '0', STR_PAD_LEFT);
                
                $invoice = \App\Models\Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'customer_id' => $customer->id,
                    'subscription_id' => $subscription->id,
                    'invoice_date' => $invoiceDate,
                    'amount' => $package->price,
                    'due_date' => $dueDate,
                    'status' => $i == 0 ? 'unpaid' : (rand(1, 10) > 3 ? 'paid' : 'unpaid'),
                    'description' => 'Monthly internet subscription - ' . $package->name,
                    'created_at' => $invoiceDate,
                    'updated_at' => $invoiceDate
                ]);

                // Create payment for paid invoices
                if ($invoice->status === 'paid') {
                    $paymentDate = $invoiceDate->copy()->addDays(rand(1, 10));
                    $paymentCount = \App\Models\Payment::count() + 1;
                    $paymentNumber = 'PAY' . $paymentDate->format('Ym') . str_pad($paymentCount, 4, '0', STR_PAD_LEFT);
                    
                    \App\Models\Payment::create([
                        'payment_number' => $paymentNumber,
                        'customer_id' => $customer->id,
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->amount,
                        'method' => collect(['cash', 'bank_transfer', 'credit_card'])->random(),
                        'payment_date' => $paymentDate,
                        'received_by' => 1, // Admin user
                        'created_at' => $paymentDate,
                        'updated_at' => $paymentDate,
                    ]);
                }
            }
        }

        $this->command->info('Created invoices and payments for ' . $customers->count() . ' customers');
    }
}
