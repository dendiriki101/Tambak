@extends('layouts.app')

@section('title', 'Daftar Booking')

@section('content')
<div class="container">
    <h1 class="mb-4">Produk Terdaftar untuk Lelang</h1>

    <!-- Tombol untuk Daftarkan Produk Baru -->
    <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-4">
        <i class="bi bi-plus-circle me-2"></i> Daftarkan Produk Baru
    </a>

    @if ($bookings->isEmpty())
        <div class="alert alert-info">
            <p>Belum ada produk yang terdaftar untuk lelang.</p>
        </div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Ikan yang Dilelang</th>
                    <th>Penjual</th>
                    <th>Jumlah</th>
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
                    <td>{{ $booking->jumlah }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->auction_start)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->auction_end)->format('d M Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            @if ($booking->status === 'aktif')
                                <form action="{{ route('bookings.complete', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Complete
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
