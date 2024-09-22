@extends('layouts.app')

@section('title', 'Seller Bookings')

@section('content')
<div class="container">
    <h1>Bookings on My Products</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Pembeli</th>
                <th>Status Booking</th>
                <th>Tanggal Booking</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
                @foreach ($booking->users as $user)
                <tr>
                    <td>{{ $booking->product->name }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                    <td>
                        @if ($booking->status !== 'Dikonfirmasi') <!-- Cek status booking -->
                        <form action="{{ route('confirm.booking', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Konfirmasi</button>
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
