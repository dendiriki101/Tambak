<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('status')->default('aktif'); // Menambahkan kolom status dengan nilai default 'aktif'
        });
    }

    public function down()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

