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
            $products = Product::with(['activeBooking' => function ($query) {
                // Pastikan booking yang aktif bukan selesai dan jumlah tidak nol
                $query->where('status', '!=', 'selesai')
                      ->where('jumlah', '>', 0);
            }])->whereHas('activeBooking') // Hanya ambil produk yang punya activeBooking
              ->when($jenisIkan, function ($query, $jenisIkan) {
                  return $query->where('jenis_ikan', $jenisIkan);
              })
              ->when($city, function ($query, $city) {
                  return $query->whereHas('bookings', function ($q) use ($city) {
                      $q->where('city', $city);
                  });
              })
              ->when($priceMin, function ($query, $priceMin) {
                  return $query->where('price', '>=', $priceMin);
              })
              ->when($priceMax, function ($query, $priceMax) {
                  return $query->where('price', '<=', $priceMax);
              })
              ->when($auctionStart, function ($query, $auctionStart) {
                  return $query->whereHas('bookings', function ($q) use ($auctionStart) {
                      $q->where('auction_start', '>=', $auctionStart);
                  });
              })
              ->when($auctionEnd, function ($query, $auctionEnd) {
                  return $query->whereHas('bookings', function ($q) use ($auctionEnd) {
                      $q->where('auction_end', '<=', $auctionEnd);
                  });
              })
              ->get();
    
            // Ambil daftar jenis ikan dan kota
            $jenisIkans = Product::select('jenis_ikan')->distinct()->get();
            $cities = Booking::select('city')->distinct()->get();
    
            // Kirimkan data produk, jenisIkans, dan cities ke view
            return view('dashboard.index', compact('products', 'jenisIkans', 'cities'));
        }
    }
    
    
    
}

