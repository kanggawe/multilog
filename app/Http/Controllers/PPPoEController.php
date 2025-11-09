<?php

namespace App\Http\Controllers;

use App\Models\PPPoEAccount;
use App\Models\Customer;
use App\Models\InternetPackage;
use Illuminate\Http\Request;

class PPPoEController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PPPoEAccount::with(['customer', 'internetPackage']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('customer_code', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('status', $request->get('status'));
        }

        $accounts = $query->paginate(15);

        return view('billing.pppoe.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $packages = InternetPackage::where('is_active', true)->orderBy('name')->get();

        return view('billing.pppoe.create', compact('customers', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'internet_package_id' => 'required|exists:internet_packages,id',
            'username' => 'nullable|string|max:255|unique:p_p_po_e_accounts,username',
            'password' => 'nullable|string|max:255',
            'static_ip' => 'nullable|ip',
            'profile_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended,expired',
            'expires_at' => 'nullable|datetime',
            'notes' => 'nullable|string'
        ]);

        $account = PPPoEAccount::create($validated);

        // TODO: Sync with Mikrotik RouterOS API
        // $this->syncToMikrotik($account);

        return redirect()->route('pppoe.index')
            ->with('success', 'Akun PPPoE berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PPPoEAccount $pppoe)
    {
        $pppoe->load(['customer', 'internetPackage']);

        return view('billing.pppoe.show', compact('pppoe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PPPoEAccount $pppoe)
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $packages = InternetPackage::where('is_active', true)->orderBy('name')->get();

        return view('billing.pppoe.edit', compact('pppoe', 'customers', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PPPoEAccount $pppoe)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'internet_package_id' => 'required|exists:internet_packages,id',
            'username' => 'required|string|max:255|unique:p_p_po_e_accounts,username,' . $pppoe->id,
            'password' => 'nullable|string|max:255',
            'static_ip' => 'nullable|ip',
            'profile_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended,expired',
            'expires_at' => 'nullable|datetime',
            'notes' => 'nullable|string'
        ]);

        // Don't update password if empty
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $pppoe->update($validated);

        // TODO: Sync with Mikrotik RouterOS API
        // $this->syncToMikrotik($pppoe);

        return redirect()->route('pppoe.index')
            ->with('success', 'Akun PPPoE berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PPPoEAccount $pppoe)
    {
        try {
            // TODO: Remove from Mikrotik RouterOS API first
            // $this->removeFromMikrotik($pppoe);

            $pppoe->delete();

            return redirect()->route('pppoe.index')
                ->with('success', 'Akun PPPoE berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pppoe.index')
                ->with('error', 'Gagal menghapus akun PPPoE: ' . $e->getMessage());
        }
    }

    /**
     * Enable PPPoE account
     */
    public function enable(PPPoEAccount $pppoe)
    {
        $pppoe->update(['status' => 'active']);

        // TODO: Enable in Mikrotik RouterOS API
        // $this->enableInMikrotik($pppoe);

        return redirect()->back()
            ->with('success', 'Akun PPPoE berhasil diaktifkan.');
    }

    /**
     * Disable PPPoE account
     */
    public function disable(PPPoEAccount $pppoe)
    {
        $pppoe->update(['status' => 'suspended']);

        // TODO: Disable in Mikrotik RouterOS API
        // $this->disableInMikrotik($pppoe);

        return redirect()->back()
            ->with('success', 'Akun PPPoE berhasil dinonaktifkan.');
    }

    /**
     * Sync account to Mikrotik RouterOS
     * 
     * TODO: Implement Mikrotik RouterOS API integration
     * You can use library like: routeros/routeros-api-php
     * 
     * composer require routeros/routeros-api-php
     */
    // private function syncToMikrotik(PPPoEAccount $account)
    // {
    //     // Implementation example:
    //     // $client = new RouterosAPI();
    //     // $client->connect('192.168.1.1', 'admin', 'password');
    //     // 
    //     // $client->comm('/ppp/secret/add', [
    //     //     'name' => $account->username,
    //     //     'password' => $account->password,
    //     //     'profile' => $account->profile_name,
    //     //     'disabled' => $account->status !== 'active' ? 'yes' : 'no'
    //     // ]);
    //     // 
    //     // $client->disconnect();
    // }
}
