<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pembayaran', 30)->unique();
            $table->foreignId('id_pemesanan')->constrained('pemesanans')->restrictOnDelete();
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('total_tagihan', 15, 2);
            $table->decimal('sisa_tagihan', 15, 2)->default(0);
            $table->enum('metode_bayar', ['tunai', 'transfer', 'cek', 'giro'])->default('tunai');
            $table->enum('status_bayar', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->string('bukti_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};