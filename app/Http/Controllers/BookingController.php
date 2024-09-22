<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Booking;
use App\Models\History;
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
        // Mendapatkan daftar produk yang dijual oleh user yang sedang login dan belum didaftarkan untuk lelang aktif
        $products = Product::where('seller_id', Auth::id())
                            ->whereDoesntHave('bookings', function ($query) {
                                $query->where('status', 'aktif');
                            })->get();

        // Daftar kota dan kecamatan di Jawa Timur (array statis)
        $cities = [
            'Surabaya' => [
                'Sukolilo',
                'Mulyorejo',
                'Rungkut',
                'Wonokromo',
                'Tambaksari',
                'Tegalsari'
            ],
            'Malang' => [
                'Blimbing',
                'Kedungkandang',
                'Lowokwaru',
                'Sukun',
                'Klojen'
            ],
            'Sidoarjo' => [
                'Waru',
                'Taman',
                'Krian',
                'Buduran',
                'Candi',
                'Sedati'
            ],
            'Gresik' => [
                'Kebomas',
                'Driyorejo',
                'Bungah',
                'Manyar',
                'Menganti',
                'Benjeng'
            ],
            'Mojokerto' => [
                'Prajurit Kulon',
                'Magersari',
                'Puri',
                'Jatirejo',
                'Jetis'
            ],
            'Pasuruan' => [
                'Bangil',
                'Beji',
                'Gempol',
                'Pandaan',
                'Purwosari'
            ],
            'Probolinggo' => [
                'Kraksaan',
                'Paiton',
                'Dringu',
                'Krejengan',
                'Tegalsiwalan'
            ],
            'Banyuwangi' => [
                'Banyuwangi',
                'Glagah',
                'Giri',
                'Kabat',
                'Rogojampi'
            ],
            'Jember' => [
                'Patrang',
                'Sumbersari',
                'Kaliwates',
                'Ambulu',
                'Balung'
            ],
            'Madiun' => [
                'Kartoharjo',
                'Manguharjo',
                'Taman',
                'Wungu',
                'Sawahan'
            ],
            'Kediri' => [
                'Pesantren',
                'Mojoroto',
                'Kota',
                'Pare',
                'Kandangan'
            ],
            'Blitar' => [
                'Sananwetan',
                'Sukorejo',
                'Kepanjenkidul',
                'Kanigoro',
                'Garum'
            ]
        ];

        // Mengembalikan view dengan daftar produk dan kota beserta kecamatannya
        return view('bookings.create', compact('products', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'city' => 'required|string', 
            'subdistrict' => 'required|string',
            'location' => 'required|string|max:255',
            'auction_start' => 'required|date',
            'auction_end' => 'required|date',
            'jumlah' => 'required|integer|min:1' // Validasi untuk jumlah
        ]);
    
        Booking::create([
            'product_id' => $request->product_id,
            'seller_id' => Auth::id(),
            'buyer_id' => null,
            'location' => $request->location,
            'city' => $request->city,
            'subdistrict' => $request->subdistrict,
            'auction_start' => $request->auction_start,
            'auction_end' => $request->auction_end,
            'jumlah' => $request->jumlah // Menyimpan jumlah
        ]);
    
        return redirect()->route('bookings.index')->with('success', 'Produk berhasil didaftarkan untuk lelang');
    }
    
    public function myBookings(Request $request)
    {
        $product_id = $request->get('product_id'); // Mendapatkan ID produk dari query parameter
        $product = Product::find($product_id); // Mendapatkan informasi produk
    
        return view('bookings.my-bookings', compact('product'));
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

    public function sellerBookings()
    {
        $seller_id = Auth::id();
        $bookings = Booking::with(['product', 'users'])
                            ->whereHas('product', function ($query) use ($seller_id) {
                                $query->where('seller_id', $seller_id); // Asumsi kolom seller_id ada di tabel products
                            })->get();

        return view('bookings.seller-bookings', compact('bookings'));
    }

    public function confirmBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $history = History::where('product_id', $booking->product_id)->first();

        if ($history) {
            // Kurangi jumlah di tabel history
            $booking->jumlah -= $history->jumlah; // Asumsi 'jumlah' adalah kolom di tabel history
            $booking->status = 'Dikonfirmasi';
            $booking->save();

            // Jika diperlukan, bisa juga update status booking
          
            $history->status = 'Dikonfirmasi'; // Atau status lain yang sesuai
            $history->save();
            
            return redirect()->route('seller-bookings')->with('success', 'Booking berhasil dikonfirmasi.');
        }

        return redirect()->route('seller-bookings')->with('error', 'History tidak ditemukan.');
    }


    public function update(Request $request, $bookingId)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $booking = Booking::find($bookingId);
        $product = Product::find($booking->product_id);

        // Menghitung total harga baru
        $total_harga = $request->jumlah * $product->price;

        // Update booking
        $booking->jumlah = $request->jumlah;
        $booking->total_harga = $total_harga;
        $booking->save();

        return redirect()->route('my-bookings')->with('success', 'Booking berhasil diupdate!');
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
        ]);
    
        $product = Product::findOrFail($request->product_id);
        $jumlah = $request->jumlah;
        $total_harga = $product->price * $jumlah;
    
        // Simpan ke tabel history
        History::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'jumlah' => $jumlah,
            'total_harga' => $total_harga,
            'status' => 'pending',
        ]);
    
        return redirect()->route('history')->with('success', 'Pesanan berhasil dibuat!');
    }
    


    public function showHistory()
    {
        $histories = History::where('user_id', auth()->id())->with('product')->get();

        return view('bookings.history', compact('histories'));
    }




}

