@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-black">Dashboard</div>
                <div class="card-body bg-light">
                    <h4>Selamat datang, {{ Auth::user()->name }}!</h4>
                    <p>Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.</p>
                    <p>Ini adalah area dashboard Anda, di mana Anda dapat melihat aktivitas terkini dan statistik penting.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
