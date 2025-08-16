@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Barang</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Kelola Barang</a></li>
                    <li class="breadcrumb-item active">{{ $barang->nama_barang }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Detail Barang -->
        <div class="col-lg-8">
            <!-- Info Utama -->
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Informasi Barang</h6>
                    <div>
                        @if($barang->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Non-aktif</span>
                        @endif
                        
                        @if($barang->stok <= 0)
                            <span class="badge bg-danger">Stok Habis</span>
                        @elseif($barang->stok <= 10)
                            <span class="badge bg-warning">Stok Menipis</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Kode Barang:</label>
                            <div class="h5 text-primary">{{ $barang->kode_barang }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Nama Barang:</label>
                            <div class="h5">{{ $barang->nama_barang }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold text-muted">Kategori:</label>
                            <div><span class="badge bg-light text-dark fs-6">{{ $barang->kategori }}</span></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold text-muted">Satuan:</label>
                            <div class="h6">{{ $barang->satuan }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold text-muted">Harga Satuan:</label>
                            <div class="h6 text-success">{{ $barang->formatted_harga }}</div>
                        </div>
                        @if($barang->deskripsi)
                        <div class="col-12">
                            <label class="fw-bold text-muted">Deskripsi:</label>
                            <div class="text-muted">{{ $barang->deskripsi }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Riwayat Transaksi</h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success" onclick="filterTransaksi('masuk')">
                            <i class="fas fa-arrow-up me-1"></i>Masuk
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="filterTransaksi('keluar')">
                            <i class="fas fa-arrow-down me-1"></i>Keluar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="filterTransaksi('all')">
                            <i class="fas fa-list me-1"></i>Semua
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($barang->transaksi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Transaksi</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>User</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="transaksi-table">
                                @foreach($barang->transaksi as $transaksi)
                                <tr class="transaksi-row" data-jenis="{{ $transaksi->jenis_transaksi }}">
                                    <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <code class="text-primary">{{ $transaksi->kode_transaksi }}</code>
                                    </td>
                                    <td>
                                        @if($transaksi->jenis_transaksi === 'masuk')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-up me-1"></i>Masuk
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="fas fa-arrow-down me-1"></i>Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $transaksi->jenis_transaksi === 'masuk' ? 'text-success' : 'text-info' }}">
                                            {{ $transaksi->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ number_format($transaksi->jumlah) }}
                                        </span>
                                        <small class="text-muted">{{ $barang->satuan }}</small>
                                    </td>
                                    <td>{{ $transaksi->formatted_harga_satuan }}</td>
                                    <td>{{ $transaksi->formatted_total_harga }}</td>
                                    <td>
                                        <small>{{ $transaksi->user->name }}</small>
                                    </td>
                                    <td>
                                        @if($transaksi->keterangan)
                                            <small class="text-muted">{{ Str::limit($transaksi->keterangan, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                        
                                        @if($transaksi->supplier)
                                            <br><small class="text-info">Supplier: {{ $transaksi->supplier }}</small>
                                        @endif
                                        
                                        @if($transaksi->customer)
                                            <br><small class="text-warning">Customer: {{ $transaksi->customer }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada transaksi</h5>
                        <p class="text-muted">Transaksi akan muncul di sini setelah barang digunakan</p>
                        <div class="btn-group">
                            <a href="{{ route('transaksi.create-barang-masuk') }}?barang_id={{ $barang->id }}" class="btn btn-success">
                                <i class="fas fa-arrow-up me-2"></i>Barang Masuk
                            </a>
                            @if($barang->stok > 0)
                            <a href="{{ route('transaksi.create-barang-keluar') }}?barang_id={{ $barang->id }}" class="btn btn-info">
                                <i class="fas fa-arrow-down me-2"></i>Barang Keluar
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border-start border-primary border-4 ps-3">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Stok Saat Ini</div>
                                <div class="h5 mb-0 fw-bold {{ $barang->stok <= 0 ? 'text-danger' : ($barang->stok <= 10 ? 'text-warning' : 'text-success') }}">
                                    {{ number_format($barang->stok) }}
                                </div>
                                <small class="text-muted">{{ $barang->satuan }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-start border-success border-4 ps-3">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Nilai Total</div>
                                <div class="h5 mb-0 fw-bold text-success">
                                    Rp {{ number_format($barang->stok * $barang->harga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-start border-info border-4 ps-3">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Masuk</div>
                                <div class="h5 mb-0 fw-bold text-info">
                                    {{ number_format($barang->transaksi->where('jenis_transaksi', 'masuk')->sum('jumlah')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-start border-warning border-4 ps-3">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Keluar</div>
                                <div class="h5 mb-0 fw-bold text-warning">
                                    {{ number_format($barang->transaksi->where('jenis_transaksi', 'keluar')->sum('jumlah')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transaksi.create-barang-masuk') }}?barang_id={{ $barang->id }}" class="btn btn-outline-success">
                            <i class="fas fa-arrow-up me-2"></i>Tambah Stok
                        </a>
                        @if($barang->stok > 0)
                        <a href="{{ route('transaksi.create-barang-keluar') }}?barang_id={{ $barang->id }}" class="btn btn-outline-info">
                            <i class="fas fa-arrow-down me-2"></i>Kurangi Stok
                        </a>
                        @endif
                        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Edit Barang
                        </a>
                        <form action="{{ route('barang.toggle-status', $barang) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $barang->is_active ? 'secondary' : 'success' }} w-100">
                                <i class="fas fa-{{ $barang->is_active ? 'times' : 'check' }} me-2"></i>
                                {{ $barang->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Detail -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Informasi Detail</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <small class="text-muted">Dibuat pada:</small>
                            <div>{{ $barang->created_at->format('d F Y, H:i') }}</div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Terakhir diupdate:</small>
                            <div>{{ $barang->updated_at->format('d F Y, H:i') }}</div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Total transaksi:</small>
                            <div>{{ $barang->transaksi->count() }} transaksi</div>
                        </div>
                        @if($barang->transaksi->count() > 0)
                        <div class="col-12">
                            <small class="text-muted">Transaksi terakhir:</small>
                            <div>{{ $barang->transaksi->first()->created_at->diffForHumans() }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterTransaksi(jenis) {
    const rows = document.querySelectorAll('.transaksi-row');
    const buttons = document.querySelectorAll('.btn-group-sm .btn');
    
    // Reset button states
    buttons.forEach(btn => {
        btn.classList.remove('btn-success', 'btn-info', 'btn-secondary');
        btn.classList.add('btn-outline-success', 'btn-outline-info', 'btn-outline-secondary');
    });
    
    // Show/hide rows based on filter
    rows.forEach(row => {
        if (jenis === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.jenis === jenis ? '' : 'none';
        }
    });
    
    // Update active button
    if (jenis === 'masuk') {
        buttons[0].classList.remove('btn-outline-success');
        buttons[0].classList.add('btn-success');
    } else if (jenis === 'keluar') {
        buttons[1].classList.remove('btn-outline-info');
        buttons[1].classList.add('btn-info');
    } else {
        buttons[2].classList.remove('btn-outline-secondary');
        buttons[2].classList.add('btn-secondary');
    }
}
</script>
@endsection