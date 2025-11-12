@extends('layouts.billing')

@section('title', 'Buat Invoice Manual')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Buat Invoice Manual</h2>
                <a href="{{ route('billing.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-exclamation-circle"></i> Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Form Invoice Manual</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('billing.invoices.store-manual') }}" method="POST" id="manualInvoiceForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="customer_id">Pilih Customer <span class="text-danger">*</span></label>
                            <select class="form-control @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            data-subscriptions="{{ json_encode($customer->subscriptions) }}"
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }} - {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Pilih customer yang akan dibuatkan invoice
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="subscription_id">Pilih Subscription (Opsional)</label>
                            <select class="form-control @error('subscription_id') is-invalid @enderror" 
                                    id="subscription_id" name="subscription_id" disabled>
                                <option value="">-- Pilih Customer terlebih dahulu --</option>
                            </select>
                            @error('subscription_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Jika customer belum punya subscription, biarkan kosong untuk tagihan custom
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Jumlah Tagihan (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           min="0" step="1000" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Akan terisi otomatis sesuai harga paket
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" 
                                           value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" 
                                           required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Default: 7 hari dari sekarang
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_start">Periode Mulai</label>
                                    <input type="date" class="form-control @error('period_start') is-invalid @enderror" 
                                           id="period_start" name="period_start" 
                                           value="{{ old('period_start', now()->startOfMonth()->format('Y-m-d')) }}">
                                    @error('period_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_end">Periode Selesai</label>
                                    <input type="date" class="form-control @error('period_end') is-invalid @enderror" 
                                           id="period_end" name="period_end" 
                                           value="{{ old('period_end', now()->endOfMonth()->format('Y-m-d')) }}">
                                    @error('period_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi/Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Jelaskan untuk apa tagihan ini (wajib diisi)
                            </small>
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('billing.invoices.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-invoice"></i> Buat Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-md-4">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info"><i class="fas fa-info-circle"></i> Informasi</h6>
                    <p class="text-sm text-gray-700 mb-2">
                        <strong>Invoice Manual</strong> digunakan untuk membuat tagihan di luar jadwal billing otomatis.
                    </p>
                    <p class="text-sm text-gray-700 mb-0">
                        Contoh penggunaan:
                    </p>
                    <ul class="text-sm text-gray-700 mb-0 pl-3">
                        <li>Customer ingin bayar lebih awal</li>
                        <li>Pembayaran pro-rated</li>
                        <li>Tagihan tambahan layanan</li>
                        <li>Customer baru di tengah periode</li>
                    </ul>
                </div>
            </div>

            <div class="card border-left-danger shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-danger"><i class="fas fa-exclamation-triangle"></i> Persyaratan</h6>
                    <p class="text-sm text-gray-700 mb-2">
                        Untuk membuat invoice dengan subscription, customer harus memiliki <strong>subscription aktif</strong> terlebih dahulu.
                    </p>
                    <p class="text-sm text-gray-700 mb-0">
                        Jika dropdown subscription kosong atau disabled:
                    </p>
                    <ol class="text-sm text-gray-700 mb-2 pl-3">
                        <li>Buat <strong>PPPoE Account</strong> untuk customer</li>
                        <li>Buat <strong>Subscription</strong> dari PPPoE Account</li>
                        <li>Atau lanjut buat invoice manual tanpa subscription</li>
                    </ol>
                    <p class="text-sm text-gray-700 mb-0">
                        <strong>Alternatif:</strong> Anda bisa membuat invoice manual tanpa subscription dengan mengisi amount dan deskripsi secara manual.
                    </p>
                </div>
            </div>

            <div class="card border-left-warning shadow mt-3" id="subscription-info" style="display: none;">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning"><i class="fas fa-box"></i> Info Paket</h6>
                    <div id="subscription-details">
                        <!-- Will be filled by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success"><i class="fas fa-lightbulb"></i> Tips</h6>
                    <ul class="text-sm text-gray-700 mb-0 pl-3">
                        <li>Pastikan customer tidak memiliki invoice yang belum dibayar</li>
                        <li>Jumlah tagihan akan otomatis terisi sesuai harga paket</li>
                        <li>Anda dapat menyesuaikan jumlah jika diperlukan</li>
                        <li>Invoice akan langsung tersedia untuk pembayaran</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_id');
    const subscriptionSelect = document.getElementById('subscription_id');
    const amountInput = document.getElementById('amount');
    const descriptionInput = document.getElementById('description');
    const subscriptionInfo = document.getElementById('subscription-info');
    const subscriptionDetails = document.getElementById('subscription-details');

    // Handle customer selection
    customerSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        subscriptionSelect.innerHTML = '<option value="">-- Tidak menggunakan subscription --</option>';
        
        if (this.value) {
            try {
                const subscriptionsData = selectedOption.dataset.subscriptions;
                console.log('Raw subscriptions data:', subscriptionsData);
                
                const subscriptions = JSON.parse(subscriptionsData || '[]');
                console.log('Parsed subscriptions:', subscriptions);
                
                if (subscriptions.length > 0) {
                    subscriptions.forEach(sub => {
                        console.log('Processing subscription:', sub);
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = `${sub.package.name} - Rp ${parseInt(sub.package.price).toLocaleString('id-ID')}`;
                        option.dataset.price = sub.package.price;
                        option.dataset.packageName = sub.package.name;
                        option.dataset.bandwidth = sub.package.bandwidth_display;
                        subscriptionSelect.appendChild(option);
                    });
                    subscriptionSelect.disabled = false;
                    
                    // Show info about manual input
                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'alert alert-info mt-2';
                    infoDiv.innerHTML = '<small><i class="fas fa-info-circle"></i> Customer ini memiliki subscription. Pilih subscription atau biarkan kosong untuk tagihan custom.</small>';
                    subscriptionSelect.parentNode.appendChild(infoDiv);
                } else {
                    // No subscriptions, but allow manual invoice
                    subscriptionSelect.innerHTML = '<option value="">-- Tidak ada subscription, lanjut dengan tagihan manual --</option>';
                    subscriptionSelect.disabled = false;
                    
                    const warningDiv = document.createElement('div');
                    warningDiv.className = 'alert alert-warning mt-2';
                    warningDiv.innerHTML = '<small><i class="fas fa-exclamation-triangle"></i> Customer belum memiliki subscription. Anda bisa membuat invoice manual dengan mengisi jumlah dan deskripsi.</small>';
                    subscriptionSelect.parentNode.appendChild(warningDiv);
                }
            } catch (e) {
                console.error('Error parsing subscriptions:', e);
                subscriptionSelect.innerHTML = '<option value="">-- Lanjut tanpa subscription --</option>';
                subscriptionSelect.disabled = false;
            }
        } else {
            subscriptionSelect.innerHTML = '<option value="">-- Pilih Customer terlebih dahulu --</option>';
            subscriptionSelect.disabled = true;
            subscriptionInfo.style.display = 'none';
        }
    });

    // Handle subscription selection
    subscriptionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const price = selectedOption.dataset.price;
            const packageName = selectedOption.dataset.packageName;
            const bandwidth = selectedOption.dataset.bandwidth;
            
            // Auto fill amount
            amountInput.value = price;
            
            // Auto fill description
            if (!descriptionInput.value) {
                descriptionInput.value = `Pembayaran layanan internet - ${packageName}`;
            }
            
            // Show subscription info
            subscriptionDetails.innerHTML = `
                <p class="mb-1"><strong>Paket:</strong> ${packageName}</p>
                <p class="mb-1"><strong>Bandwidth:</strong> ${bandwidth}</p>
                <p class="mb-0"><strong>Harga:</strong> Rp ${parseInt(price).toLocaleString('id-ID')}</p>
            `;
            subscriptionInfo.style.display = 'block';
        } else {
            subscriptionInfo.style.display = 'none';
        }
    });

    // Format amount input
    amountInput.addEventListener('blur', function() {
        if (this.value) {
            this.value = Math.round(this.value);
        }
    });
});
</script>
@endsection
@endsection
