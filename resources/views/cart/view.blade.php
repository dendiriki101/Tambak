@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    .display-4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
    }
    .card-title {
        font-size: 1.25rem;
    }
    .btn-warning, .btn-danger {
        font-weight: 600;
    }
</style>

<div class="container mt-4">
    <h1 class="display-4 text-center mb-5 text-primary">Keranjang Saya</h1>

    <!-- Menampilkan Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-pill shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-pill shadow-sm" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @foreach($cartItems as $cartItem)
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $cartItem->product->image) }}" class="img-fluid rounded-start" alt="{{ $cartItem->product->name }}" style="height: 100%; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title fw-bold text-primary">{{ $cartItem->product->name }}</h4>
                    <h5 class="card-title text-success mb-3">Harga: Rp{{ number_format($cartItem->product->price, 0, ',', '.') }}</h5>
                    <p class="card-text mb-1 text-muted">Jumlah di Keranjang: <strong>{{ $cartItem->quantity }}</strong></p>
                    <p class="card-text mb-1 text-muted">Jumlah Tersedia: <strong>{{ $stocks[$cartItem->id] ?? 0 }}</strong></p>

                    <!-- Tampilkan pesan jika di luar tanggal lelang -->
                    @if(isset($auctionStatus[$cartItem->id]) && !$auctionStatus[$cartItem->id])
                        <p class="text-danger"><strong>Produk masih belum bisa dipesan karena tidak sesuai dengan tanggal lelang.</strong></p>
                    @else
                        <!-- Form Konfirmasi Pesanan -->
                        <form action="{{ route('confirm.order') }}" method="POST" class="d-inline-block me-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $cartItem->product->id }}">
                            <div class="d-flex align-items-center">
                                <input type="number" name="jumlah" min="1" max="{{ $stocks[$cartItem->id] ?? 0 }}" required placeholder="Jumlah" class="form-control form-control-sm me-2" style="width: 80px;">
                                <button type="submit" class="btn btn-warning btn-sm rounded-pill">Konfirmasi Pesanan</button>
                            </div>
                        </form>
                    @endif

                    <!-- Form Hapus dari Keranjang -->
                    <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach


</div>
@endsection


