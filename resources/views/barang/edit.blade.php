@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Barang</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Kelola Barang</a></li>
                    <li class="breadcrumb-item active">Edit: {{ $barang->nama_barang }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('barang.show', $barang) }}" class="btn btn-info">
                <i class="fas fa-eye me-2"></i>Detail
            </a>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form Edit -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Form Edit Barang
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.update', $barang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Kode Barang -->
                            <div class="col-md-4">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" value="{{ $barang->kode_barang }}" readonly>
                                <small class="text-muted">Kode tidak dapat diubah</small>
                            </div>

                            <!-- Nama Barang -->
                            <div class="col-md-8">
                                <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_barang') is-invalid @enderror" 
                                       id="nama_barang" 
                                       name="nama_barang" 
                                       value="{{ old('nama_barang', $barang->nama_barang) }}"
                                       required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6">
                                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('kategori') is-invalid @enderror" 
                                       id="kategori" 
                                       name="kategori" 
                                       value="{{ old('kategori', $barang->kategori) }}"
                                       list="kategori-list"
                                       required>
                                <datalist id="kategori-list">
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori }}">
                                    @endforeach
                                </datalist>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Satuan -->
                            <div class="col-md-6">
                                <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('satuan') is-invalid @enderror" 
                                       id="satuan" 
                                       name="satuan" 
                                       value="{{ old('satuan', $barang->satuan) }}"
                                       list="satuan-list"
                                       required>
                                <datalist id="satuan-list">
                                    <option value="pcs"><option value="kg"><option value="gram">
                                    <option value="liter"><option value="ml"><option value="box">
                                    <option value="pack"><option value="meter"><option value="cm">
                                </datalist>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Harga -->
                            <div class="col-md-6">
                                <label for="harga" class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('harga') is-invalid @enderror" 
                                           id="harga" 
                                           name="harga" 
                                           value="{{ old('harga', $barang->harga) }}"
                                           min="0"
                                           step="0.01"
                                           required>
                                </div>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stok Current -->
                            <div class="col-md-6">
                                <label class="form-label">Stok Saat Ini</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           value="{{ number_format($barang->stok) }} {{ $barang->satuan }}" readonly>
                                    <span class="input-group-text">
                                        @if($barang->stok <= 0)
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @elseif($barang->stok <= 10)
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                        @else
                                            <i class="fas fa-check-circle text-success"></i>
                                        @endif
                                    </span>
                                </div>
                                <small class="text-muted">Stok diubah melalui transaksi</small>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           {{ old('is_active', $barang->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Barang Aktif</strong>
                                        <small class="text-muted d-block">Barang aktif dapat digunakan dalam transaksi</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <!-- Info Barang -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Informasi Barang</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted">Dibuat:</small>
                            <div class="fw-bold">{{ $barang->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Diupdate:</small>
                            <div class="fw-bold">{{ $barang->updated_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Total Transaksi:</small>
                            <div class="fw-bold">{{ $barang->transaksi()->count() }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Nilai Total:</small>
                            <div class="fw-bold text-success">{{ $barang->formatted_harga }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terakhir -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Transaksi Terakhir</h6>
                </div>
                <div class="card-body">
                    @php
                        $lastTransaksi = $barang->transaksi()->latest()->limit(3)->get();
                    @endphp
                    
                    @if($lastTransaksi->count() > 0)
                        @foreach($lastTransaksi as $transaksi)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="fw-bold">{{ ucfirst($transaksi->jenis_transaksi) }}</div>
                                <small class="text-muted">{{ $transaksi->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $transaksi->jenis_transaksi === 'masuk' ? 'success' : 'info' }}">
                                    {{ $transaksi->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $transaksi->jumlah }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                        <div class="mt-3">
                            <a href="{{ route('barang.show', $barang) }}" class="btn btn-sm btn-outline-primary w-100">
                                Lihat Semua Transaksi
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number input
    document.getElementById('harga').addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
});
</script>
@endsection