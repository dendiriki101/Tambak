@extends('layouts.app')

@section('title', 'Seller Bookings')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h1>Bookings on My Products</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Pembeli</th>
                <th>Status</th>
                <th>Status Booking</th>
                <th>Tanggal Booking</th>
                <th>Jumlah</th> <!-- Kolom untuk jumlah -->
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
                @foreach ($booking->users as $user)
                <tr>
                    {{-- {{ dd($user->pivot) }} <!-- Cek isi pivot di sini --> --}}
                    <td>{{ $booking->product->name }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->pivot->status }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                    <td>
                        @php
                            // Ambil jumlah dari history yang terkait
                            $history = $booking->product->history->where('product_id', $booking->product_id)->first();
                        @endphp
                        {{ $history ? $history->jumlah : 'N/A' }} <!-- Tampilkan jumlah -->
                    </td>
                    <td>
                        @if ($user->pivot->status !== 'Pesanan Diterima' && $user->pivot->status !== 'Dibatalkan') <!-- Cek status booking -->
                        <form action="{{ route('confirm.booking', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Konfirmasi</button>
                        </form>
                        <form action="{{ route('cancel.booking', [$user->pivot->booking_id, $user->pivot->user_id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE') <!-- Menggunakan method DELETE untuk pembatalan -->
                            <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                        </form>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled>Dikonfirmasi</button> <!-- Tombol dinonaktifkan -->
                        @endif
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
