@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="container mt-4">
    <h1 class="display-4">Riwayat Pemesanan</h1>

    @if($histories->isEmpty())
        <p>Tidak ada riwayat pemesanan yang ditemukan.</p>
    @else
        <table class="table">
            <thead>
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
                        <td>{{ ucfirst($history->status) }}</td>
                        <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
