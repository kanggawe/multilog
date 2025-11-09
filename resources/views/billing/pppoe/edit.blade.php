@extends('layouts.billing')

@section('title', 'Edit Akun PPPoE')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Edit Akun PPPoE: {{ $pppoe->username }}</h2>
                <a href="{{ route('pppoe.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Akun PPPoE</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pppoe.update', $pppoe) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id', $pppoe->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->customer_code }} - {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="internet_package_id">Paket Internet <span class="text-danger">*</span></label>
                                    <select class="form-control @error('internet_package_id') is-invalid @enderror" 
                                            id="internet_package_id" name="internet_package_id" required>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" {{ old('internet_package_id', $pppoe->internet_package_id) == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }} - {{ $package->bandwidth_display }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('internet_package_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username PPPoE <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           id="username" name="username" value="{{ old('username', $pppoe->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" value="" 
                                           placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <small class="form-text text-muted">Password saat ini: ********</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="static_ip">Static IP</label>
                                    <input type="text" class="form-control @error('static_ip') is-invalid @enderror" 
                                           id="static_ip" name="static_ip" value="{{ old('static_ip', $pppoe->static_ip) }}">
                                    @error('static_ip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_name">Profile Mikrotik</label>
                                    <input type="text" class="form-control @error('profile_name') is-invalid @enderror" 
                                           id="profile_name" name="profile_name" value="{{ old('profile_name', $pppoe->profile_name) }}">
                                    @error('profile_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $pppoe->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $pppoe->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="suspended" {{ old('status', $pppoe->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="expired" {{ old('status', $pppoe->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expires_at">Tanggal Expired</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" 
                                           value="{{ old('expires_at', $pppoe->expires_at ? $pppoe->expires_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $pppoe->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pppoe.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Akun PPPoE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="col-md-4">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Info Akun Saat Ini</h6>
                    <p><strong>Username:</strong> {{ $pppoe->username }}</p>
                    <p><strong>Customer:</strong> {{ $pppoe->customer->name }}</p>
                    <p><strong>Paket:</strong> {{ $pppoe->internetPackage->name }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $pppoe->status == 'active' ? 'success' : ($pppoe->status == 'suspended' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($pppoe->status) }}
                        </span>
                    </p>
                    @if($pppoe->static_ip)
                        <p><strong>Static IP:</strong> {{ $pppoe->static_ip }}</p>
                    @endif
                </div>
            </div>

            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Usage Statistics</h6>
                    <p><strong>Last Login:</strong><br>
                        {{ $pppoe->last_login ? $pppoe->last_login->format('d/m/Y H:i') : 'Belum pernah login' }}
                    </p>
                    @if($pppoe->last_ip)
                        <p><strong>Last IP:</strong> {{ $pppoe->last_ip }}</p>
                    @endif
                    <p><strong>Data Usage:</strong><br>
                        ↓ {{ $pppoe->formatted_bytes_in }}<br>
                        ↑ {{ $pppoe->formatted_bytes_out }}
                    </p>
                </div>
            </div>

            @if($pppoe->expires_at)
            <div class="card border-left-{{ $pppoe->isExpired() ? 'danger' : 'warning' }} shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-{{ $pppoe->isExpired() ? 'danger' : 'warning' }}">
                        {{ $pppoe->isExpired() ? 'Account Expired' : 'Expiration Info' }}
                    </h6>
                    <p>Expires: {{ $pppoe->expires_at->format('d F Y, H:i') }}</p>
                    @if(!$pppoe->isExpired())
                        <p>Remaining: {{ $pppoe->expires_at->diffForHumans() }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection