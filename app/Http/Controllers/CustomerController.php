<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::with('createdBy');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('status', $request->get('status'));
        }

        $customers = $query->paginate(15);

        return view('billing.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('billing.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'id_card' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'join_date' => 'required|date',
            'deposit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $validated['created_by'] = Auth::id();

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'subscriptions.internetPackage',
            'pppoeAccounts.internetPackage',
            'invoices' => function($query) {
                $query->latest()->limit(10);
            },
            'payments' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        return view('billing.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('billing.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'id_card' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'join_date' => 'required|date',
            'deposit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')
                ->with('success', 'Customer berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')
                ->with('error', 'Tidak dapat menghapus customer. Customer memiliki data terkait.');
        }
    }
}
