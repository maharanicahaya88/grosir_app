<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'no_pembayaran',
        'id_pemesanan',
        'tanggal_bayar',
        'jumlah_bayar',
        'total_tagihan',
        'sisa_tagihan',
        'metode_bayar',
        'status_bayar',
        'bukti_bayar',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar'  => 'decimal:2',
        'total_tagihan' => 'decimal:2',
        'sisa_tagihan'  => 'decimal:2',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }
}