@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container">
    <h1>My Bookings</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Status</th>
                <th>Penjual</th>
                <th>Lokasi</th>
                <th>Tanggal Booking</th>
                <th>Tanggal Berakir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
            <tr>
                <td>{{ $booking->product->name }}</td>
                <td>{{ $booking->status }}</td>
                <td>{{ $booking->seller->name}}</td>
                <td>{{ $booking->location }}</td>
                <td>{{ $booking->created_at->format('d M Y') }}</td>
                <td>{{ $booking->auction_end}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
