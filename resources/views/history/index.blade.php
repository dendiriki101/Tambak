@extends('layouts.app')

@section('title', 'History')

@section('content')
<div class="container">
    <h1>Riwayat Pesanan</h1>

    @if($histories->count() > 0)
    <table class="table table-striped">
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
            @foreach ($histories as $history)
            <tr>
                <td>{{ $history->product->name }}</td>
                <td>{{ $history->jumlah }}</td>
                <td>Rp{{ number_format($history->total_harga, 0, ',', '.') }}</td>
                <td>{{ $history->status }}</td>
                <td>{{ $history->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Belum ada riwayat pesanan.</p>
    @endif
</div>
@endsection
