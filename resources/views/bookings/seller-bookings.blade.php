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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookingUsers as $bookingUser)
                <tr>
                    <td>
                        @php
                            // Ambil produk yang terkait dengan booking
                            $product = \App\Models\Product::find($bookingUser->product_id);
                        @endphp
                        {{ $product ? $product->name : 'Produk tidak ditemukan' }}
                    </td>
                    <td>
                        @php
                            // Ambil user (pembeli) yang terkait dengan booking_user
                            $buyer = \App\Models\User::find($bookingUser->user_id);
                        @endphp
                        {{ $buyer ? $buyer->name : 'Pembeli tidak ditemukan' }}
                    </td>
                    <td>{{ $bookingUser->status }}</td>
                    <td>{{ $bookingUser->booking_status }}</td>
                    <td>{{ \Carbon\Carbon::parse($bookingUser->booking_created_at)->format('d M Y') }}</td>
                    <td>
                        @if ($bookingUser->status !== 'Pesanan Diterima' && $bookingUser->status !== 'Dibatalkan')
                        <form action="{{ route('confirm.booking', $bookingUser->booking_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Konfirmasi</button>
                        </form>
                        <form action="{{ route('cancel.booking', [$bookingUser->booking_id, $bookingUser->user_id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                        </form>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
