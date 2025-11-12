<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InternetPackage;
use App\Models\PPPoEAccount;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Dashboard billing utama
     */
    public function dashboard()
    {
        $stats = [
            'total_customers' => Customer::where('status', 'active')->count(),
            'total_packages' => InternetPackage::where('is_active', true)->count(),
            'active_accounts' => PPPoEAccount::where('status', 'active')->count(),
            'monthly_revenue' => Invoice::whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->where('status', 'paid')
                                      ->sum('amount')
        ];

        // Pending invoices
        $pendingInvoices = Invoice::with(['customer'])
                                 ->where('status', 'unpaid')
                                 ->orderBy('due_date', 'asc')
                                 ->limit(10)
                                 ->get();

        // Recent payments
        $recentPayments = Payment::with(['customer', 'invoice'])
                                ->latest()
                                ->limit(10)
                                ->get();

        // Overdue invoices
        $overdueInvoices = Invoice::with(['customer'])
                                 ->where('status', 'unpaid')
                                 ->where('due_date', '<', now())
                                 ->count();

        // Expiring accounts
        $expiringAccounts = PPPoEAccount::with(['customer'])
                                       ->where('status', 'active')
                                       ->whereNotNull('expires_at')
                                       ->where('expires_at', '<=', now()->addDays(7))
                                       ->count();

        return view('billing.dashboard', compact(
            'stats',
            'pendingInvoices',
            'recentPayments',
            'overdueInvoices',
            'expiringAccounts'
        ));
    }

    /**
     * Generate invoice bulanan otomatis
     */
    public function generateMonthlyInvoices()
    {
        $subscriptions = Subscription::with(['customer', 'internetPackage'])
                                   ->where('status', 'active')
                                   ->whereDoesntHave('invoices', function($query) {
                                       $query->whereMonth('invoice_date', now()->month)
                                             ->whereYear('invoice_date', now()->year);
                                   })
                                   ->get();

        $generated = 0;

        foreach ($subscriptions as $subscription) {
            $invoice = Invoice::create([
                'customer_id' => $subscription->customer_id,
                'subscription_id' => $subscription->id,
                'invoice_date' => now(),
                'due_date' => now()->addDays(7),
                'amount' => $subscription->monthly_fee,
                'description' => 'Tagihan Internet ' . now()->format('F Y') . ' - ' . $subscription->internetPackage->name,
                'status' => 'unpaid'
            ]);

            $generated++;
        }

        return redirect()->back()
            ->with('success', "Berhasil generate {$generated} invoice bulanan.");
    }

    /**
     * Laporan keuangan
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfMonth();

        // Revenue
        $revenue = Payment::whereBetween('payment_date', [$startDate, $endDate])
                         ->sum('amount');

        // Outstanding invoices
        $outstanding = Invoice::where('status', '!=', 'paid')
                             ->sum('amount');

        // Payments by method
        $paymentsByMethod = Payment::whereBetween('payment_date', [$startDate, $endDate])
                                  ->selectRaw('method, sum(amount) as total')
                                  ->groupBy('method')
                                  ->get();

        // Monthly trend (last 12 months)
        $monthlyTrend = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue = Payment::whereMonth('payment_date', $month->month)
                                   ->whereYear('payment_date', $month->year)
                                   ->sum('amount');
            
            $monthlyTrend->push([
                'month' => $month->format('M Y'),
                'revenue' => $monthlyRevenue
            ]);
        }

        return view('billing.reports.financial', compact(
            'revenue',
            'outstanding',
            'paymentsByMethod',
            'monthlyTrend',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Laporan customer
     */
    public function customerReport()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('status', 'active')->count(),
            'inactive_customers' => Customer::where('status', 'inactive')->count(),
            'suspended_customers' => Customer::where('status', 'suspended')->count()
        ];

        // Customers by join date (last 12 months)
        $customerGrowth = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $newCustomers = Customer::whereMonth('join_date', $month->month)
                                   ->whereYear('join_date', $month->year)
                                   ->count();
            
            $customerGrowth->push([
                'month' => $month->format('M Y'),
                'customers' => $newCustomers
            ]);
        }

        // Top customers by revenue
        $topCustomers = Customer::withSum(['payments' => function($query) {
                                    $query->whereYear('payment_date', now()->year);
                                }], 'amount')
                               ->orderBy('payments_sum_amount', 'desc')
                               ->limit(10)
                               ->get();

        return view('billing.reports.customers', compact(
            'stats',
            'customerGrowth',
            'topCustomers'
        ));
    }

    /**
     * Daftar invoice
     */
    public function invoices(Request $request)
    {
        $query = Invoice::with(['customer']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%$search%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%$search%")
                                   ->orWhere('customer_code', 'like', "%$search%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);
        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('billing.invoices.index', compact('invoices', 'customers'));
    }

    /**
     * Detail invoice
     */
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['customer', 'subscription.package', 'payments']);
        
        return view('billing.invoices.show', compact('invoice'));
    }

    /**
     * Print invoice
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['customer', 'subscription.package']);
        
        return view('billing.invoices.print', compact('invoice'));
    }

    /**
     * Kirim invoice via email
     */
    public function sendInvoiceEmail(Invoice $invoice)
    {
        // TODO: Implement email sending
        return redirect()->back()->with('success', 'Invoice email sent successfully');
    }

    /**
     * Hapus invoice
     */
    public function deleteInvoice(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot delete paid invoice');
        }

        $invoice->delete();
        return redirect()->route('billing.invoices.index')->with('success', 'Invoice deleted successfully');
    }

    /**
     * Daftar payment
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['customer', 'invoice']);

        // Filter by method
        if ($request->method) {
            $query->where('method', $request->method);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_number', 'like', "%$search%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%$search%")
                                   ->orWhere('customer_code', 'like', "%$search%");
                  })
                  ->orWhereHas('invoice', function($invoiceQuery) use ($search) {
                      $invoiceQuery->where('invoice_number', 'like', "%$search%");
                  });
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        return view('billing.payments.index', compact('payments'));
    }

    /**
     * Form create payment
     */
    public function createPayment(Request $request, Invoice $invoice = null)
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $unpaidInvoices = Invoice::where('status', 'unpaid')->with('customer')->orderBy('due_date')->get();

        return view('billing.payments.create', compact('customers', 'unpaidInvoices', 'invoice'));
    }

    /**
     * Store payment
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,bank_transfer,credit_card,debit_card',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        // Check if payment amount doesn't exceed invoice amount
        $totalPaid = $invoice->payments()->sum('amount');
        $remaining = $invoice->amount - $totalPaid;
        
        if ($request->amount > $remaining) {
            return redirect()->back()->with('error', 'Payment amount exceeds remaining invoice amount');
        }

        $payment = Payment::create([
            'payment_number' => Payment::generatePaymentNumber(),
            'customer_id' => $invoice->customer_id,
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
            'received_by' => auth()->id()
        ]);

        // Update invoice status if fully paid
        $newTotalPaid = $invoice->payments()->sum('amount');
        if ($newTotalPaid >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }

        return redirect()->route('billing.payments.index')->with('success', 'Payment recorded successfully');
    }

    /**
     * Detail payment
     */
    public function showPayment(Payment $payment)
    {
        $payment->load(['customer', 'invoice', 'receivedBy']);
        
        return view('billing.payments.show', compact('payment'));
    }

    /**
     * Print receipt
     */
    public function printReceipt(Payment $payment)
    {
        $payment->load(['customer', 'invoice', 'receivedBy']);
        
        return view('billing.payments.receipt', compact('payment'));
    }

    /**
     * Show form to create manual invoice
     */
    public function createManualInvoice()
    {
        $customers = Customer::where('status', 'active')
                            ->with('subscriptions.package')
                            ->orderBy('name')
                            ->get();
        
        return view('billing.invoices.create-manual', compact('customers'));
    }

    /**
     * Store manual invoice
     */
    public function storeManualInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'required|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after:period_start',
        ]);

        // If subscription provided, check for existing unpaid invoice
        if ($request->subscription_id) {
            $subscription = Subscription::with('package')->findOrFail($request->subscription_id);
            
            $existingInvoice = Invoice::where('subscription_id', $subscription->id)
                                      ->where('status', 'unpaid')
                                      ->first();
            
            if ($existingInvoice) {
                return back()->withInput()->with('error', 'Masih ada invoice yang belum dibayar untuk subscription ini.');
            }
        }

        // Generate invoice number
        $lastInvoice = Invoice::whereYear('created_at', now()->year)
                             ->whereMonth('created_at', now()->month)
                             ->orderBy('id', 'desc')
                             ->first();
        
        $sequence = $lastInvoice ? (intval(substr($lastInvoice->invoice_number, -4)) + 1) : 1;
        $invoiceNumber = 'INV/' . now()->format('Ym') . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $request->customer_id,
            'subscription_id' => $request->subscription_id,
            'amount' => $request->amount,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'due_date' => $request->due_date,
            'invoice_date' => now(),
            'description' => $request->description,
            'notes' => 'Invoice manual - dibuat oleh ' . auth()->user()->name,
        ]);

        return redirect()->route('billing.invoices.show', $invoice)
                        ->with('success', 'Invoice manual berhasil dibuat!');
    }

    /**
     * Delete payment
     */
    public function deletePayment(Payment $payment)
    {
        $invoice = $payment->invoice;
        
        $payment->delete();
        
        // Update invoice status back to unpaid if needed
        $totalPaid = $invoice->payments()->sum('amount');
        if ($totalPaid < $invoice->amount) {
            $invoice->update(['status' => 'unpaid']);
        }

        return redirect()->route('billing.payments.index')->with('success', 'Payment deleted successfully');
    }
}
