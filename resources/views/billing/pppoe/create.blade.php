@extends('layouts.billing')

@section('title', 'Tambah Akun PPPoE')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Tambah Akun PPPoE</h2>
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
                    <h6 class="m-0 font-weight-bold text-primary">Form Akun PPPoE Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pppoe.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        <option value="">Pilih Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                        <option value="">Pilih Paket</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" {{ old('internet_package_id') == $package->id ? 'selected' : '' }}
                                                    data-price="{{ $package->price }}" data-bandwidth="{{ $package->bandwidth_display }}">
                                                {{ $package->name }} - {{ $package->bandwidth_display }} - Rp {{ number_format($package->price, 0, ',', '.') }}
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
                                    <label for="username">Username PPPoE</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           id="username" name="username" value="{{ old('username') }}"
                                           placeholder="Kosongkan untuk auto-generate">
                                    <small class="form-text text-muted">Username akan di-generate otomatis jika dikosongkan</small>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" value="{{ old('password') }}"
                                           placeholder="Kosongkan untuk auto-generate">
                                    <small class="form-text text-muted">Password akan di-generate otomatis jika dikosongkan</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="static_ip">Static IP (Opsional)</label>
                                    <input type="text" class="form-control @error('static_ip') is-invalid @enderror" 
                                           id="static_ip" name="static_ip" value="{{ old('static_ip') }}"
                                           placeholder="Contoh: 192.168.100.10">
                                    @error('static_ip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_name">Profile Mikrotik</label>
                                    <input type="text" class="form-control @error('profile_name') is-invalid @enderror" 
                                           id="profile_name" name="profile_name" value="{{ old('profile_name') }}"
                                           placeholder="Akan diisi otomatis dari paket">
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
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expires_at">Tanggal Expired (Opsional)</label>
                                    <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
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
                                <i class="fas fa-save"></i> Buat Akun PPPoE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-md-4">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary">Informasi</h6>
                    <p class="text-sm text-gray-600">
                        • Username dan password akan di-generate otomatis jika dikosongkan<br>
                        • Profile Mikrotik akan diisi dari nama paket yang dipilih<br>
                        • Status "Aktif" akan langsung sync ke Mikrotik RouterOS
                    </p>
                </div>
            </div>

            <div class="card border-left-info shadow mt-3" id="package-info" style="display: none;">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Info Paket Terpilih</h6>
                    <div id="package-details"></div>
                </div>
            </div>

            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Tips</h6>
                    <p class="text-sm text-gray-600">
                        • Pastikan customer sudah terdaftar sebelum membuat akun PPPoE<br>
                        • Gunakan static IP untuk customer bisnis/enterprise<br>
                        • Set tanggal expired untuk trial account
                    </p>
                </div>
            </div>

            <div class="card border-left-warning shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">Integrasi Mikrotik</h6>
                    <p class="text-sm text-gray-600">
                        Akun PPPoE akan otomatis di-sync ke Mikrotik RouterOS setelah disimpan.
                        Pastikan konfigurasi koneksi Mikrotik sudah benar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Show package info when package is selected
document.getElementById('internet_package_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const packageInfo = document.getElementById('package-info');
    const packageDetails = document.getElementById('package-details');
    
    if (selectedOption.value) {
        const bandwidth = selectedOption.dataset.bandwidth;
        const price = selectedOption.getAttribute('data-price');
        const priceFormatted = new Intl.NumberFormat('id-ID').format(price);
        
        packageDetails.innerHTML = `
            <p><strong>Bandwidth:</strong> ${bandwidth}</p>
            <p><strong>Harga:</strong> Rp ${priceFormatted}</p>
        `;
        packageInfo.style.display = 'block';
        
        // Auto fill profile name
        const profileName = selectedOption.text.split(' - ')[0].toLowerCase().replace(/\s+/g, '-');
        document.getElementById('profile_name').value = profileName;
    } else {
        packageInfo.style.display = 'none';
        document.getElementById('profile_name').value = '';
    }
});

// Generate random password
function generatePassword() {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let password = '';
    for (let i = 0; i < 8; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return password;
}

// Add button to generate password
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-sm btn-outline-secondary mt-1';
    button.innerHTML = '<i class="fas fa-key"></i> Generate';
    button.onclick = function() {
        passwordField.value = generatePassword();
    };
    passwordField.parentNode.appendChild(button);
});
</script>
@endsection
@endsection