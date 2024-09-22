@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container mt-4">
    <h1 class="display-4 text-center mb-4">My Bookings</h1>

    @if($product)
    <div class="card mb-4">
        <div class="card-header">Booking Produk</div>
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-text">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</p>
            <form action="{{ route('confirm.order') }}" method="POST" class="mb-0">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="jumlah" min="1" required placeholder="Jumlah" class="form-control" style="width: 100px;">
                <button type="submit" class="btn btn-warning btn-sm">Konfirmasi Pesanan</button>
            </form>
        </div>
    </div>
    @else
    <p class="text-center">Silakan pilih produk untuk booking.</p>
    @endif
</div>
@endsection
