<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleImagesToProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image2')->nullable()->after('image'); // Menambahkan gambar kedua
            $table->string('image3')->nullable()->after('image2'); // Menambahkan gambar ketiga
            $table->string('image4')->nullable()->after('image3'); // Menambahkan gambar keempat
            $table->string('image5')->nullable()->after('image4'); // Menambahkan gambar kelima
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image2', 'image3', 'image4', 'image5']); // Menghapus kolom jika rollback
        });
    }
}
