<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\TripayService;
use Carbon\Carbon;

class PaymentGatewayController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    /**
     * Show payment gateway selection
     */
    public function showPaymentGateway(Invoice $invoice)
    {
        // Get available payment channels from Tripay
        $channels = $this->tripayService->getPaymentChannels();
        
        return view('billing.payments.gateway', [
            'invoice' => $invoice,
            'channels' => $channels['success'] ? $channels['data'] : [],
            'error' => $channels['success'] ? null : $channels['message']
        ]);
    }

    /**
     * Process payment via gateway
     */
    public function processPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Generate merchant reference
            $merchantRef = $this->tripayService->generateMerchantReference('INV');
            
            // Create payment record first
            $payment = Payment::create([
                'payment_number' => '', // Will be auto-generated
                'merchant_ref' => $merchantRef,
                'customer_id' => $invoice->customer_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'method' => $request->payment_method,
                'payment_gateway' => 'tripay',
                'gateway_status' => 'PENDING',
                'payment_date' => now(),
                'expired_at' => now()->addHours(config('tripay.expiry_time', 24)),
                'notes' => 'Payment via Tripay Gateway - ' . $request->payment_method,
                'received_by' => auth()->id()
            ]);

            // Prepare payment data for Tripay
            $paymentData = [
                'method' => $request->payment_method,
                'merchant_ref' => $merchantRef,
                'amount' => $this->tripayService->formatAmount($invoice->total_amount),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'order_items' => [
                    [
                        'sku' => $invoice->invoice_number,
                        'name' => $invoice->description ?: 'Internet Billing Payment',
                        'price' => $this->tripayService->formatAmount($invoice->total_amount),
                        'quantity' => 1,
                        'subtotal' => $this->tripayService->formatAmount($invoice->total_amount)
                    ]
                ]
            ];

            // Create transaction via Tripay
            $result = $this->tripayService->createTransaction($paymentData);

            if ($result['success']) {
                $tripayData = $result['data'];
                
                // Update payment with Tripay response
                $payment->update([
                    'tripay_reference' => $tripayData['reference'],
                    'payment_url' => $tripayData['checkout_url'] ?? null,
                    'fee_merchant' => $tripayData['fee_merchant'] ?? 0,
                    'fee_customer' => $tripayData['fee_customer'] ?? 0,
                    'gateway_response' => $tripayData
                ]);

                DB::commit();

                // Redirect to payment URL
                return redirect($tripayData['checkout_url']);
                
            } else {
                DB::rollback();
                
                return back()->withErrors([
                    'payment_error' => $result['message']
                ])->withInput();
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment gateway process error: ' . $e->getMessage());
            
            return back()->withErrors([
                'payment_error' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Handle payment success return
     */
    public function paymentSuccess(Request $request)
    {
        $reference = $request->get('reference');
        $merchantRef = $request->get('merchant_ref');
        
        if (!$reference && !$merchantRef) {
            return redirect()->route('billing.invoices.index')
                           ->with('error', 'Invalid payment reference');
        }

        // Find payment by reference or merchant_ref
        $payment = Payment::where('tripay_reference', $reference)
                         ->orWhere('merchant_ref', $merchantRef)
                         ->first();

        if (!$payment) {
            return redirect()->route('billing.invoices.index')
                           ->with('error', 'Payment not found');
        }

        // Check payment status from Tripay
        if ($payment->tripay_reference) {
            $result = $this->tripayService->getTransactionDetail($payment->tripay_reference);
            
            if ($result['success']) {
                $this->updatePaymentFromTripayData($payment, $result['data']);
            }
        }

        return view('billing.payments.success', compact('payment'));
    }

    /**
     * Handle Tripay callback/webhook
     */
    public function callback(Request $request)
    {
        Log::info('Tripay callback received', $request->all());

        try {
            $callbackData = $request->all();
            $result = $this->tripayService->processCallback($callbackData);

            if (!$result['success']) {
                Log::error('Invalid callback signature', $callbackData);
                return response('Invalid signature', 400);
            }

            $data = $result['data'];
            
            // Find payment by merchant_ref or tripay_reference
            $payment = Payment::where('merchant_ref', $data['merchant_ref'])
                            ->orWhere('tripay_reference', $data['reference'])
                            ->first();

            if (!$payment) {
                Log::error('Payment not found for callback', $data);
                return response('Payment not found', 404);
            }

            // Update payment status
            $this->updatePaymentFromTripayData($payment, $data);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Callback processing error: ' . $e->getMessage(), $request->all());
            return response('Internal server error', 500);
        }
    }

    /**
     * Update payment from Tripay data
     */
    private function updatePaymentFromTripayData(Payment $payment, array $tripayData)
    {
        $updateData = [
            'tripay_reference' => $tripayData['reference'] ?? $payment->tripay_reference,
            'gateway_status' => $tripayData['status'] ?? $payment->gateway_status,
            'gateway_response' => array_merge($payment->gateway_response ?? [], $tripayData),
        ];

        // If payment is successful, mark as paid
        if (in_array($tripayData['status'] ?? '', ['PAID', 'SETTLED'])) {
            $updateData['paid_at'] = isset($tripayData['paid_at']) 
                ? Carbon::createFromTimestamp($tripayData['paid_at']) 
                : now();
        }

        $payment->update($updateData);

        // Update invoice if payment is successful
        if ($payment->isPaid() && $payment->invoice) {
            $payment->invoice->markAsPaid();
        }

        Log::info('Payment updated from Tripay data', [
            'payment_id' => $payment->id,
            'status' => $tripayData['status'] ?? 'unknown'
        ]);
    }

    /**
     * Check payment status
     */
    public function checkStatus(Payment $payment)
    {
        if (!$payment->tripay_reference) {
            return response()->json([
                'success' => false,
                'message' => 'No Tripay reference found'
            ]);
        }

        $result = $this->tripayService->getTransactionDetail($payment->tripay_reference);

        if ($result['success']) {
            $this->updatePaymentFromTripayData($payment, $result['data']);
            $payment->refresh();

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => $payment->gateway_status,
                    'status_text' => $payment->getStatusText(),
                    'is_paid' => $payment->isPaid(),
                    'paid_at' => $payment->paid_at?->format('d/m/Y H:i')
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
}
