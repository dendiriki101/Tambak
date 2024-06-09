<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Menentukan apakah pengguna adalah penjual atau pembeli
        if (Auth::user()->can('penjual')) {
            // Jika penjual, dapatkan semua produknya
            $products = Product::where('seller_id', Auth::id())->get();
        } else if (Auth::user()->can('pembeli')) {
            // Jika pembeli, hanya tampilkan produk yang memiliki status booking 'aktif'
            $products = Product::whereHas('bookings', function ($query) {
                $query->where('status', 'aktif');
            })->get();
        }

        return view('dashboard.index', compact('products'));
    }
}

