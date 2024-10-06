<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookinguser extends Model
{
    use HasFactory;

    protected $table = 'booking_user';
    protected $guarded = ['id'];
}
