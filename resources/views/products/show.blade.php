@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container py-5">

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

    <div class="row">
        <!-- Bagian gambar produk -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                @if($product->image || $product->image2 || $product->image3 || $product->image4 || $product->image5)
                    <!-- Carousel untuk menampilkan banyak gambar -->
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if($product->image)
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endif
                            @if($product->image2)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $product->image2) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endif
                            @if($product->image3)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $product->image3) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endif
                            @if($product->image4)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $product->image4) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endif
                            @if($product->image5)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $product->image5) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @else
                    <!-- Jika hanya ada satu gambar -->
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                @endif
            </div>
        </div>

        <!-- Bagian detail produk -->
        <div class="col-md-6">
            <div class="product-details bg-light p-4 shadow-sm rounded">
                <h1 class="display-5 fw-bold">{{ $product->name }}</h1>
                <p class="text-muted">{{ $product->description }}</p>
                <div class="product-location my-3">
                    <i class="bi bi-geo-alt-fill"></i>
                    <strong>Lokasi:</strong> {{ $product->activeBooking ? $product->activeBooking->location : 'Lokasi tidak tersedia' }}
                </div>
                <div class="product-seller my-3">
                    <i class="bi bi-person-circle"></i>
                    <strong>Penjual:</strong> {{ $product->seller->name }}
                </div>
                <div class="product-price my-3">
                    <i class="bi bi-currency-dollar"></i>
                    <strong>Harga:</strong> <span class="text-success">Rp{{ number_format($product->price, 2) }}</span>
                </div>

                <!-- Tombol aksi -->
                <div class="d-flex justify-content-start mt-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <!-- Mengatur jumlah default ke nol -->
                        <input type="hidden" name="jumlah" value="0">
                        <button type="submit" class="btn btn-primary btn-lg me-3">
                            <i class="bi bi-cart-fill"></i> Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
