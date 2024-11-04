<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Booking; // Tambahkan model Booking
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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



    public function viewCart()
    {
        // Ambil semua item di keranjang untuk pengguna yang sedang login
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        // Inisialisasi array untuk menyimpan stok dan status lelang
        $stocks = [];
        $auctionStatus = [];

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                // Ambil booking berdasarkan product_id untuk setiap item di cart
                $booking = Booking::where('product_id', $cartItem->product_id)->first();

                if ($booking) {
                    // Simpan stok berdasarkan jumlah di tabel booking
                    $stocks[$cartItem->id] = $booking->jumlah;

                    // Cek apakah tanggal sekarang berada di rentang auction_start dan auction_end
                    $today = Carbon::now()->toDateString();
                    $auctionStatus[$cartItem->id] = ($today >= $booking->auction_start && $today <= $booking->auction_end);
                }
            }
        }

        return view('cart.view', compact('cartItems', 'stocks', 'auctionStatus'));
    }


    // Fungsi untuk melihat keranjang
    // public function viewCart()
    // {
    //     // Ambil semua item di keranjang untuk pengguna yang sedang login
    //     $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

    //     // Inisialisasi variabel stock
    //   // Inisialisasi array untuk menyimpan stok
    //   $stocks = [];

    //   // Periksa apakah ada item di keranjang
    //   if ($cartItems->isNotEmpty()) {
    //       foreach ($cartItems as $cartItem) {
    //           // Ambil booking berdasarkan product_id untuk setiap item di cart
    //           $booking = Booking::where('product_id', $cartItem->product_id)->get();

    //           // Ambil semua nilai jumlah dan simpan dalam array dengan id cartItem sebagai key
    //           $stocks[$cartItem->id] = $booking->pluck('jumlah');
    //       }

    //       // Debug untuk melihat semua stok
    //     //   dd($stocks);
    //   }


    //     return view('cart.view', compact('cartItems', 'stocks'));
    // }


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
