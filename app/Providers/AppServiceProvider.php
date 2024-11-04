<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Paginator;
use App\Models\User;
use App\Models\Booking; // Impor model Booking
use App\Models\BookingUser; // Import model BookingUser
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
    //  Pengecekan untuk membatalkan booking yang masih pending selama lebih dari 1 hari di tabel booking_user
     BookingUser::where('status', 'Pending') // Filter untuk status 'Pending'
     ->where('created_at', '<=', Carbon::now()->subDay()) // Booking yang dibuat lebih dari 1 hari yang lalu
     ->update(['status' => 'Dibatalkan']); // Update status menjadi 'Dibatalkan'
}

}
