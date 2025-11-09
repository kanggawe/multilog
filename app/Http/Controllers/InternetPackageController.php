<?php

namespace App\Http\Controllers;

use App\Models\InternetPackage;
use Illuminate\Http\Request;

class InternetPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InternetPackage::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by active status
        if ($request->has('is_active') && $request->get('is_active') !== '') {
            $query->where('is_active', $request->get('is_active'));
        }

        $packages = $query->paginate(15);

        return view('billing.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('billing.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bandwidth_up' => 'required|integer|min:1',
            'bandwidth_down' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'is_active' => 'boolean',
            'duration_days' => 'required|integer|min:1',
            'ip_pool' => 'nullable|string|max:255',
            'features' => 'nullable|string'
        ]);

        // Convert features from string to array if provided
        if ($validated['features']) {
            $validated['features'] = json_decode($validated['features'], true) ?: [];
        }

        InternetPackage::create($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Paket internet berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InternetPackage $package)
    {
        $package->load([
            'subscriptions.customer',
            'pppoeAccounts.customer'
        ]);

        return view('billing.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InternetPackage $package)
    {
        return view('billing.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InternetPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bandwidth_up' => 'required|integer|min:1',
            'bandwidth_down' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'is_active' => 'boolean',
            'duration_days' => 'required|integer|min:1',
            'ip_pool' => 'nullable|string|max:255',
            'features' => 'nullable|string'
        ]);

        // Convert features from string to array if provided
        if ($validated['features']) {
            $validated['features'] = json_decode($validated['features'], true) ?: [];
        }

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Paket internet berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InternetPackage $package)
    {
        try {
            $package->delete();
            return redirect()->route('packages.index')
                ->with('success', 'Paket internet berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('packages.index')
                ->with('error', 'Tidak dapat menghapus paket. Paket sedang digunakan oleh customer.');
        }
    }
}
