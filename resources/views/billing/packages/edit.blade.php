@extends('layouts.billing')

@section('title', 'Edit Paket Internet')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Edit Paket Internet</h2>
                <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit: {{ $package->name }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('packages.update', $package) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $package->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bandwidth_down">Bandwidth Download (Kbps) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bandwidth_down') is-invalid @enderror" 
                                           id="bandwidth_down" name="bandwidth_down" value="{{ old('bandwidth_down', $package->bandwidth_down) }}" 
                                           required min="128" step="128">
                                    @error('bandwidth_down')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bandwidth_up">Bandwidth Upload (Kbps) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bandwidth_up') is-invalid @enderror" 
                                           id="bandwidth_up" name="bandwidth_up" value="{{ old('bandwidth_up', $package->bandwidth_up) }}" 
                                           required min="128" step="128">
                                    @error('bandwidth_up')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $package->price) }}" 
                                           required min="0" step="1000">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing_cycle">Siklus Tagihan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('billing_cycle') is-invalid @enderror" 
                                            id="billing_cycle" name="billing_cycle" required>
                                        <option value="monthly" {{ old('billing_cycle', $package->billing_cycle) == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="quarterly" {{ old('billing_cycle', $package->billing_cycle) == 'quarterly' ? 'selected' : '' }}>Triwulan (3 Bulan)</option>
                                        <option value="yearly" {{ old('billing_cycle', $package->billing_cycle) == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                    </select>
                                    @error('billing_cycle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duration_days">Durasi (Hari) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" 
                                           required min="1">
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ip_pool">IP Pool</label>
                                    <input type="text" class="form-control @error('ip_pool') is-invalid @enderror" 
                                           id="ip_pool" name="ip_pool" value="{{ old('ip_pool', $package->ip_pool) }}">
                                    @error('ip_pool')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" 
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $package->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active', $package->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="features">Fitur Tambahan (JSON)</label>
                            <textarea class="form-control @error('features') is-invalid @enderror" 
                                      id="features" name="features" rows="3">{{ old('features', json_encode($package->features)) }}</textarea>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Package Info -->
        <div class="col-md-4">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Info Paket Saat Ini</h6>
                    <p><strong>Bandwidth:</strong> {{ $package->bandwidth_display }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    <p><strong>Durasi:</strong> {{ $package->duration_days }} hari</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $package->is_active ? 'success' : 'danger' }}">
                            {{ $package->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </p>
                </div>
            </div>

            @if($package->features && is_array($package->features) && count($package->features) > 0)
            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Fitur Saat Ini</h6>
                    <ul class="list-unstyled mb-0">
                        @foreach($package->features as $feature)
                            <li><i class="fas fa-check text-success"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection