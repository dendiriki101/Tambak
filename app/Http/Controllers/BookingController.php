<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
{
    $bookings = Booking::where('seller_id', Auth::id())->get();  // Asumsi model Booking memiliki 'seller_id'
    return view('bookings.index', compact('bookings'));
}

}
