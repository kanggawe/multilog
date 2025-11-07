<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        return view('dashboard', [
            'user' => $user,
            'page' => 'dashboard',
            'showBreadcrumb' => false,
        ]);
    }
}
