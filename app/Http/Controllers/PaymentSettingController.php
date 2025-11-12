<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentSettingController extends Controller
{
    /**
     * Display payment gateway settings
     */
    public function index()
    {
        $tripay = PaymentSetting::where('gateway_name', 'tripay')->first();
        
        return view('admin.payment-settings.index', compact('tripay'));
    }

    /**
     * Show form to edit Tripay settings
     */
    public function editTripay()
    {
        $tripay = PaymentSetting::where('gateway_name', 'tripay')->first();
        
        return view('admin.payment-settings.tripay', compact('tripay'));
    }

    /**
     * Update Tripay settings
     */
    public function updateTripay(Request $request)
    {
        $validated = $request->validate([
            'environment' => 'required|in:sandbox,production',
            'api_key' => 'required|string',
            'private_key' => 'required|string',
            'merchant_code' => 'required|string',
            'callback_url' => 'nullable|url',
            'is_active' => 'boolean'
        ]);

        try {
            $tripay = PaymentSetting::updateOrCreate(
                ['gateway_name' => 'tripay'],
                [
                    'environment' => $validated['environment'],
                    'api_key' => $validated['api_key'],
                    'private_key' => $validated['private_key'],
                    'merchant_code' => $validated['merchant_code'],
                    'callback_url' => $validated['callback_url'] ?? route('tripay.callback'),
                    'is_active' => $request->has('is_active')
                ]
            );

            return redirect()
                ->route('admin.payment-settings.index')
                ->with('success', 'Tripay settings updated successfully');

        } catch (\Exception $e) {
            Log::error('Failed to update Tripay settings: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Test Tripay connection
     */
    public function testTripay()
    {
        try {
            $tripayService = new TripayService();
            $result = $tripayService->getPaymentChannels();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful! Found ' . count($result['data']) . ' payment channels.',
                    'channels' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle gateway status
     */
    public function toggleStatus($gateway)
    {
        try {
            $setting = PaymentSetting::where('gateway_name', $gateway)->firstOrFail();
            $setting->is_active = !$setting->is_active;
            $setting->save();

            return response()->json([
                'success' => true,
                'is_active' => $setting->is_active,
                'message' => $gateway . ' gateway ' . ($setting->is_active ? 'activated' : 'deactivated')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }
}

