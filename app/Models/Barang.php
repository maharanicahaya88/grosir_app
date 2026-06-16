<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table    = 'barangs';
    protected $fillable = ['kode_barang','nama_barang','satuan','harga_beli','harga_jual','stok','stok_minimum','keterangan','status'];
    protected $casts    = ['harga_beli' => 'decimal:2', 'harga_jual' => 'decimal:2'];

    public function detailPemesanans()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_barang');
    }

    public function stokMenipis(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public static function generateKode(): string
    {
        $last = self::orderByDesc('id')->first();
        $urut = $last ? ((int) substr($last->kode_barang, 4)) + 1 : 1;
        return 'BRG-' . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }
}