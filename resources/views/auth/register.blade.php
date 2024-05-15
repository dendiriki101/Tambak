@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="col-md-6 form-container">
    <h3 class="mb-3">Daftar Akun Baru</h3>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="role">Peran</label>
            <select name="role" class="form-control" required>
                <option value="pembeli">Pembeli</option>
                <option value="penjual">Penjual</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
    </form>
</div>
@endsection
