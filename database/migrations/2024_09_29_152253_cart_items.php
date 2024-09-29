<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID customer
            $table->unsignedBigInteger('product_id'); // Produk yang masuk ke keranjang
            $table->integer('quantity'); // Jumlah produk yang diinginkan
            $table->timestamps(); // Timestamp untuk created_at dan updated_at

            // Relasi ke tabel users dan products
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
