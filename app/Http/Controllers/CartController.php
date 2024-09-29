<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Booking; // Tambahkan model Booking
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Fungsi untuk menambah produk ke keranjang
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
    
        if ($product) {
            // Cek jika produk sudah ada di keranjang
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
    
            // Jika produk sudah ada, tambahkan jumlahnya
            $cartItem->quantity = $request->input('quantity', 1); // Jika tidak ada input, set default ke 1
            $cartItem->save();
    
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }
    
        return redirect()->back()->with('error', 'Produk tidak ditemukan!');
    }
    

    // Fungsi untuk melihat keranjang
    public function viewCart()
    {
        // Ambil semua item di keranjang untuk pengguna yang sedang login
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
    
        // Inisialisasi variabel stock
        $stock = 0;
    
        // Periksa apakah ada item di keranjang
        if ($cartItems->isNotEmpty()) {
            // Ambil jumlah dari booking berdasarkan product_id item pertama
            $stock = Booking::where('product_id', $cartItems->first()->product_id)->value('jumlah');
        }
    
        return view('cart.view', compact('cartItems', 'stock'));
    }
    

    public function remove($id)
    {
        $cartItem = CartItem::find($id);

        // Pastikan item ada dan milik pengguna yang sedang login
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan.');
    }
}
