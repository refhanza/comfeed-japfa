@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Daftar Transaksi</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('transaksi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Transaksi
                        </a>
                        <a href="{{ route('transaksi.barang-masuk') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Barang Masuk
                        </a>
                        <a href="{{ route('transaksi.barang-keluar') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Barang Keluar
                        </a>
                        <a href="{{ route('transaksi.laporan') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Laporan
                        </a>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <form method="GET" action="{{ route('transaksi.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                            <select name="jenis" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua</option>
                                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                            <select name="barang_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4 flex justify-start space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filter
                            </button>
                            <a href="{{ route('transaksi.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Kode</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Tanggal</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Barang</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Jenis</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Jumlah</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Harga Satuan</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Total</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">User</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $transaksi)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b text-sm">{{ $transaksi->kode_transaksi }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ $transaksi->formatted_tanggal }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ $transaksi->barang->nama_barang }}</td>
                                    <td class="py-2 px-4 border-b text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaksi->jenis_transaksi == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($transaksi->jenis_transaksi) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border-b text-sm">{{ $transaksi->jumlah }} {{ $transaksi->barang->satuan }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ $transaksi->formatted_harga_satuan }}</td>
                                    <td class="py-2 px-4 border-b text-sm font-medium">{{ $transaksi->formatted_total_harga }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ $transaksi->user->name }}</td>
                                    <td class="py-2 px-4 border-b text-sm">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('transaksi.show', $transaksi) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                                            <a href="{{ route('transaksi.edit', $transaksi) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                            <form action="{{ route('transaksi.destroy', $transaksi) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 px-4 text-center text-gray-500">
                                        Tidak ada transaksi ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $transaksis->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection