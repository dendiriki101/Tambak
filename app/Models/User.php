<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'no_ktp', 'no_hp', 'role', 'profile_picture_url',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Di dalam model User
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'bookings')
                    ->withPivot('status', 'id') // Sertakan 'id' di sini
                    ->withTimestamps();
    }



    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }



}
