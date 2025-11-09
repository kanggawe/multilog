@extends('layouts.billing')

@section('title', 'Manajemen Customer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Manajemen Customer</h2>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Customer
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('customers.index') }}" class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama, kode, telepon, email..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Customer</h6>
                </div>
                <div class="card-body">
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode Customer</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <a href="{{ route('customers.show', $customer) }}" class="font-weight-bold">
                                                {{ $customer->customer_code }}
                                            </a>
                                        </td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ Str::limit($customer->address, 50) }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            @if($customer->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @elseif($customer->status == 'inactive')
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Suspended</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->join_date ? $customer->join_date->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customers.show', $customer) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer) }}" 
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('customers.destroy', $customer) }}" 
                                                      method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
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
                            {{ $customers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Belum ada customer</h5>
                            <p class="text-gray-500">Mulai dengan menambahkan customer pertama Anda.</p>
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Customer
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection