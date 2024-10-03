@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">


    @if(Auth::user()->can('pembeli'))
    <!-- Filter Section -->
    <h1 class="display-4 text-center mb-4">Dashboard Pembeli</h1>

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

    <div class="card mb-4">
        <div class="card-header">
            Filter Produk
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="row">
                    <!-- Filter by Jenis Ikan -->
                    <div class="col-md-3 mb-3">
                        <label for="jenis_ikan" class="form-label">Jenis Ikan</label>
                        <select class="form-select" id="jenis_ikan" name="jenis_ikan">
                            <option value="">Semua</option>
                            @foreach($jenisIkans as $ikan)
                                <option value="{{ $ikan->jenis_ikan }}" {{ request('jenis_ikan') == $ikan->jenis_ikan ? 'selected' : '' }}>
                                    {{ ucfirst($ikan->jenis_ikan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Location -->
                    <div class="col-md-3 mb-3">
                        <label for="city" class="form-label">Lokasi</label>
                        <select class="form-select" id="city" name="city">
                            <option value="">Semua</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->city }}" {{ request('city') == $city->city ? 'selected' : '' }}>
                                    {{ ucfirst($city->city) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Harga -->
                    <div class="col-md-3 mb-3">
                        <label for="price_min" class="form-label">Harga Minimum</label>
                        <input type="number" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="price_max" class="form-label">Harga Maksimum</label>
                        <input type="number" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}">
                    </div>

                    <!-- Filter by Auction Dates -->
                    <div class="col-md-3 mb-3">
                        <label for="auction_start" class="form-label">Jadwal Panen Mulai</label>
                        <input type="date" class="form-control" id="auction_start" name="auction_start" value="{{ request('auction_start') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="auction_end" class="form-label">Jadwal Panen Selesai</label>
                        <input type="date" class="form-control" id="auction_end" name="auction_end" value="{{ request('auction_end') }}">
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Section -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($products as $product)
        <div class="col mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 80) }}</p>
                    <p class="card-text">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="card-text">Jumlah Tersedia: {{ $product->activeBooking->jumlah ?? '0' }}</p>
                </div>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">Detail</a>
                <div class="card-footer d-flex justify-content-between">
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <!-- Mengatur jumlah default ke nol -->
                        <input type="hidden" name="jumlah" value="0">
                        <button type="submit" class="btn btn-warning btn-sm mt-2">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada produk yang ditemukan sesuai filter.</p>
        @endforelse
    </div>






    @elsecan('penjual')
    @can('penjual')
    <h1 class="display-4 text-center mb-4">Dashboard Pembeli</h1>
    <div class="d-flex justify-content-center mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-success btn-lg">Tambah Produk Baru</a>
    </div>
    @endcan
    <h2>Produk Saya</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 80) }}</p>
                    <p class="card-text">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit Produk</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endcan
</div>
@endsection
