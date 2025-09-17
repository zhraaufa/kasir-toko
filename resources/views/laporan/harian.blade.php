@extends('layouts.laporan', ['title' => 'Laporan Harian'])

@section('content')
<div class="container my-4">

    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-calendar-day me-2"></i> Laporan Harian
        </h2>
        <p class="text-muted">Tanggal: <strong>{{ date('d/m/Y', strtotime(request()->tanggal)) }}</strong></p>
    </div>

    <!-- Tabel -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i> Detail Transaksi</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Kasir</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $key => $row)
                        <tr class="{{ $row->status == 'batal' ? 'table-light text-muted' : '' }}">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $row->nomor_transaksi }}</td>
                            <td>{{ $row->nama_pelanggan ?? 'Pelanggan' }}</td>
                            <td>{{ $row->nama_kasir }}</td>
                            <td class="text-center">
                                @if($row->status == 'batal')
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> {{ ucwords($row->status) }}</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> {{ ucwords($row->status) }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ date('H:i:s', strtotime($row->tanggal)) }}</td>
                            <td class="text-end">
                                @if($row->status == 'batal')
                                    <span class="text-decoration-line-through text-muted">
                                        {{ number_format($row->total, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="fw-bold text-success">
                                        {{ number_format($row->total, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="fw-bold text-center">
                    <tr class="table-success">
                        <td colspan="6">Jumlah Total (Transaksi Berhasil)</td>
                        <td class="text-end text-success">{{ number_format($totalHarian, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-info">
                        <td colspan="6">Total Semua Transaksi (Termasuk Batal)</td>
                        <td class="text-end">{{ number_format($penjualan->sum('total'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm text-center p-3">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h6>Total Transaksi Berhasil</h6>
                <h4 class="fw-bold text-success">{{ number_format($totalHarian, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm text-center p-3">
                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                <h6>Total Semua Transaksi</h6>
                <h4 class="fw-bold text-primary">{{ number_format($penjualan->sum('total'), 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="mt-3 text-muted small">
        <p>⚠️ Transaksi dengan status <strong>BATAL</strong> ditampilkan abu-abu & dicoret.<br>
        ✅ Total hanya menghitung transaksi yang <strong>berhasil</strong>.</p>
    </div>

</div>
@endsection
