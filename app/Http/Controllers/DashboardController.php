<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->can('penjual')) {
            // Jika penjual, ambil semua produk mereka
            $products = Product::where('seller_id', Auth::id())->get();
            
            // Kirimkan data produk ke view tanpa jenisIkans dan cities
            return view('dashboard.index', compact('products'));
        } else if (Auth::user()->can('pembeli')) {
            // Ambil data filter
            $jenisIkan = $request->input('jenis_ikan');
            $city = $request->input('city');
            $priceMin = $request->input('price_min');
            $priceMax = $request->input('price_max');
            $auctionStart = $request->input('auction_start');
            $auctionEnd = $request->input('auction_end');
            
            // Ambil data produk sesuai filter
            $products = Product::with('activeBooking')->whereHas('bookings', function ($query) use ($jenisIkan, $city, $priceMin, $priceMax, $auctionStart, $auctionEnd) {
                if ($jenisIkan) {
                    $query->where('jenis_ikan', $jenisIkan);
                }
                if ($city) {
                    $query->where('city', $city);
                }
                if ($priceMin) {
                    $query->where('price', '>=', $priceMin);
                }
                if ($priceMax) {
                    $query->where('price', '<=', $priceMax);
                }
                if ($auctionStart) {
                    $query->where('auction_start', '>=', $auctionStart);
                }
                if ($auctionEnd) {
                    $query->where('auction_end', '<=', $auctionEnd);
                }
            })->get();
    
            // Ambil daftar jenis ikan dan kota
            $jenisIkans = Product::select('jenis_ikan')->distinct()->get();
            $cities = Booking::select('city')->distinct()->get();
            
            // Kirimkan data produk, jenisIkans, dan cities ke view
            return view('dashboard.index', compact('products', 'jenisIkans', 'cities'));
        }
    }
    
    
}

