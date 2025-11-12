<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('billing.dashboard');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Password Reset routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/password-reset-link-sent', [ForgotPasswordController::class, 'resetLinkSent'])->name('password.reset-link-sent');
Route::get('/password-reset-success', [ResetPasswordController::class, 'resetSuccess'])->name('password.reset-success');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes (standard Laravel format)
    Route::get('/profile', [AccountController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile', [AccountController::class, 'destroy'])->name('profile.destroy');
    
    // Account routes
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::get('/settings', [AccountController::class, 'settings'])->name('settings');
        Route::post('/settings', [AccountController::class, 'updateSettings'])->name('settings.update');
        Route::get('/edit-profile', [AccountController::class, 'editProfile'])->name('edit-profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/change-password', [AccountController::class, 'changePassword'])->name('password.change');
        Route::put('/change-password', [AccountController::class, 'updatePassword'])->name('password.change.update');
        Route::get('/help', [AccountController::class, 'help'])->name('help');
    });
    
    // Billing routes
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\BillingController::class, 'dashboard'])->name('dashboard');
        Route::post('/generate-invoices', [\App\Http\Controllers\BillingController::class, 'generateMonthlyInvoices'])->name('generate.invoices');
        Route::get('/reports/financial', [\App\Http\Controllers\BillingController::class, 'financialReport'])->name('reports.financial');
        Route::get('/reports/customers', [\App\Http\Controllers\BillingController::class, 'customerReport'])->name('reports.customers');
        
        // Invoice management routes
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BillingController::class, 'invoices'])->name('index');
            Route::get('/create-manual', [\App\Http\Controllers\BillingController::class, 'createManualInvoice'])->name('create-manual');
            Route::post('/create-manual', [\App\Http\Controllers\BillingController::class, 'storeManualInvoice'])->name('store-manual');
            Route::get('/{invoice}', [\App\Http\Controllers\BillingController::class, 'showInvoice'])->name('show');
            Route::get('/{invoice}/print', [\App\Http\Controllers\BillingController::class, 'printInvoice'])->name('print');
            Route::post('/{invoice}/send-email', [\App\Http\Controllers\BillingController::class, 'sendInvoiceEmail'])->name('send-email');
            Route::delete('/{invoice}', [\App\Http\Controllers\BillingController::class, 'deleteInvoice'])->name('destroy');
        });
        
        // Payment management routes  
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BillingController::class, 'payments'])->name('index');
            Route::get('/create/{invoice?}', [\App\Http\Controllers\BillingController::class, 'createPayment'])->name('create');
            Route::post('/', [\App\Http\Controllers\BillingController::class, 'storePayment'])->name('store');
            Route::get('/{payment}', [\App\Http\Controllers\BillingController::class, 'showPayment'])->name('show');
            Route::get('/{payment}/receipt', [\App\Http\Controllers\BillingController::class, 'printReceipt'])->name('receipt');
            Route::delete('/{payment}', [\App\Http\Controllers\BillingController::class, 'deletePayment'])->name('destroy');
            
            // Payment Gateway routes
            Route::get('/{invoice}/gateway', [\App\Http\Controllers\PaymentGatewayController::class, 'showPaymentGateway'])->name('gateway');
            Route::post('/{invoice}/gateway', [\App\Http\Controllers\PaymentGatewayController::class, 'processPayment'])->name('gateway.process');
            Route::get('/success', [\App\Http\Controllers\PaymentGatewayController::class, 'paymentSuccess'])->name('success');
            Route::get('/{payment}/check-status', [\App\Http\Controllers\PaymentGatewayController::class, 'checkStatus'])->name('check-status');
        });
    });

    // Customer management routes
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    
    // Internet package management routes
    Route::resource('packages', \App\Http\Controllers\InternetPackageController::class);
    
    // PPPoE account management routes
    Route::resource('pppoe', \App\Http\Controllers\PPPoEController::class);
    Route::post('/pppoe/{pppoe}/enable', [\App\Http\Controllers\PPPoEController::class, 'enable'])->name('pppoe.enable');
    Route::post('/pppoe/{pppoe}/disable', [\App\Http\Controllers\PPPoEController::class, 'disable'])->name('pppoe.disable');

    // Admin routes
    Route::middleware('role:admin,manager')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        
        // Additional user management routes
        Route::get('/users/{user}/reset-password', [UserController::class, 'resetPasswordForm'])->name('users.reset-password');
        Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password.update');
        
        // Payment Gateway Settings routes
        Route::prefix('payment-settings')->name('payment-settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PaymentSettingController::class, 'index'])->name('index');
            Route::get('/tripay/edit', [\App\Http\Controllers\PaymentSettingController::class, 'editTripay'])->name('tripay.edit');
            Route::put('/tripay', [\App\Http\Controllers\PaymentSettingController::class, 'updateTripay'])->name('tripay.update');
            Route::post('/tripay/test', [\App\Http\Controllers\PaymentSettingController::class, 'testTripay'])->name('tripay.test');
            Route::post('/{gateway}/toggle-status', [\App\Http\Controllers\PaymentSettingController::class, 'toggleStatus'])->name('toggle-status');
        });
    });
});

// Tripay webhook routes (no auth required)
Route::prefix('api/tripay')->name('tripay.')->group(function () {
    Route::post('/callback', [\App\Http\Controllers\PaymentGatewayController::class, 'callback'])->name('callback');
});
