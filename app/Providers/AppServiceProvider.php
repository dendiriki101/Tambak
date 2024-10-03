<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Paginator;
use App\Models\User;
use App\Models\Booking; // Impor model Booking
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon; // Impor Carbon


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{

    Gate::define('penjual', function(User $user) {
        return $user->role === 'penjual';
    });

    Gate::define('pembeli', function(User $user) {
        return $user->role === 'pembeli';
    });

    // Pengecekan untuk membatalkan booking yang masih pending
    Booking::whereHas('users', function ($query) {
        $query->where('booking_user.status', 'Pending') // Tambahkan alias booking_user di sini
            ->where('booking_user.created_at', '<=', Carbon::now()->subDay()); // Tambahkan alias booking_user di sini
    })->update(['status' => 'Dibatalkan']);
}

}
