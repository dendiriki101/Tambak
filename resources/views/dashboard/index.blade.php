@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem; /* Rounded corners */
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2); /* Shadow on hover */
    }
    .card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.4rem;
        color: #007bff; /* Blue color for titles */
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
    }
    .btn-outline-primary {
        border: 2px solid #007bff;
        color: #007bff;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }
    .btn {
        font-size: 1rem; /* Slightly larger buttons */
        font-weight: 600;
    }
    .card-footer {
        background-color: #f8f9fa; /* Light background for footer */
        border-top: 1px solid #dee2e6; /* Border for separation */
    }
    .filter-card {
        background-color: #f9f9f9; /* Light background for filter card */
        border: 1px solid #dee2e6; /* Border for filter card */
    }
    .filter-title {
        font-weight: bold;
    }
    .display-4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
    }
</style>

<div class="container mt-4">
    @if(Auth::user()->can('pembeli'))
        <!-- Filter Section -->
        <h1 class="display-4 text-center mb-4 text-primary ">Dashboard Pembeli</h1>

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

        <div class="card mb-4 filter-card">
            <div class="card-header filter-title">
                Filter Produk
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="row">
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

                        <div class="col-md-3 mb-3">
                            <label for="price_min" class="form-label">Harga Minimum</label>
                            <input type="number" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="price_max" class="form-label">Harga Maksimum</label>
                            <input type="number" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="auction_start" class="form-label">Jadwal Panen Mulai</label>
                            <input type="date" class="form-control" id="auction_start" name="auction_start" value="{{ request('auction_start') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="auction_end" class="form-label">Jadwal Panen Selesai</label>
                            <input type="date" class="form-control" id="auction_end" name="auction_end" value="{{ request('auction_end') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ request('jumlah') }}">
                        </div>

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
                    <div class="card h-100 shadow-lg border-0">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Kota: {{ $product->activeBooking->city }}</p>
                            <p class="card-text">Kecamatan: {{ $product->activeBooking->subdistrict }}</p>
                            <p class="card-text">Penjual: {{ $product->seller->name }}</p>
                            <p class="card-text text-success fw-semibold">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="card-text">Jumlah Tersedia: {{ $product->activeBooking->jumlah ?? '0' }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary rounded-pill">Detail</a>
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="jumlah" value="0">
                                <button type="submit" class="btn btn-primary rounded-pill">Tambah ke Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Tidak ada produk ditemukan.</p>
            @endforelse
        </div>

    @elsecan('penjual')
        <h1 class="display-4 text-center mb-4">Dashboard Penjual</h1>
        <div class="d-flex justify-content-center mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-success btn-lg">Tambah Produk Baru</a>
        </div>
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
