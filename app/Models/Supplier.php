<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table    = 'suppliers';
    protected $fillable = ['kode_supplier','nama_supplier','alamat','telepon','email','status'];

    public static function generateKode(): string
    {
        $last = self::orderByDesc('id')->first();
        $urut = $last ? ((int) substr($last->kode_supplier, 4)) + 1 : 1;
        return 'SUP-' . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }
}