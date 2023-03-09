<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nomor_induk_pegawai');
            $table->foreignId('id_pembeli')->references('id')->on('users');
            $table->foreignId('id_produk')->references('id')->on('produks');
            $table->date('tanggal_pembelian');
            $table->integer('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelians');
    }
};
