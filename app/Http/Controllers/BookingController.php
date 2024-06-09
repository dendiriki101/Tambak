<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('product')->where('seller_id', Auth::id())->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $products = Product::where('seller_id', Auth::id())
                            ->whereDoesntHave('bookings', function ($query) {
                                $query->where('status', 'aktif');
                            })->get();

        return view('bookings.create', compact('products'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location' => 'required|string|max:255',
            'auction_start' => 'required|date',
            'auction_end' => 'required|date'
        ]);

        Booking::create([
            'product_id' => $request->product_id,
            'seller_id' => Auth::id(),
            'buyer_id' => null, // Jika belum ada pembeli, set sebagai null
            'location' => $request->location,
            'auction_start' => $request->auction_start,
            'auction_end' => $request->auction_end
        ]);

        return redirect()->route('bookings.index')->with('success', 'Produk berhasil didaftarkan untuk lelang');
    }
    public function show($id)
    {
        $booking = Booking::with('product', 'product.seller')->findOrFail($id);
        return view('bookings.show', compact('booking'));
    }

    public function complete(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'selesai'; // Ubah status menjadi 'selesai'
        $booking->save();

        return redirect()->route('bookings.index')->with('success', 'Booking telah ditandai sebagai selesai.');
    }
    public function addUser(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $user = Auth::user();

        // Menambahkan user ke booking
        $booking->users()->syncWithoutDetaching([$user->id]);

        // Menambahkan flash message ke sesi
        session()->flash('success', 'Booking berhasil! Anda telah berhasil booking produk ini.');

        return redirect()->route('dashboard');
    }
    public function myBookings()
    {
        $user_id = Auth::id(); // Mengambil ID user yang sedang login
        $bookings = Booking::whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->with('product')->get(); // Memastikan untuk memuat produk terkait dengan booking

        return view('bookings.my-bookings', compact('bookings'));
    }
    public function sellerBookings()
    {
        $seller_id = Auth::id();
        $bookings = Booking::with(['product', 'users'])
                            ->whereHas('product', function ($query) use ($seller_id) {
                                $query->where('seller_id', $seller_id); // Asumsi kolom seller_id ada di tabel products
                            })->get();

        return view('bookings.seller-bookings', compact('bookings'));
    }



}

