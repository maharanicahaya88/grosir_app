<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    protected $table = 'detail_pemesanans';

    protected $fillable = [
        'id_pemesanan',
        'id_barang',
        'jumlah_pesan',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}