<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Booking;
use App\Models\History;
use App\Models\CartItem;
use App\Models\Bookinguser;
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
                            ->whereDoesntHave('pendaftaran', function ($query) {
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
    // public function addUser(Request $request, $bookingId)
    // {
    //     $booking = Booking::findOrFail($bookingId);
    //     $user = Auth::user();

    //     // Menambahkan user ke booking
    //     $booking->users()->syncWithoutDetaching([$user->id]);

    //     // Menambahkan flash message ke sesi
    //     session()->flash('success', 'Booking berhasil! Anda telah berhasil booking produk ini.');

    //     return redirect()->route('dashboard');
    // }

    public function sellerBookings()
    {
        $seller_id = Auth::id(); // ID penjual yang sedang login

        // Mengambil booking di mana seller_id cocok dengan ID penjual yang sedang login
        // Juga mengambil daftar users (pembeli) yang terkait melalui tabel pivot booking_user
        $bookingUsers = \DB::table('bookings')
            ->join('pendaftaran', 'bookings.booking_id', '=', 'pendaftaran.id')
            ->join('history', 'bookings.id', '=', 'history.bookinguser_id')
            ->where('pendaftaran.seller_id', $seller_id)
            ->select('bookings.*', 'pendaftaran.product_id', 'pendaftaran.status as booking_status','history.jumlah as booking_jumlah', 'pendaftaran.created_at as booking_created_at')
            ->get();

        return view('bookings.seller-bookings', compact('bookingUsers'));
    }



    public function confirmBooking($bookingId)
    {
        // Ambil booking_user berdasarkan id yang diterima
        $bookingUser = Bookinguser::findOrFail($bookingId);

        // Ambil booking berdasarkan booking_id di booking_user
        $booking = Booking::findOrFail($bookingUser->booking_id);

        // Update status di tabel booking_user
        // Update status untuk user yang terkait dengan booking ini
        $bookingUser->status = 'Pesanan Diterima'; // Ubah status sesuai kebutuhan
        $bookingUser->save(); // Simpan perubahan

        // Ambil history berdasarkan product_id dari booking
        $history = History::where('bookinguser_id', $bookingUser->id)->first();

        if ($history) {
            // Kurangi jumlah di tabel booking
            $booking->jumlah -= $history->jumlah; // Asumsi 'jumlah' adalah kolom di tabel booking
            $booking->save();

            // Update status di tabel history
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
        // Validasi input dari form
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
        ]);

        // Ambil produk berdasarkan ID yang dikirimkan dalam request
        $product = Product::findOrFail($request->product_id);
        $jumlah = $request->jumlah;
        $total_harga = $product->price * $jumlah;

        // Pencarian booking yang aktif berdasarkan produk
        $booking = Booking::where('product_id', $product->id)->first();

        if ($booking) {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Masukkan data ke dalam tabel booking_user secara manual menggunakan model Bookinguser
            $bookingUser = Bookinguser::create([
                'booking_id' => $booking->id,
                'user_id' => $user->id,
            ]);

            // Ambil bookinguser_id dari data yang baru saja dibuat
            $bookinguser_id = $bookingUser->id;

            // Simpan informasi order ke tabel history dengan bookinguser_id
            $history = History::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'jumlah' => $jumlah,
                'total_harga' => $total_harga,
                'status' => 'pending',
                'bookinguser_id' => $bookinguser_id, // Isi kolom bookinguser_id di sini
            ]);

            // Update bookinguser_id di cart_items
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->update([
                    'bookinguser_id' => $bookinguser_id,
                ]);

            // Menambahkan flash message ke sesi untuk menandai bahwa booking berhasil
            session()->flash('success', 'Booking berhasil! Anda telah berhasil memesan produk ini.');
        } else {
            // Jika tidak ada booking yang cocok, tambahkan flash message error
            session()->flash('error', 'Booking tidak tersedia untuk produk ini.');
        }

        // Hapus item dari keranjang setelah konfirmasi pesanan
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        // Redirect ke dashboard atau halaman lain setelah proses selesai
        return redirect()->route('dashboard');
    }





    // if ($booking) {
    //     // Ambil user yang sedang login
    //     $user = Auth::user();

    //     // Menambahkan user ke dalam booking
    //     $booking->users()->syncWithoutDetaching([$user->id]);

    //     // Menambahkan flash message ke sesi untuk menandai bahwa booking berhasil
    //     session()->flash('success', 'Booking berhasil! Anda telah berhasil memesan produk ini.');


    public function showHistory()
    {
        $histories = History::where('user_id', auth()->id())->with('product')->get();

        return view('bookings.history', compact('histories'));
    }


    public function addToCart(Request $request)
    {
        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => \DB::raw("quantity + $request->quantity")
            ]
        );

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Fungsi untuk melihat produk di keranjang
    public function viewCart()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
                             ->with('product') // Mengambil detail produk
                             ->get();

        return view('cart.view', compact('cartItems'));
    }

    // Fungsi untuk menghapus produk dari keranjang
    public function removeFromCart($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function cancelBooking($bookingUserId)
    {
        // Temukan data booking_user berdasarkan booking user ID
        $bookingUser = BookingUser::findOrFail($bookingUserId);
        // dd($bookingUser->id);

        $history = History::where('bookinguser_id', $bookingUser->id)->first();

        // Jika Anda ingin mendapatkan booking berdasarkan relasi
        $booking = $bookingUser->id; // Mengambil booking yang terkait

        if ($booking) {
            // Ubah status menjadi 'Dibatalkan' di pivot
            $bookingUser->status = 'Dibatalkan'; // Ubah status di booking_user
            $bookingUser->save(); // Simpan perubahan status

            $history->status = 'Dibatalkan'; // Atau status lain yang sesuai
            $history->save();

            // Jika Anda ingin juga mengupdate status di tabel booking, lakukan hal ini:
            // $booking->status = 'Dibatalkan'; // Ubah status di booking
            // $booking->save();
        }

        return redirect()->route('seller-bookings')->with('success', 'Booking berhasil dibatalkan.');
    }








}

