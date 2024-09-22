@extends('layouts.app')

@section('title', 'Daftarkan Produk untuk Lelang')

@section('content')
<div class="container">
    <h1>Daftarkan Produk untuk Lelang</h1>
    <div class="mt-4">
        @if ($products->isNotEmpty())
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <!-- Pilih Produk -->
                <div class="mb-3">
                    <label for="product_id" class="form-label">Pilih Produk:</label>
                    <select class="form-select" id="product_id" name="product_id">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kota -->
                <div class="mb-3">
                    <label for="city" class="form-label">Pilih Kota:</label>
                    <select class="form-select" id="city" name="city" onchange="loadSubdistricts()">
                        <option value="">Pilih Kota</option>
                        @foreach ($cities as $city => $subdistricts)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kecamatan -->
                <div class="mb-3">
                    <label for="subdistrict" class="form-label">Pilih Kecamatan:</label>
                    <select class="form-select" id="subdistrict" name="subdistrict" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>

                <!-- Lokasi -->
                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi Lelang:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>

                <!-- Mulai Lelang -->
                <div class="mb-3">
                    <label for="auction_start" class="form-label">Mulai Lelang:</label>
                    <input type="date" class="form-control" id="auction_start" name="auction_start" required>
                </div>

                <!-- Akhir Lelang -->
                <div class="mb-3">
                    <label for="auction_end" class="form-label">Akhir Lelang:</label>
                    <input type="date" class="form-control" id="auction_end" name="auction_end" required>
                </div>

                <!-- Jumlah -->
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Produk:</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" required min="1">
                </div>

                <button type="submit" class="btn btn-primary">Daftarkan</button>
            </form>
        @else
            <p>Tidak ada produk yang tersedia untuk didaftarkan.</p>
        @endif
    </div>
</div>

<!-- Script untuk load kecamatan berdasarkan kota -->
<script>
    const subdistrictsData = @json($cities);

    function loadSubdistricts() {
        const citySelect = document.getElementById('city');
        const subdistrictSelect = document.getElementById('subdistrict');
        const selectedCity = citySelect.value;

        // Clear subdistrict options
        subdistrictSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

        if (selectedCity && subdistrictsData[selectedCity]) {
            subdistrictSelect.disabled = false;
            subdistrictsData[selectedCity].forEach(function(subdistrict) {
                const option = document.createElement('option');
                option.value = subdistrict;
                option.textContent = subdistrict;
                subdistrictSelect.appendChild(option);
            });
        } else {
            subdistrictSelect.disabled = true;
        }
    }
</script>
@endsection
