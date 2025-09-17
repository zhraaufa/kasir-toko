@extends('layouts.laporan', ['title' => 'Laporan Bulanan'])

@section('content')
<div class="container my-4">

    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-chart-line me-2"></i> Laporan Bulanan
        </h2>
        <p class="text-muted">Bulan: <strong>{{ $bulan }} {{ request()->tahun }}</strong></p>
    </div>

    <!-- Tabel -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i> Detail Transaksi</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Total Transaksi</th>
                        <th>Berhasil</th>
                        <th>Total (Berhasil)</th>
                        <th>Batal</th>
                        <th>Total (Batal)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $key => $row)
                        @php
                            $transaksi_batal = $row->jumlah_transaksi - $row->jumlah_transaksi_berhasil;
                        @endphp
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $row->tgl }}</td>
                            <td><span class="badge bg-secondary">{{ $row->jumlah_transaksi }}</span></td>
                            <td><span class="badge bg-success">{{ $row->jumlah_transaksi_berhasil }}</span></td>
                            <td class="text-end text-success">{{ number_format($row->jumlah_total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-danger">{{ $transaksi_batal }}</span></td>
                            <td class="text-end text-danger">{{ number_format($row->jumlah_total_batal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold text-center">
                    <tr>
                        <td colspan="3">Jumlah Total</td>
                        <td>{{ $penjualan->sum('jumlah_transaksi_berhasil') }}</td>
                        <td class="text-end text-success">{{ number_format($totalBulanan, 0, ',', '.') }}</td>
                        <td>{{ $penjualan->sum('jumlah_transaksi') - $penjualan->sum('jumlah_transaksi_berhasil') }}</td>
                        <td class="text-end text-danger">{{ number_format($totalBatal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
                <i class="fas fa-receipt fa-2x text-primary mb-2"></i>
                <h6>Total Transaksi</h6>
                <h4 class="fw-bold">{{ $penjualan->sum('jumlah_transaksi') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h6>Transaksi Berhasil</h6>
                <h4 class="fw-bold text-success">{{ $penjualan->sum('jumlah_transaksi_berhasil') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                <h6>Transaksi Batal</h6>
                <h4 class="fw-bold text-danger">{{ $penjualan->sum('jumlah_transaksi') - $penjualan->sum('jumlah_transaksi_berhasil') }}</h4>
            </div>
        </div>
    </div>

    <div class="mt-4 text-muted small">
        <p>⚠️ Laporan bulanan hanya menghitung total dari transaksi yang <strong>berhasil</strong>.<br>
        ❌ Transaksi dengan status <strong>BATAL</strong> tidak dihitung dalam pendapatan.</p>
    </div>

</div>
@endsection
