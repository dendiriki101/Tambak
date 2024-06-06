@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="display-4 text-center mb-4">Selamat Datang di Tambak Online</h1>
    @can('penjual')
    <div class="d-flex justify-content-center mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-success btn-lg">Tambah Produk Baru</a>
    </div>
    @endcan

    @can('pembeli')
    <div class="alert alert-success" role="alert">
        Anda login sebagai Pembeli. Temukan produk terbaru di bawah ini!
    </div>
    @endcan

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($products as $product)
        <div class="col">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="Product Image" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 100, '...') }}</p>
                    <p class="card-text"><small class="text-muted">Rp{{ number_format($product->price, 2) }}</small></p>
                </div>
                <div class="card-footer d-grid">
                    @can('penjual')
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-lg btn-primary">Edit</a>
                    @endcan
                    @can('pembeli')
                    <a href="#" class="btn btn-warning">Booking</a>
                    @endcan
                    <hr>
                    <small class="text-muted float-right">Last updated {{ $product->updated_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
