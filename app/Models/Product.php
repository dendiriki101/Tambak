<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'name', 'description', 'price', 'image', 'jenis_ikan', 'image2', 'image3', 'image4', 'image5'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBooking()
    {
        return $this->hasOne(Booking::class)
                    ->where('status', '!=', 'selesai')
                    ->where('jumlah', '>', 0);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }



}
