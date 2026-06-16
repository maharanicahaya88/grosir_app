<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konsumen extends Model
{
    use SoftDeletes;

    protected $table    = 'konsumens';
    protected $fillable = ['kode_konsumen','nama_konsumen','alamat','telepon','email','status'];

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_konsumen');
    }

    public static function aktif()
    {
        return self::where('status', 'aktif');
    }

    public static function generateKode(): string
    {
        $last = self::orderByDesc('id')->first();
        $urut = $last ? ((int) substr($last->kode_konsumen, 4)) + 1 : 1;
        return 'KSM-' . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }
}