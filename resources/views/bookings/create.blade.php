@extends('layouts.app')

@section('title', 'Daftarkan Produk untuk Lelang')

@section('content')
<div class="container">
    <h1>Daftarkan Produk untuk Lelang</h1>
    <div class="mt-4">
        @if ($products->isNotEmpty())
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="product_id" class="form-label">Pilih Produk:</label>
                    <select class="form-select" id="product_id" name="product_id">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi Lelang:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="mb-3">
                    <label for="auction_start" class="form-label">Mulai Lelang:</label>
                    <input type="date" class="form-control" id="auction_start" name="auction_start" required>
                </div>
                <div class="mb-3">
                    <label for="auction_end" class="form-label">Akhir Lelang:</label>
                    <input type="date" class="form-control" id="auction_end" name="auction_end" required>
                </div>
                <button type="submit" class="btn btn-primary">Daftarkan</button>
            </form>
        @else
            <p>Tidak ada produk yang tersedia untuk didaftarkan.</p>
        @endif
    </div>
</div>
@endsection
