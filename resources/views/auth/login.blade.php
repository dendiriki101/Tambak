@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="col-md-4 form-container">
    <h3 class="mb-3">Login ke Akun Anda</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" class="form-control" name="email" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>
@endsection
