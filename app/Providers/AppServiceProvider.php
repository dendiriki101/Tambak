<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Paginator;
use App\Models\User;
use Illuminate\Support\Facades\Gate;


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
}

}
