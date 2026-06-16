<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemesanan extends Model
{
    use SoftDeletes;

    protected $table = 'pemesanans';
    protected $fillable = [
        'no_pemesanan',
        'id_konsumen',
        'tanggal_pesan',
        'tanggal_kirim',
        'status_pesan',
        'total_harga',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date',
        'tanggal_kirim' => 'date',
        'total_harga'   => 'decimal:2',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    public function detailPemesanans()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_pemesanan');
    }
    public function pembayarans()
{
    return $this->hasMany(Pembayaran::class, 'id_pemesanan');
}

public function isPaid(): bool
{
    return $this->pembayarans()->where('status_bayar', 'lunas')->exists();
}

public function totalDibayar(): float
{
    return (float) $this->pembayarans()->sum('jumlah_bayar');
}

public function hitungTotal(): void
{
    $total = $this->detailPemesanans()->sum('subtotal');
    $this->update(['total_harga' => $total]);
}

public function sisaTagihan(): float
{
    return max(0, (float) $this->total_harga - $this->totalDibayar());
}

    public static function menunggu() { return self::where('status_pesan', 'menunggu'); }
    public static function disetujui() { return self::where('status_pesan', 'disetujui'); }

    public static function generateKode(): string

    
    {
        $last = self::orderByDesc('id')->first();
        $urut = $last ? $last->id + 1 : 1;
        return 'PMS-' . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }

    public function scopeFilter($query, array $filters)
{
    $query->when($filters['status'] ?? null, function ($q, $status) {
        $q->where('status_pesan', $status);
    });

    $query->when($filters['konsumen'] ?? null, function ($q, $konsumen) {
        $q->whereHas('konsumen', function ($q) use ($konsumen) {
            $q->where('nama_konsumen', 'like', '%' . $konsumen . '%');
        });
    });

    $query->when($filters['dari'] ?? null, function ($q, $dari) {
        $q->whereDate('tanggal_pesan', '>=', $dari);
    });

    $query->when($filters['sampai'] ?? null, function ($q, $sampai) {
        $q->whereDate('tanggal_pesan', '<=', $sampai);
    });

    $query->when($filters['search'] ?? null, function ($q, $search) {
        $q->where('no_pemesanan', 'like', '%' . $search . '%');
    });
}
}