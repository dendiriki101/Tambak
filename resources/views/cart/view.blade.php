@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')
<div class="container mt-4">
    <h1 class="display-4 text-center mb-4">Keranjang Saya</h1>

    <!-- Menampilkan Notifikasi -->
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

    @foreach($cartItems as $cartItem)
    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $cartItem->product->image) }}" class="img-fluid rounded-start" alt="{{ $cartItem->product->name }}" style="height: 200px; width: 100%; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $cartItem->product->name }}</h5>
                    <h5 class="card-title">Harga: Rp{{ number_format($cartItem->product->price, 0, ',', '.') }}</h5>
                    <p class="card-text">Jumlah di Keranjang: {{ $cartItem->quantity }}</p>
                    <form action="{{ route('confirm.order') }}" method="POST" class="mb-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $cartItem->product->id }}">
                        <input type="number" name="jumlah" min="1" max="{{ $stock}}" required placeholder="Jumlah" class="form-control" style="width: 100px; weight: 90px display: inline-block;">
                        <button type="submit" class="btn btn-warning btn-sm mt-2">Konfirmasi Pesanan</button>
                    </form>
                    <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
