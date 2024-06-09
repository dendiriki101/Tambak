@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="container">
    <h1>Detail Booking</h1>
    <div>
        <p>ID: {{ $booking->id }}</p>
        <p>Produk: {{ $booking->product->name }}</p>
        <p>Penjual: {{ $booking->product->seller->name }}</p>
        <p>Lokasi: {{ $booking->location }}</p>
        <p>{{ \Carbon\Carbon::parse($booking->auction_start)->format('d M Y') }}</p>
        <p>{{ \Carbon\Carbon::parse($booking->auction_end)->format('d M Y') }}</p>
    </div>
</div>
@endsection
