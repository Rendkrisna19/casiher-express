<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokLog extends Model
{
    protected $fillable = ['barang_id', 'jenis', 'jumlah', 'keterangan', 'bukti_transaksi'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
