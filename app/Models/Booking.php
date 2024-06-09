<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = ['id']; // Melindungi 'id' dari mass assignment
    protected $dates = ['auction_start', 'auction_end'];


    // Relasi dengan model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi dengan model User untuk pembeli
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Jika Anda juga ingin mengakses data penjual melalui produk, Anda dapat menambahkan ini
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Di dalam model Booking
    public function users()
    {
        return $this->belongsToMany(User::class, 'booking_user')->withTimestamps();
    }



}

