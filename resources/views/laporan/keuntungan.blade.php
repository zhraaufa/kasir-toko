@extends('layouts.main', ['title' => 'Laporan Keuntungan'])

@section('title-content')
    <i class="fas fa-chart-line mr-2"></i> Laporan Keuntungan
@endsection

@section('content')
<div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="fas fa-coins me-2"></i> 
            Keuntungan Bulan {{ $bulanNama }} {{ $tahun }}
        </h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="bg-light text-primary">
                <tr>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Pendapatan</th>
                    <th>Laba</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detail as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $row->produk }}</td>
                        <td>{{ $row->jumlah }}</td>
                        <td><span class="badge bg-secondary">Rp {{ number_format($row->harga_modal, 0, ',', '.') }}</span></td>
                        <td><span class="badge bg-info">Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</span></td>
                        <td><span class="text-dark">Rp {{ number_format($row->pendapatan, 0, ',', '.') }}</span></td>
                        <td><span class="text-success fw-bold">Rp {{ number_format($row->laba, 0, ',', '.') }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted fst-italic">Tidak ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="row text-center mt-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Total Pendapatan</h6>
                        <h4 class="text-primary">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Total Modal</h6>
                        <h4 class="text-danger">Rp {{ number_format($totalModal, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Total Laba</h6>
                        <h4 class="text-success fw-bold">Rp {{ number_format($totalLaba, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
