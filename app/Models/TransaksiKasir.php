<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKasir extends Model
{
    protected $fillable = [
        'hasil_penjualan',
        'saldo_sebelum',
        'saldo_masuk_rek',
        'setor_tunai',
        'kas_masuk',
        'saldo_rek',
        'pengeluaran_items'
    ];

    protected $casts = [
        'pengeluaran_items' => 'array' // Agar otomatis jadi array/JSON
    ];
}
