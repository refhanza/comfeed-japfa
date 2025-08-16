@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Tambah Barang Masuk</h1>
                    <a href="{{ route('transaksi.barang-masuk') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('transaksi.store-barang-masuk') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Barang -->
                        <div>
                            <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">Barang *</label>
                            <select name="barang_id" id="barang_id" required 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}
                                            data-harga="{{ $barang->harga }}" data-satuan="{{ $barang->satuan }}">
                                        {{ $barang->nama_barang }} ({{ $barang->kode_barang }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                            <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah') }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <div id="satuan-info" class="text-sm text-gray-500 mt-1"></div>
                        </div>

                        <!-- Harga Satuan -->
                        <div>
                            <label for="harga_satuan" class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan *</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" min="0" step="0.01" value="{{ old('harga_satuan') }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div>
                            <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi *</label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                            <input type="text" name="supplier" id="supplier" value="{{ old('supplier') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Nama supplier (opsional)">
                        </div>

                        <!-- Total Harga (readonly) -->
                        <div>
                            <label for="total_harga" class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                            <input type="text" id="total_harga" readonly
                                   class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50">
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('transaksi.barang-masuk') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Barang Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    const jumlahInput = document.getElementById('jumlah');
    const hargaInput = document.getElementById('harga_satuan');
    const totalInput = document.getElementById('total_harga');
    const satuanInfo = document.getElementById('satuan-info');

    // Auto-fill harga and show satuan info
    barangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const harga = selectedOption.dataset.harga;
            const satuan = selectedOption.dataset.satuan;
            
            hargaInput.value = harga;
            satuanInfo.textContent = `Satuan: ${satuan}`;
            
            calculateTotal();
        } else {
            hargaInput.value = '';
            satuanInfo.textContent = '';
            totalInput.value = '';
        }
    });

    // Calculate total
    function calculateTotal() {
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const total = jumlah * harga;
        totalInput.value = total > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(total) : '';
    }

    jumlahInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);

    // Trigger barang change on page load if value exists
    if (barangSelect.value) {
        barangSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection