<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function create()
    {
        if (!Gate::allows('penjual')) {
            return redirect('/dashboard')->with('error', 'Hanya penjual yang dapat mengakses halaman ini.');
        }

        return view('products.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'jenis_ikan' => 'required|string', // Tambahkan validasi jenis ikan
            'image' => 'nullable|image|max:2048'
        ]);

        $product = new Product([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'jenis_ikan' => $request->jenis_ikan, // Simpan jenis ikan
            'image' => $request->file('image') ? $request->file('image')->store('products', 'public') : null
        ]);
        $product->save();

        return redirect()->route('dashboard')->with('success', 'Product has been added successfully');
    }
    public function update(Request $request, Product $product)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'jenis_ikan' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'image2' => 'nullable|image|max:2048',
            'image3' => 'nullable|image|max:2048',
            'image4' => 'nullable|image|max:2048',
            'image5' => 'nullable|image|max:2048'
        ]);

        // Cek apakah pengguna memiliki hak akses
        if ($product->seller_id != Auth::id()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        // Persiapkan data untuk update
        $dataToUpdate = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'jenis_ikan' => $request->jenis_ikan,
            'image' => $request->file('image') ? $request->file('image')->store('products', 'public') : $product->image,
            'image2' => $request->file('image2') ? $request->file('image2')->store('products', 'public') : $product->image2,
            'image3' => $request->file('image3') ? $request->file('image3')->store('products', 'public') : $product->image3,
            'image4' => $request->file('image4') ? $request->file('image4')->store('products', 'public') : $product->image4,
            'image5' => $request->file('image5') ? $request->file('image5')->store('products', 'public') : $product->image5
        ];

        // Log informasi sebelum update
        Log::info('Data yang akan diupdate:', $dataToUpdate);

        // Update produk dengan data baru
        $updated = $product->update($dataToUpdate);

        // Log hasil update
        if ($updated) {
            Log::info('Produk berhasil diperbarui.', [$product->id]);
        } else {
            Log::error('Gagal memperbarui produk.', [$product->id]);
        }

        return redirect()->route('dashboard')->with('success', 'Produk berhasil diperbarui.');
    }




    public function edit(Product $product)
    {
        // Memastikan hanya penjual yang bisa mengedit produknya
        if ($product->seller_id != Auth::id()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        return view('products.edit', compact('product'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }



    // Additional methods can be added here for listing, editing, etc.
}
