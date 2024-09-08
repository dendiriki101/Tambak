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
            'city' => 'required|string', // Validasi untuk kota
            'subdistrict' => 'required|string', // Validasi untuk kecamatan
            'location' => 'required|string|max:255',
            'auction_start' => 'required|date',
            'auction_end' => 'required|date'
        ]);

        Booking::create([
            'product_id' => $request->product_id,
            'seller_id' => Auth::id(),
            'buyer_id' => null, // Jika belum ada pembeli, set sebagai null
            'location' => $request->location,
            'city' => $request->city, // Menyimpan kota yang dipilih
            'subdistrict' => $request->subdistrict, // Menyimpan kecamatan yang dipilih
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

