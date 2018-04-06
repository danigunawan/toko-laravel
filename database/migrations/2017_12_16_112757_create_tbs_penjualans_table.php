<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbsPenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbs_penjualans', function (Blueprint $table) {
            $table->increments('id_tbs_penjualan');
            $table->integer('id_simpan_penjualan')->nullable();
            $table->integer('toko_id');
            $table->string('session_id')->nullable();
            $table->string('satuan_id')->nullable();
            $table->integer('produk_id');
            $table->string('jumlah_produk');
            $table->string('harga_produk');
            $table->string('subtotal');
            $table->string('keterangan')->nullable();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbs_penjualans');
    }
}
