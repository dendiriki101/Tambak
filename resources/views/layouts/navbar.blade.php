<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Tambak Ikan Online</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto">
                @if (Auth::check())
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>

                    @if (Auth::user()->role == 'penjual')
                        <a class="nav-link" href="{{ route('bookings.index') }}">Daftarkan Produk</a>
                        <a class="nav-link" href="{{ route('seller-bookings') }}">Daftar Pembeli</a>
                    @endif

                    @if (Auth::user()->role == 'pembeli')
                        <a class="nav-link" href="{{ route('my-bookings') }}">My Bookings</a>
                    @endif

                    <!-- Dropdown untuk Edit Profil dan Logout -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Menampilkan gambar profil atau default -->
                            <img src="{{ Auth::user()->profile_picture_url ?? asset('img/default-profile.png') }}" alt="Profile" class="rounded-circle" width="30" height="30">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profil</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        </ul>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endif
            </div>
        </div>
    </div>
</nav>
