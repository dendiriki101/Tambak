<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingUserTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('pendaftaran')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Tambahkan kolom tambahan jika diperlukan, misal status booking untuk setiap user
            $table->string('status')->default('pending');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}

