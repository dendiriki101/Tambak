<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            'image' => 'nullable|image|max:2048'
        ]);

        $product = new Product([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->file('image') ? $request->file('image')->store('products', 'public') : null
        ]);
        $product->save();

        return redirect()->route('dashboard')->with('success', 'Product has been added successfully');
    }

    public function edit(Product $product)
    {
        // Memastikan hanya penjual yang bisa mengedit produknya
        if ($product->seller_id != Auth::id()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($product->seller_id != Auth::id()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->file('image') ? $request->file('image')->store('products', 'public') : $product->image
        ]);

        return redirect()->route('dashboard')->with('success', 'Product updated successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }



    // Additional methods can be added here for listing, editing, etc.
}
