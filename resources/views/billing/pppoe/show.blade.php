@extends('layouts.billing')

@section('title', 'Detail Akun PPPoE')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Akun PPPoE: {{ $pppoe->username }}</h2>
                <div>
                    <a href="{{ route('pppoe.edit', $pppoe) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Akun
                    </a>
                    @if($pppoe->status == 'active')
                        <form action="{{ route('pppoe.disable', $pppoe) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Suspend akun ini?')">
                                <i class="fas fa-pause"></i> Suspend
                            </button>
                        </form>
                    @else
                        <form action="{{ route('pppoe.enable', $pppoe) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Aktifkan akun ini?')">
                                <i class="fas fa-play"></i> Aktifkan
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('pppoe.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Account Details -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Akun PPPoE</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Username:</strong></td>
                            <td>{{ $pppoe->username }}</td>
                        </tr>
                        <tr>
                            <td><strong>Password:</strong></td>
                            <td>********</td>
                        </tr>
                        <tr>
                            <td><strong>Customer:</strong></td>
                            <td>
                                <a href="{{ route('customers.show', $pppoe->customer) }}">
                                    {{ $pppoe->customer->name }}
                                </a>
                                <br><small class="text-muted">{{ $pppoe->customer->customer_code }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Paket Internet:</strong></td>
                            <td>
                                <a href="{{ route('packages.show', $pppoe->internetPackage) }}">
                                    {{ $pppoe->internetPackage->name }}
                                </a>
                                <br><span class="badge badge-info">{{ $pppoe->internetPackage->bandwidth_display }}</span>
                                <br><small class="text-muted">Rp {{ number_format($pppoe->internetPackage->price, 0, ',', '.') }}/{{ $pppoe->internetPackage->billing_cycle }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($pppoe->status == 'active')
                                    <span class="badge badge-success badge-lg">Aktif</span>
                                @elseif($pppoe->status == 'suspended')
                                    <span class="badge badge-warning badge-lg">Suspended</span>
                                @elseif($pppoe->status == 'expired')
                                    <span class="badge badge-danger badge-lg">Expired</span>
                                @else
                                    <span class="badge badge-secondary badge-lg">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Static IP:</strong></td>
                            <td>{{ $pppoe->static_ip ?: 'Dynamic IP (DHCP)' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Profile Mikrotik:</strong></td>
                            <td>{{ $pppoe->profile_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $pppoe->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        @if($pppoe->expires_at)
                        <tr>
                            <td><strong>Expires:</strong></td>
                            <td>
                                {{ $pppoe->expires_at->format('d F Y, H:i') }}
                                @if($pppoe->isExpired())
                                    <span class="badge badge-danger ml-2">EXPIRED</span>
                                @else
                                    <br><small class="text-muted">{{ $pppoe->expires_at->diffForHumans() }}</small>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @if($pppoe->notes)
                        <tr>
                            <td><strong>Catatan:</strong></td>
                            <td>{{ $pppoe->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Connection Statistics -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Koneksi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Last Login Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $pppoe->last_login ? $pppoe->last_login->format('d/m/Y H:i:s') : 'Belum pernah login' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last IP:</strong></td>
                                    <td>{{ $pppoe->last_ip ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Data Usage</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Download:</strong></td>
                                    <td>{{ $pppoe->formatted_bytes_in }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Upload:</strong></td>
                                    <td>{{ $pppoe->formatted_bytes_out }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td>{{ $pppoe->formatBytes($pppoe->bytes_in + $pppoe->bytes_out) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bandwidth Information -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bandwidth Configuration</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <h5 class="text-success">Download Speed</h5>
                                    <h3>{{ $pppoe->internetPackage->formatBandwidth($pppoe->internetPackage->bandwidth_down) }}</h3>
                                    <small class="text-muted">{{ number_format($pppoe->internetPackage->bandwidth_down) }} Kbps</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h5 class="text-info">Upload Speed</h5>
                                    <h3>{{ $pppoe->internetPackage->formatBandwidth($pppoe->internetPackage->bandwidth_up) }}</h3>
                                    <small class="text-muted">{{ number_format($pppoe->internetPackage->bandwidth_up) }} Kbps</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics & Actions -->
        <div class="col-md-4">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <h6 class="font-weight-bold text-primary">Quick Info</h6>
                    <p><strong>Account Age:</strong><br>{{ $pppoe->created_at->diffForHumans() }}</p>
                    <p><strong>Monthly Fee:</strong><br>Rp {{ number_format($pppoe->internetPackage->price, 0, ',', '.') }}</p>
                    @if($pppoe->expires_at && !$pppoe->isExpired())
                        <p><strong>Expires In:</strong><br>{{ $pppoe->expires_at->diffForHumans() }}</p>
                    @endif
                </div>
            </div>

            @if($pppoe->status == 'active')
            <div class="card border-left-success shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Account Active</h6>
                    <p class="text-sm">Account is currently active and can connect to internet.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                    </div>
                    <small class="text-success">Ready to connect</small>
                </div>
            </div>
            @elseif($pppoe->status == 'suspended')
            <div class="card border-left-warning shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">Account Suspended</h6>
                    <p class="text-sm">Account is temporarily suspended and cannot connect.</p>
                </div>
            </div>
            @elseif($pppoe->status == 'expired')
            <div class="card border-left-danger shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-danger">Account Expired</h6>
                    <p class="text-sm">Account has expired and needs renewal.</p>
                </div>
            </div>
            @endif

            <div class="card border-left-info shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('pppoe.edit', $pppoe) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Account
                        </a>
                        @if($pppoe->status == 'active')
                            <form action="{{ route('pppoe.disable', $pppoe) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100" 
                                        onclick="return confirm('Suspend akun ini?')">
                                    <i class="fas fa-pause"></i> Suspend Account
                                </button>
                            </form>
                        @else
                            <form action="{{ route('pppoe.enable', $pppoe) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm w-100" 
                                        onclick="return confirm('Aktifkan akun ini?')">
                                    <i class="fas fa-play"></i> Activate Account
                                </button>
                            </form>
                        @endif
                        <button class="btn btn-outline-info btn-sm" onclick="refreshStats()">
                            <i class="fas fa-sync"></i> Refresh Stats
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="showPassword()">
                            <i class="fas fa-eye"></i> Show Password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Connection Instructions -->
            <div class="card border-left-secondary shadow mt-3">
                <div class="card-body">
                    <h6 class="font-weight-bold text-secondary">Connection Info</h6>
                    <p class="text-sm"><strong>Username:</strong> {{ $pppoe->username }}</p>
                    <p class="text-sm"><strong>Password:</strong> ********</p>
                    @if($pppoe->static_ip)
                        <p class="text-sm"><strong>IP Address:</strong> {{ $pppoe->static_ip }}</p>
                    @endif
                    <small class="text-muted">Share this info with customer for connection setup.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function refreshStats() {
    // TODO: Implement AJAX call to refresh statistics from Mikrotik
    alert('Feature akan segera tersedia - refresh stats dari Mikrotik RouterOS');
}

function showPassword() {
    // TODO: Implement show password with security confirmation
    alert('Feature akan segera tersedia - show password dengan konfirmasi keamanan');
}
</script>
@endsection
@endsection