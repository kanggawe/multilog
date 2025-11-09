@extends('layouts.billing')

@section('title', 'Manajemen PPPoE')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Akun PPPoE</h2>
                <a href="{{ route('pppoe.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Akun PPPoE
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('pppoe.index') }}" class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari username, customer..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="{{ route('pppoe.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- PPPoE Account List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Akun PPPoE</h6>
                </div>
                <div class="card-body">
                    @if($accounts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Customer</th>
                                        <th>Paket</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Data Usage</th>
                                        <th>Expires</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $account)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pppoe.show', $account) }}" class="font-weight-bold">
                                                {{ $account->username }}
                                            </a>
                                            @if($account->static_ip)
                                                <br><small class="text-muted">IP: {{ $account->static_ip }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('customers.show', $account->customer) }}">
                                                {{ $account->customer->name }}
                                            </a>
                                            <br><small class="text-muted">{{ $account->customer->customer_code }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('packages.show', $account->internetPackage) }}">
                                                {{ $account->internetPackage->name }}
                                            </a>
                                            <br><span class="badge badge-info badge-sm">{{ $account->internetPackage->bandwidth_display }}</span>
                                        </td>
                                        <td>
                                            @if($account->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @elseif($account->status == 'suspended')
                                                <span class="badge badge-warning">Suspended</span>
                                            @elseif($account->status == 'expired')
                                                <span class="badge badge-danger">Expired</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->last_login)
                                                {{ $account->last_login->format('d/m/Y H:i') }}
                                                @if($account->last_ip)
                                                    <br><small class="text-muted">{{ $account->last_ip }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Belum pernah login</span>
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
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pppoe.show', $account) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pppoe.edit', $account) }}" 
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($account->status == 'active')
                                                    <form action="{{ route('pppoe.disable', $account) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" 
                                                                title="Suspend" onclick="return confirm('Suspend akun ini?')">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('pppoe.enable', $account) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Aktifkan" onclick="return confirm('Aktifkan akun ini?')">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('pppoe.destroy', $account) }}" 
                                                      method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus akun PPPoE ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $accounts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-network-wired fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Belum ada akun PPPoE</h5>
                            <p class="text-gray-500">Mulai dengan membuat akun PPPoE pertama untuk customer Anda.</p>
                            <a href="{{ route('pppoe.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Akun PPPoE
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Akun Aktif
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $accounts->where('status', 'active')->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Suspended
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $accounts->where('status', 'suspended')->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Expired
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $accounts->where('status', 'expired')->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Total Akun
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $accounts->total() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection