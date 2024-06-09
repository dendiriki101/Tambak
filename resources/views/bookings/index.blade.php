@extends('layouts.app')

@section('title', 'Daftar Booking')

@section('content')
<div class="container">
    <h1>Produk Terdaftar untuk Lelang</h1>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Daftarkan Produk Baru</a>

    @if ($bookings->isEmpty())
        <p>Belum ada produk yang terdaftar untuk lelang.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ikan yang Dilelang</th>
                    <th>Penjual</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->product->name }}</td>
                    <td>{{ $booking->product->seller->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->auction_start)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->auction_end)->format('d M Y') }}</td>
                    <td>

                        @if ($booking->status === 'aktif')
                        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <form action="{{ route('bookings.complete', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Complete</button>
                        </form>
                        @else
                        <span class="text-success">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
