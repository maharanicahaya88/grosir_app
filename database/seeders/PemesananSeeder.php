<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Supplier ----
        $suppliers = [
            ['kode_supplier'=>'SUP-001','nama_supplier'=>'PT. Grosir Utama','alamat'=>'Jl. Industri No.1 Jakarta','telepon'=>'021-5678901','email'=>'info@grosirutama.com','status'=>'aktif'],
            ['kode_supplier'=>'SUP-002','nama_supplier'=>'CV. Sumber Rejeki','alamat'=>'Jl. Perdagangan No.5 Bandung','telepon'=>'022-1234567','email'=>'sumber@rejeki.co.id','status'=>'aktif'],
            ['kode_supplier'=>'SUP-003','nama_supplier'=>'UD. Makmur Abadi','alamat'=>'Jl. Pasar Baru No.12 Surabaya','telepon'=>'031-9876543','email'=>'','status'=>'nonaktif'],
        ];
        foreach ($suppliers as $s) Supplier::firstOrCreate(['kode_supplier'=>$s['kode_supplier']], $s);

        // ---- Konsumen ----
        $konsumens = [
            ['kode_konsumen'=>'KSM-001','nama_konsumen'=>'Toko Maju Jaya','alamat'=>'Jl. Raya No.12 Bandung','telepon'=>'0812-3456-7890','email'=>'majujaya@gmail.com','status'=>'aktif'],
            ['kode_konsumen'=>'KSM-002','nama_konsumen'=>'UD. Sejahtera','alamat'=>'Jl. Pasar Baru No.5 Bandung','telepon'=>'0813-5678-9012','email'=>'ud.sejahtera@gmail.com','status'=>'aktif'],
            ['kode_konsumen'=>'KSM-003','nama_konsumen'=>'CV. Berkah Abadi','alamat'=>'Jl. Sudirman No.8 Cimahi','telepon'=>'0821-1234-5678','email'=>'berkah@abadi.id','status'=>'aktif'],
            ['kode_konsumen'=>'KSM-004','nama_konsumen'=>'Toko Bintang','alamat'=>'Jl. Ahmad Yani No.3 Bandung','telepon'=>'0852-9999-8888','email'=>'','status'=>'aktif'],
        ];
        foreach ($konsumens as $k) Konsumen::firstOrCreate(['kode_konsumen'=>$k['kode_konsumen']], $k);

        // ---- Barang ----
        $barangs = [
            ['kode_barang'=>'BRG-001','nama_barang'=>'Beras Premium 5kg','satuan'=>'Sak','harga_beli'=>65000,'harga_jual'=>75000,'stok'=>250,'stok_minimum'=>20,'status'=>'aktif'],
            ['kode_barang'=>'BRG-002','nama_barang'=>'Gula Pasir 1kg','satuan'=>'Kg','harga_beli'=>12000,'harga_jual'=>14000,'stok'=>500,'stok_minimum'=>50,'status'=>'aktif'],
            ['kode_barang'=>'BRG-003','nama_barang'=>'Minyak Goreng 2L','satuan'=>'Dus','harga_beli'=>270000,'harga_jual'=>300000,'stok'=>12,'stok_minimum'=>15,'status'=>'aktif'],
            ['kode_barang'=>'BRG-004','nama_barang'=>'Tepung Terigu 1kg','satuan'=>'Karung','harga_beli'=>8500,'harga_jual'=>10000,'stok'=>300,'stok_minimum'=>30,'status'=>'aktif'],
            ['kode_barang'=>'BRG-005','nama_barang'=>'Mie Instan (Karton)','satuan'=>'Karton','harga_beli'=>95000,'harga_jual'=>110000,'stok'=>80,'stok_minimum'=>10,'status'=>'aktif'],
        ];
        foreach ($barangs as $b) Barang::firstOrCreate(['kode_barang'=>$b['kode_barang']], $b);

        // ---- Pemesanan ----
        $konsumen1 = Konsumen::where('kode_konsumen','KSM-001')->first();
        $konsumen2 = Konsumen::where('kode_konsumen','KSM-002')->first();
        $beras     = Barang::where('kode_barang','BRG-001')->first();
        $gula      = Barang::where('kode_barang','BRG-002')->first();
        $minyak    = Barang::where('kode_barang','BRG-003')->first();

        $po1 = Pemesanan::firstOrCreate(['no_pemesanan'=>'PO-202406-0001'],[
            'tanggal_pesan' => '2024-06-01',
            'tanggal_kirim' => '2024-06-05',
            'id_konsumen'   => $konsumen1->id,
            'status_pesan'  => 'disetujui',
            'keterangan'    => 'Pesanan rutin bulanan',
            'total_harga'   => 0,
        ]);
        if ($po1->details()->count() === 0) {
            $po1->details()->createMany([
                ['id_barang'=>$beras->id, 'jumlah_pesan'=>50,'harga_satuan'=>75000,'subtotal'=>3750000],
                ['id_barang'=>$gula->id,  'jumlah_pesan'=>100,'harga_satuan'=>14000,'subtotal'=>1400000],
            ]);
            $po1->hitungTotal();
        }

        $po2 = Pemesanan::firstOrCreate(['no_pemesanan'=>'PO-202406-0002'],[
            'tanggal_pesan' => '2024-06-07',
            'id_konsumen'   => $konsumen2->id,
            'status_pesan'  => 'menunggu',
            'keterangan'    => '',
            'total_harga'   => 0,
        ]);
        if ($po2->details()->count() === 0) {
            $po2->details()->createMany([
                ['id_barang'=>$minyak->id,'jumlah_pesan'=>30,'harga_satuan'=>300000,'subtotal'=>9000000],
                ['id_barang'=>$beras->id, 'jumlah_pesan'=>20,'harga_satuan'=>75000,'subtotal'=>1500000],
            ]);
            $po2->hitungTotal();
        }

        $this->command->info('Seeder PemesananSeeder berhasil dijalankan.');
    }
}
