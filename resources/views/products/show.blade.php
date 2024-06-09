@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="Product Image" style="height: 300px; object-fit: cover;">
    <p>{{ $product->description }}</p>
    <p>Lokasi: {{ $product->activeBooking ? $product->activeBooking->location : 'Lokasi tidak tersedia' }}</p>
    <p>Penjual: {{ $product->seller->name }}</p>
    <p>Harga: Rp{{ number_format($product->price, 2) }}</p>
</div>
@endsection
