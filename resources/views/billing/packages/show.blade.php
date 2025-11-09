@extends('layouts.billing')

@section('title', 'Detail Paket Internet')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Detail Paket: {{ $package->name }}</h2>
                <div>
                    <a href="{{ route('packages.edit', $package) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Paket
                    </a>
                    <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Package Details -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Paket</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Nama Paket:</strong></td>
                            <td>{{ $package->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi:</strong></td>
                            <td>{{ $package->description ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bandwidth:</strong></td>
                            <td>
                                <span class="badge badge-info badge-lg">{{ $package->bandwidth_display }}</span>
                                <br><small class="text-muted">
                                    Download: {{ number_format($package->bandwidth_down) }} Kbps | 
                                    Upload: {{ number_format($package->bandwidth_up) }} Kbps
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Harga:</strong></td>
                            <td>
                                <h4 class="text-success mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</h4>
                                <small class="text-muted">per {{ ucfirst($package->billing_cycle) }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Durasi:</strong></td>
                            <td>{{ $package->duration_days }} hari</td>
                        </tr>
                        <tr>
                            <td><strong>IP Pool:</strong></td>
                            <td>{{ $package->ip_pool ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($package->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($package->features && is_array($package->features) && count($package->features) > 0)
                    <hr>
                    <h6 class="font-weight-bold">Fitur Paket:</h6>
                    <div class="row">
                        @foreach($package->features as $feature)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-check text-success"></i> {{ $feature }}
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Subscription List -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Customer yang Berlangganan 
                        <span class="badge badge-info">{{ $package->subscriptions->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($package->subscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Monthly Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <a href="{{ route('customers.show', $subscription->customer) }}">
                                                {{ $subscription->customer->name }}
                                            </a>
                                        </td>
                                        <td>{{ $subscription->start_date->format('d/m/Y') }}</td>
                                        <td>{{ $subscription->end_date->format('d/m/Y') }}</td>
                                        <td>
                                            @if($subscription->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($subscription->status) }}</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($subscription->monthly_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Belum ada customer yang berlangganan paket ini</p>
                    @endif
                </div>
            </div>

            <!-- PPPoE Accounts -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Akun PPPoE 
                        <span class="badge badge-info">{{ $package->pppoeAccounts->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($package->pppoeAccounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Data Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->pppoeAccounts as $account)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pppoe.show', $account) }}">
                                                {{ $account->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('customers.show', $account->customer) }}">
                                                {{ $account->customer->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($account->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($account->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $account->last_login ? $account->last_login->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <small>
                                                ↓ {{ $account->formatted_bytes_in }}<br>
                                                ↑ {{ $account->formatted_bytes_out }}
                                            </small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Belum ada akun PPPoE untuk paket ini</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-md-4">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary">Statistik Paket</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td>Total Subscriber:</td>
                            <td><strong>{{ $package->subscriptions->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Active Subscriber:</td>
                            <td><strong>{{ $package->subscriptions->where('status', 'active')->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td>PPPoE Accounts:</td>
                            <td><strong>{{ $package->pppoeAccounts->count() }}</strong></td>
                        </tr>
                        <tr>
                            <td>Active PPPoE:</td>
                            <td><strong>{{ $package->pppoeAccounts->where('status', 'active')->count() }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Revenue Potensial</h6>
                    @php
                        $activeSubscriptions = $package->subscriptions->where('status', 'active')->count();
                        $monthlyRevenue = $activeSubscriptions * $package->price;
                    @endphp
                    <h4 class="text-success">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
                    <small class="text-muted">per bulan dari {{ $activeSubscriptions }} subscriber aktif</small>
                </div>
            </div>

            <div class="card border-left-info shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('packages.edit', $package) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Paket
                        </a>
                        <a href="{{ route('pppoe.create') }}?package_id={{ $package->id }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah PPPoE
                        </a>
                        @if(!$package->is_active)
                            <form action="{{ route('packages.update', $package) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="1">
                                <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-toggle-on"></i> Aktifkan Paket
                                </button>
                            </form>
                        @else
                            <form action="{{ route('packages.update', $package) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="0">
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="fas fa-toggle-off"></i> Nonaktifkan Paket
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection