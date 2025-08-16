<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar - {{ config('app.name', 'COMFEED JAPFA') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm 1cm 1cm 1cm;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2563EB;
        }
        
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #4b5563;
            font-weight: normal;
        }
        
        .header .company-info {
            font-size: 8px;
            color: #6b7280;
            margin-top: 5px;
        }
        
        .filter-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 8px;
        }
        
        .filter-info strong {
            color: #374151;
        }
        
        .summary-stats {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .summary-stats .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 8px;
            text-align: center;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
        }
        
        .summary-stats .stat-box:first-child {
            border-radius: 5px 0 0 5px;
        }
        
        .summary-stats .stat-box:last-child {
            border-radius: 0 5px 5px 0;
        }
        
        .stat-label {
            font-size: 7px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .stat-value {
            font-size: 10px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 2px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        
        table.main-table {
            border: 1px solid #d1d5db;
        }
        
        th {
            background-color: #2563EB;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 7px;
            text-transform: uppercase;
            border: 1px solid #1d4ed8;
        }
        
        td {
            padding: 4px 3px;
            border: 1px solid #d1d5db;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tr:nth-child(odd) {
            background-color: white;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        .currency {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .badge-keluar {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .no-column {
            width: 4%;
            text-align: center;
        }
        
        .kode-column {
            width: 12%;
        }
        
        .barang-column {
            width: 20%;
        }
        
        .qty-column {
            width: 10%;
            text-align: center;
        }
        
        .harga-column {
            width: 12%;
        }
        
        .total-column {
            width: 12%;
        }
        
        .customer-column {
            width: 15%;
        }
        
        .user-column {
            width: 10%;
        }
        
        .tanggal-column {
            width: 10%;
            text-align: center;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding: 5px;
            background-color: white;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-break {
            page-break-inside: avoid;
        }
        
        .total-summary {
            margin-top: 10px;
            padding: 8px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 5px;
        }
        
        .total-summary table {
            margin: 0;
        }
        
        .total-summary th,
        .total-summary td {
            border: none;
            padding: 3px 5px;
        }
        
        .total-summary th {
            background-color: transparent;
            color: #374151;
            font-size: 8px;
        }
        
        .grand-total {
            font-weight: bold;
            font-size: 9px;
            color: #dc2626;
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }
        
        .empty-state h3 {
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .empty-state p {
            font-size: 9px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>{{ config('app.name', 'COMFEED JAPFA') }}</h1>
        <h2>Laporan Barang Keluar</h2>
        <div class="company-info">
            Sistem Manajemen Inventory | Dicetak pada: {{ now()->format('d F Y - H:i:s') }} WIB
        </div>
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <strong>FILTER LAPORAN:</strong>
        @if(isset($filters))
            Periode: 
            @if($filters['tanggal_dari'] && $filters['tanggal_sampai'])
                {{ \Carbon\Carbon::parse($filters['tanggal_dari'])->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($filters['tanggal_sampai'])->format('d/m/Y') }}
            @elseif($filters['tanggal_dari'])
                Dari {{ \Carbon\Carbon::parse($filters['tanggal_dari'])->format('d/m/Y') }}
            @elseif($filters['tanggal_sampai'])
                Sampai {{ \Carbon\Carbon::parse($filters['tanggal_sampai'])->format('d/m/Y') }}
            @else
                Semua Data
            @endif
            
            @if($filters['barang_id'])
                @php
                    $selectedBarang = $barangs->where('id', $filters['barang_id'])->first();
                @endphp
                | Barang: {{ $selectedBarang ? $selectedBarang->kode_barang . ' - ' . $selectedBarang->nama_barang : 'Tidak ditemukan' }}
            @else
                | Barang: Semua Barang
            @endif
        @else
            Semua Data | Semua Barang
        @endif
    </div>

    <!-- Summary Statistics -->
    @if($transaksis->count() > 0)
    <div class="summary-stats">
        <div class="stat-box">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ number_format($transaksis->count()) }} Transaksi</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Quantity</div>
            <div class="stat-value">{{ number_format($transaksis->sum('jumlah')) }} Unit</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Nilai Keluar</div>
            <div class="stat-value">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</div>
        </div>
    </div>
    @endif

    <!-- Main Data Table -->
    @if($transaksis->count() > 0)
        <table class="main-table">
            <thead>
                <tr>
                    <th class="no-column">No</th>
                    <th class="kode-column">Kode Transaksi</th>
                    <th class="tanggal-column">Tanggal</th>
                    <th class="barang-column">Barang</th>
                    <th class="qty-column">Qty</th>
                    <th class="harga-column">Harga Satuan</th>
                    <th class="total-column">Total Harga</th>
                    <th class="customer-column">Customer</th>
                    <th class="user-column">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $index => $transaksi)
                    <tr class="no-break">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge-keluar">{{ $transaksi->kode_transaksi }}</span>
                        </td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}
                        </td>
                        <td>
                            <strong>{{ $transaksi->barang->nama_barang ?? 'N/A' }}</strong><br>
                            <small style="color: #6b7280;">{{ $transaksi->barang->kode_barang ?? 'N/A' }}</small><br>
                            <small style="color: #6b7280;">{{ $transaksi->barang->kategori ?? 'N/A' }}</small>
                        </td>
                        <td class="text-center">
                            <strong>{{ number_format($transaksi->jumlah, 0, ',', '.') }}</strong><br>
                            <small>{{ $transaksi->barang->satuan ?? 'Unit' }}</small>
                        </td>
                        <td class="currency">
                            Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="currency">
                            <strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            {{ $transaksi->customer ?? '-' }}
                            @if($transaksi->keterangan)
                                <br><small style="color: #6b7280; font-style: italic;">{{ Str::limit($transaksi->keterangan, 30) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $transaksi->user->name ?? 'System' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Total -->
        <div class="total-summary">
            <table>
                <tr>
                    <th class="text-left" style="width: 70%;">TOTAL KESELURUHAN:</th>
                    <td class="text-right grand-total">
                        {{ number_format($transaksis->count()) }} Transaksi | 
                        {{ number_format($transaksis->sum('jumlah')) }} Unit | 
                        Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Rata-rata per Transaksi:</th>
                    <td class="text-right">
                        Rp {{ number_format($transaksis->avg('total_harga'), 0, ',', '.') }}
                    </td>
                </tr>
                @if($transaksis->count() > 1)
                <tr>
                    <th class="text-left">Transaksi Terbesar:</th>
                    <td class="text-right">
                        Rp {{ number_format($transaksis->max('total_harga'), 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Transaksi Terkecil:</th>
                    <td class="text-right">
                        Rp {{ number_format($transaksis->min('total_harga'), 0, ',', '.') }}
                    </td>
                </tr>
                @endif
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <h3>Tidak Ada Data Transaksi</h3>
            <p>Tidak ada transaksi barang keluar yang sesuai dengan filter yang dipilih.</p>
            <p>Silakan ubah filter atau tambah transaksi baru melalui sistem.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>
            Laporan Barang Keluar - {{ config('app.name', 'COMFEED JAPFA') }} | 
            Dicetak pada: {{ now()->format('d F Y - H:i:s') }} WIB | 
            Halaman {PAGE_NUM} dari {PAGE_COUNT}
        </div>
        <div style="margin-top: 2px;">
            <small>Dokumen ini digenerate secara otomatis oleh sistem dan sah tanpa tanda tangan</small>
        </div>
    </div>
</body>
</html>