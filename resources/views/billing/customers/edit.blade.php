@extends('layouts.billing')

@section('title', 'Edit Customer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Edit Customer: {{ $customer->name }}</h2>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Data Customer</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.update', $customer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $customer->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_card">No. KTP/Identitas</label>
                                    <input type="text" class="form-control @error('id_card') is-invalid @enderror" 
                                           id="id_card" name="id_card" value="{{ old('id_card', $customer->id_card) }}">
                                    @error('id_card')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="suspended" {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="join_date">Tanggal Bergabung <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('join_date') is-invalid @enderror" 
                                           id="join_date" name="join_date" value="{{ old('join_date', $customer->join_date ? $customer->join_date->format('Y-m-d') : '') }}" required>
                                    @error('join_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="deposit">Deposit (Rp)</label>
                                    <input type="number" class="form-control @error('deposit') is-invalid @enderror" 
                                           id="deposit" name="deposit" value="{{ old('deposit', $customer->deposit) }}" min="0" step="1000">
                                    @error('deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="col-md-4">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Info Customer Saat Ini</h6>
                    <p><strong>Kode:</strong> {{ $customer->customer_code }}</p>
                    <p><strong>Bergabung:</strong> {{ $customer->join_date ? $customer->join_date->format('d/m/Y') : 'Not specified' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'suspended' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </p>
                    <p><strong>Deposit:</strong> Rp {{ number_format($customer->deposit, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="card border-left-warning shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">Perhatian</h6>
                    <p class="text-sm text-gray-600">
                        • Mengubah status ke "Suspended" akan menonaktifkan semua akun PPPoE customer<br>
                        • Kode customer tidak dapat diubah<br>
                        • Data yang sudah tersimpan akan mempengaruhi laporan
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection