<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookinguserIdToCartItemsAndHistory extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambahkan kolom bookinguser_id ke tabel cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('bookinguser_id')->nullable()->after('id');
            $table->foreign('bookinguser_id')->references('id')->on('booking_user')->onDelete('cascade');
        });

        // Menambahkan kolom bookinguser_id ke tabel history
        Schema::table('history', function (Blueprint $table) {
            $table->unsignedBigInteger('bookinguser_id')->nullable()->after('id');
            $table->foreign('bookinguser_id')->references('id')->on('booking_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom bookinguser_id dari tabel cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['bookinguser_id']);
            $table->dropColumn('bookinguser_id');
        });

        // Menghapus kolom bookinguser_id dari tabel history
        Schema::table('history', function (Blueprint $table) {
            $table->dropForeign(['bookinguser_id']);
            $table->dropColumn('bookinguser_id');
        });
    }
}
