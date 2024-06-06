<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Mendapatkan semua produk
        return view('dashboard.index', compact('products')); // Mengirim produk ke view
    }

}
