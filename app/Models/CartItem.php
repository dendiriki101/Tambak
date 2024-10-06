<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';

    protected $fillable = ['user_id', 'product_id', 'requested_quantity','bookinguser_id']; // Menambahkan kolom 'requested_quantity'

    // Relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Booking untuk mengambil jumlah
    public function booking()
    {
        return $this->hasOne(Booking::class, 'product_id', 'product_id')
                    ->where('status', 'aktif'); // Menyesuaikan dengan kondisi yang diperlukan
    }

    // Menambahkan akses untuk mendapatkan jumlah dari booking
    public function getStockQuantityAttribute()
    {
        return $this->booking ? $this->booking->jumlah : 0; // Mengambil jumlah dari booking
    }
}
