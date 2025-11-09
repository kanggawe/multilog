@extends('layouts.billing')

@section('title', 'Tambah Paket Internet')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Tambah Paket Internet</h2>
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
                    <h6 class="m-0 font-weight-bold text-primary">Form Paket Internet Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('packages.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Paket Home 10 Mbps">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bandwidth_down">Bandwidth Download (Kbps) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bandwidth_down') is-invalid @enderror" 
                                           id="bandwidth_down" name="bandwidth_down" value="{{ old('bandwidth_down') }}" 
                                           required min="128" step="128"
                                           placeholder="Contoh: 10240 (10 Mbps)">
                                    <small class="form-text text-muted">1 Mbps = 1024 Kbps</small>
                                    @error('bandwidth_down')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bandwidth_up">Bandwidth Upload (Kbps) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bandwidth_up') is-invalid @enderror" 
                                           id="bandwidth_up" name="bandwidth_up" value="{{ old('bandwidth_up') }}" 
                                           required min="128" step="128"
                                           placeholder="Contoh: 2048 (2 Mbps)">
                                    <small class="form-text text-muted">Biasanya 1/5 dari download</small>
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
                                           id="price" name="price" value="{{ old('price') }}" 
                                           required min="0" step="1000"
                                           placeholder="Contoh: 250000">
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
                                        <option value="">Pilih Siklus</option>
                                        <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="quarterly" {{ old('billing_cycle') == 'quarterly' ? 'selected' : '' }}>Triwulan (3 Bulan)</option>
                                        <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
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
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', 30) }}" 
                                           required min="1"
                                           placeholder="30">
                                    <small class="form-text text-muted">Durasi aktif paket</small>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ip_pool">IP Pool</label>
                                    <input type="text" class="form-control @error('ip_pool') is-invalid @enderror" 
                                           id="ip_pool" name="ip_pool" value="{{ old('ip_pool') }}"
                                           placeholder="Contoh: pool-home">
                                    <small class="form-text text-muted">Nama IP pool di Mikrotik</small>
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
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
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
                                      id="features" name="features" rows="3" 
                                      placeholder='["Unlimited Data", "Gaming Optimized", "24/7 Support"]'>{{ old('features') }}</textarea>
                            <small class="form-text text-muted">Format JSON array untuk fitur paket (opsional)</small>
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
                                <i class="fas fa-save"></i> Simpan Paket
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
                    <h6 class="font-weight-bold text-primary">Petunjuk Bandwidth</h6>
                    <table class="table table-sm">
                        <tr><td>1 Mbps</td><td>= 1024 Kbps</td></tr>
                        <tr><td>5 Mbps</td><td>= 5120 Kbps</td></tr>
                        <tr><td>10 Mbps</td><td>= 10240 Kbps</td></tr>
                        <tr><td>20 Mbps</td><td>= 20480 Kbps</td></tr>
                        <tr><td>50 Mbps</td><td>= 51200 Kbps</td></tr>
                    </table>
                </div>
            </div>

            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Tips Harga</h6>
                    <p class="text-sm text-gray-600">
                        • Home 5 Mbps: Rp 150.000<br>
                        • Home 10 Mbps: Rp 250.000<br>
                        • Business 20 Mbps: Rp 500.000<br>
                        • Enterprise 50 Mbps: Rp 1.200.000
                    </p>
                </div>
            </div>

            <div class="card border-left-info shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Contoh Features JSON</h6>
                    <pre class="text-xs">["Unlimited Data", "Fair Usage Policy", "Gaming Optimized", "24/7 Support", "Static IP Available"]</pre>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Auto calculate upload bandwidth (1/5 of download)
document.getElementById('bandwidth_down').addEventListener('input', function() {
    const down = parseInt(this.value);
    if (!isNaN(down) && down > 0) {
        const up = Math.round(down / 5);
        document.getElementById('bandwidth_up').value = up;
    }
});

// Auto adjust duration based on billing cycle
document.getElementById('billing_cycle').addEventListener('change', function() {
    const cycle = this.value;
    const durationInput = document.getElementById('duration_days');
    
    switch(cycle) {
        case 'monthly':
            durationInput.value = 30;
            break;
        case 'quarterly':
            durationInput.value = 90;
            break;
        case 'yearly':
            durationInput.value = 365;
            break;
    }
});
</script>
@endsection
@endsection