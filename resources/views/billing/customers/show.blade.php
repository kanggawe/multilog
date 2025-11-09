@extends('layouts.billing')

@section('title', 'Detail Customer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Customer: {{ $customer->name }}</h2>
                <div>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Customer
                    </a>
                    <a href="{{ route('pppoe.create') }}?customer_id={{ $customer->id }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah PPPoE
                    </a>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Details -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Customer</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Kode Customer:</strong></td>
                            <td>{{ $customer->customer_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Lengkap:</strong></td>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat:</strong></td>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telepon:</strong></td>
                            <td>{{ $customer->phone ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $customer->email ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. KTP:</strong></td>
                            <td>{{ $customer->id_card ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($customer->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($customer->status == 'suspended')
                                    <span class="badge badge-danger">Suspended</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Bergabung:</strong></td>
                            <td>{{ $customer->join_date ? $customer->join_date->format('d F Y') : 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Deposit:</strong></td>
                            <td>Rp {{ number_format($customer->deposit, 0, ',', '.') }}</td>
                        </tr>
                        @if($customer->notes)
                        <tr>
                            <td><strong>Catatan:</strong></td>
                            <td>{{ $customer->notes }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Dibuat oleh:</strong></td>
                            <td>{{ $customer->createdBy ? $customer->createdBy->name : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Subscriptions -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Langganan Paket 
                        <span class="badge badge-info">{{ $customer->subscriptions->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($customer->subscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Paket</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Monthly Fee</th>
                                        <th>Status</th>
                                        <th>Remaining Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <a href="{{ route('packages.show', $subscription->internetPackage) }}">
                                                {{ $subscription->internetPackage->name }}
                                            </a>
                                            <br><small class="text-muted">{{ $subscription->internetPackage->bandwidth_display }}</small>
                                        </td>
                                        <td>{{ $subscription->start_date->format('d/m/Y') }}</td>
                                        <td>{{ $subscription->end_date->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($subscription->monthly_fee, 0, ',', '.') }}</td>
                                        <td>
                                            @if($subscription->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($subscription->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscription->isExpired())
                                                <span class="badge badge-danger">Expired</span>
                                            @else
                                                {{ $subscription->getRemainingDays() }} hari
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Belum ada langganan paket</p>
                    @endif
                </div>
            </div>

            <!-- PPPoE Accounts -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Akun PPPoE 
                        <span class="badge badge-info">{{ $customer->pppoeAccounts->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($customer->pppoeAccounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Paket</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Data Usage</th>
                                        <th>Expires</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->pppoeAccounts as $account)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pppoe.show', $account) }}">
                                                {{ $account->username }}
                                            </a>
                                            @if($account->static_ip)
                                                <br><small class="text-muted">IP: {{ $account->static_ip }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $account->internetPackage->name }}
                                            <br><span class="badge badge-info badge-sm">{{ $account->internetPackage->bandwidth_display }}</span>
                                        </td>
                                        <td>
                                            @if($account->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($account->status == 'suspended')
                                                <span class="badge badge-warning">Suspended</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($account->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->last_login)
                                                {{ $account->last_login->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                ↓ {{ $account->formatted_bytes_in }}<br>
                                                ↑ {{ $account->formatted_bytes_out }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($account->expires_at)
                                                @if($account->isExpired())
                                                    <span class="badge badge-danger">
                                                        {{ $account->expires_at->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-success">
                                                        {{ $account->expires_at->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pppoe.edit', $account) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">
                            Belum ada akun PPPoE<br>
                            <a href="{{ route('pppoe.create') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Buat Akun PPPoE
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Terbaru (10 terakhir)</h6>
                </div>
                <div class="card-body">
                    @if($customer->invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($invoice->status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($invoice->status == 'partial')
                                                <span class="badge badge-warning">Partial</span>
                                            @elseif($invoice->isOverdue())
                                                <span class="badge badge-danger">Overdue</span>
                                            @else
                                                <span class="badge badge-secondary">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Belum ada invoice</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics & Actions -->
        <div class="col-md-4">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary">Statistik Customer</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td>Total Langganan:</td>
                            <td><strong>{{ $customer->subscriptions->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Akun PPPoE:</td>
                            <td><strong>{{ $customer->pppoeAccounts->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Invoice:</td>
                            <td><strong>{{ $customer->invoices->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Pembayaran:</td>
                            <td><strong>{{ $customer->payments->count() }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Total Revenue</h6>
                    @php
                        $totalRevenue = $customer->payments->sum('amount');
                    @endphp
                    <h4 class="text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    <small class="text-muted">dari {{ $customer->payments->count() }} pembayaran</small>
                </div>
            </div>

            <div class="card border-left-info shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Customer
                        </a>
                        <a href="{{ route('pppoe.create') }}?customer_id={{ $customer->id }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah PPPoE
                        </a>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-file-invoice"></i> Generate Invoice
                        </button>
                        <button class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-money-bill"></i> Record Payment
                        </button>
                    </div>
                </div>
            </div>

            @if($customer->notes)
            <div class="card border-left-warning shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">Catatan</h6>
                    <p class="text-sm">{{ $customer->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection