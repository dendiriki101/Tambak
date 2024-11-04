<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeBuyerIdNullableInBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change();
        });
    }
}

