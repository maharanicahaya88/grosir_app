<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pemesanan', 30)->unique();
            $table->date('tanggal_pesan');
            $table->date('tanggal_kirim')->nullable();
            $table->foreignId('id_konsumen')->constrained('konsumens')->restrictOnDelete();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->enum('status_pesan', ['menunggu', 'diproses', 'disetujui', 'dibatalkan'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('detail_pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemesanan')->constrained('pemesanans')->cascadeOnDelete();
            $table->foreignId('id_barang')->constrained('barangs')->restrictOnDelete();
            $table->integer('jumlah_pesan');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanans');
        Schema::dropIfExists('pemesanans');
    }
};
