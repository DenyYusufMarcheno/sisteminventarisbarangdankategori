@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<section class="section">
    <!-- Filter Periode -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                    <input type="date" 
                           class="form-control" 
                           id="tanggal_dari" 
                           name="tanggal_dari" 
                           value="{{ $tanggalDari }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" 
                           class="form-control" 
                           id="tanggal_sampai" 
                           name="tanggal_sampai" 
                           value="{{ $tanggalSampai }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang Masuk</h6>
                    <h3 class="text-success">{{ number_format($totalMasuk) }}</h3>
                    <small class="text-muted">Periode: {{ \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang Keluar</h6>
                    <h3 class="text-danger">{{ number_format($totalKeluar) }}</h3>
                    <small class="text-muted">Periode: {{ \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Selisih</h6>
                    <h3 class="{{ ($totalMasuk - $totalKeluar) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($totalMasuk - $totalKeluar) }}
                    </h3>
                    <small class="text-muted">Masuk - Keluar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Kategori</h6>
                    <h3 class="text-primary">{{ $laporanKategori->count() }}</h3>
                    <small class="text-muted">Kategori Aktif</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Stok Barang -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>Laporan Stok Barang
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanStok as $index => $barang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->kategori->nama_kategori }}</td>
                            <td>
                                <strong>{{ $barang->stok }}</strong> {{ $barang->satuan }}
                            </td>
                            <td>{{ $barang->harga_format }}</td>
                            <td>
                                @if($barang->stok == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($barang->stok < 10)
                                    <span class="badge bg-warning">Rendah</span>
                                @else
                                    <span class="badge bg-success">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Laporan per Kategori -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-folder me-2"></i>Laporan per Kategori
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th>Jumlah Jenis Barang</th>
                            <th>Total Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanKategori as $index => $kategori)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>
                                <span class="badge bg-info">{{ $kategori->barangs_count }} Jenis</span>
                            </td>
                            <td>
                                <strong>{{ number_format($kategori->barangs_sum_stok ??  0) }}</strong> Unit
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Barang Terpopuler -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-graph-up me-2"></i>10 Barang Paling Sering Transaksi
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Total Transaksi</th>
                            <th>Total Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangTerpopuler as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->barang->nama_barang }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $item->total_transaksi }} Transaksi</span>
                            </td>
                            <td>
                                <strong>{{ number_format($item->total_jumlah) }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection