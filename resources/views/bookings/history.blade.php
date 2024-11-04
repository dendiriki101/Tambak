@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@section('content')

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .table-primary {
        background-color: #f8f9fa !important; /* Warna latar belakang untuk header tabel */
    }
    .badge {
        font-size: 0.9rem; /* Ukuran font untuk badge */
    }
    .display-4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
    }
</style>
<div class="container mt-4">
    <h1 class="display-4 text-center mb-4 text-primary">Riwayat Pemesanan</h1>

    @if($histories->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Tidak ada riwayat pemesanan yang ditemukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr>
                            <td>{{ $history->product->name }}</td>
                            <td>{{ $history->jumlah }}</td>
                            <td>Rp{{ number_format($history->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $history->status == 'Dikonfirmasi' ? 'bg-success' : ($history->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($history->status) }}
                                </span>
                            </td>
                            <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif
</div>


@endsection
