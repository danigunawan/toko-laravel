<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    protected $fillable = [
        'id_transaksi', 'toko_id', 'jenis_transaksi', 'jumlah_masuk', 'jumlah_keluar',
    ];
}
