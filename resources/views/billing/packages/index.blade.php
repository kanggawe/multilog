@extends('layouts.billing')

@section('title', 'Daftar Paket Internet')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Paket Internet</h2>
                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Paket
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('packages.index') }}" class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama paket..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="is_active" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="{{ route('packages.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Package List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Paket Internet</h6>
                </div>
                <div class="card-body">
                    @if($packages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Paket</th>
                                        <th>Bandwidth</th>
                                        <th>Harga</th>
                                        <th>Billing Cycle</th>
                                        <th>Durasi (Hari)</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($packages as $package)
                                    <tr>
                                        <td>
                                            <strong>{{ $package->name }}</strong>
                                            @if($package->description)
                                                <br><small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $package->bandwidth_display }}</span>
                                            @if($package->ip_pool)
                                                <br><small class="text-muted">Pool: {{ $package->ip_pool }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ ucfirst($package->billing_cycle) }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $package->duration_days }}</td>
                                        <td>
                                            @if($package->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('packages.show', $package) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('packages.edit', $package) }}" 
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('packages.destroy', $package) }}" 
                                                      method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
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
                            {{ $packages->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-wifi fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Belum ada paket internet</h5>
                            <p class="text-gray-500">Mulai dengan menambahkan paket internet pertama Anda.</p>
                            <a href="{{ route('packages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Paket
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection