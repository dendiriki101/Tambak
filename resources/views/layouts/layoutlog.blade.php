<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }

        .full-background {
            background-image: url('/img/ikan.jpg'); /* Path ke gambar */
            background-size: cover;                /* Membuat gambar menutupi seluruh background */
            background-position: center;           /* Posisi gambar di tengah */
            background-repeat: no-repeat;          /* Gambar tidak berulang */
            height: 100vh;                         /* Tinggi viewport penuh */
            width: 100vw;                          /* Lebar viewport penuh */
            position: fixed;                       /* Tetap pada posisi */
            top: 0;
            left: 0;
            z-index: -1;                           /* Agar gambar berada di bawah konten lain */
        }

        .content-area {
            position: relative;
            z-index: 1; /* Agar form berada di atas background */
            padding: 20px;
        }
    </style>

    <div class="full-background"></div> <!-- Background gambar penuh -->

    @include('layouts.navbar')

    <div class="content-area">
        @yield('content') <!-- Konten halaman akan dimasukkan di sini -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
