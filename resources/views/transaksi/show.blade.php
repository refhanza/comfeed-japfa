@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Detail Transaksi</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('transaksi.edit', $transaksi) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('transaksi.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Success Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Informasi Transaksi -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Kode Transaksi:</span>
                                <span class="text-gray-900">{{ $transaksi->kode_transaksi }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Jenis Transaksi:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaksi->jenis_transaksi == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transaksi->jenis_transaksi) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Tanggal Transaksi:</span>
                                <span class="text-gray-900">{{ $transaksi->formatted_tanggal }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Dibuat oleh:</span>
                                <span class="text-gray-900">{{ $transaksi->user->name }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Waktu Dibuat:</span>
                                <span class="text-gray-900">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            @if($transaksi->updated_at != $transaksi->created_at)
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Terakhir Diupdate:</span>
                                <span class="text-gray-900">{{ $transaksi->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Barang -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Barang</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Kode Barang:</span>
                                <span class="text-gray-900">{{ $transaksi->barang->kode_barang }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Nama Barang:</span>
                                <span class="text-gray-900">{{ $transaksi->barang->nama_barang }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Kategori:</span>
                                <span class="text-gray-900">{{ $transaksi->barang->kategori ?? '-' }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Stok Saat Ini:</span>
                                <span class="text-gray-900">{{ $transaksi->barang->stok }} {{ $transaksi->barang->satuan }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Transaksi -->
                <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Transaksi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $transaksi->jumlah }}</div>
                            <div class="text-sm text-gray-600">Jumlah ({{ $transaksi->barang->satuan }})</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $transaksi->formatted_harga_satuan }}</div>
                            <div class="text-sm text-gray-600">Harga Satuan</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $transaksi->formatted_total_harga }}</div>
                            <div class="text-sm text-gray-600">Total Harga</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-600">
                                {{ $transaksi->jenis_transaksi == 'masuk' ? '+' : '-' }}{{ $transaksi->jumlah }}
                            </div>
                            <div class="text-sm text-gray-600">Perubahan Stok</div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @if($transaksi->supplier)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Supplier</h2>
                        <p class="text-gray-900">{{ $transaksi->supplier }}</p>
                    </div>
                    @endif

                    @if($transaksi->customer)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Customer</h2>
                        <p class="text-gray-900">{{ $transaksi->customer }}</p>
                    </div>
                    @endif

                    @if($transaksi->keterangan)
                    <div class="bg-gray-50 p-6 rounded-lg {{ !$transaksi->supplier && !$transaksi->customer ? 'lg:col-span-2' : '' }}">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Keterangan</h2>
                        <p class="text-gray-900">{{ $transaksi->keterangan }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <form action="{{ route('transaksi.destroy', $transaksi) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikembalikan ke kondisi sebelumnya.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Hapus Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection