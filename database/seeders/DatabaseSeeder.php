<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // =====================
        // 1. USER ADMIN
        // =====================
        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'adminpemesanan@gmail.com',
            'password' => bcrypt('pemesanan'),
        ]);

        // =====================
        // 2. KONSUMEN
        // =====================
        $konsumens = [
            ['kode_konsumen' => 'KSM-001', 'nama_konsumen' => 'Toko Maju Jaya',      'alamat' => 'Jl. Pasar Baru No. 12, Bandung',    'telepon' => '081234567890', 'email' => 'majujaya@email.com',     'status' => 'aktif'],
            ['kode_konsumen' => 'KSM-002', 'nama_konsumen' => 'UD. Sumber Rezeki',    'alamat' => 'Jl. Raya Cimahi No. 45, Cimahi',    'telepon' => '082345678901', 'email' => 'sumberrezeki@email.com', 'status' => 'aktif'],
            ['kode_konsumen' => 'KSM-003', 'nama_konsumen' => 'CV. Berkah Abadi',     'alamat' => 'Jl. Sudirman No. 88, Jakarta',       'telepon' => '083456789012', 'email' => 'berkah@email.com',       'status' => 'aktif'],
            ['kode_konsumen' => 'KSM-004', 'nama_konsumen' => 'Toko Sejahtera',       'alamat' => 'Jl. Diponegoro No. 21, Surabaya',    'telepon' => '084567890123', 'email' => 'sejahtera@email.com',    'status' => 'aktif'],
            ['kode_konsumen' => 'KSM-005', 'nama_konsumen' => 'PT. Cahaya Nusantara', 'alamat' => 'Jl. Ahmad Yani No. 100, Semarang',   'telepon' => '085678901234', 'email' => 'cahaya@email.com',       'status' => 'aktif'],
            ['kode_konsumen' => 'KSM-006', 'nama_konsumen' => 'Toko Harapan Baru',    'alamat' => 'Jl. Gatot Subroto No. 55, Medan',    'telepon' => '086789012345', 'email' => 'harapan@email.com',      'status' => 'aktif'],
            ['kode_konsumen' => 'KSM-007', 'nama_konsumen' => 'UD. Makmur Sentosa',   'alamat' => 'Jl. Veteran No. 33, Yogyakarta',      'telepon' => '087890123456', 'email' => 'makmur@email.com',       'status' => 'nonaktif'],
        ];

        foreach ($konsumens as $k) {
            DB::table('konsumens')->insert(array_merge($k, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // =====================
        // 3. SUPPLIER
        // =====================
        $suppliers = [
            ['kode_supplier' => 'SUP-001', 'nama_supplier' => 'PT. Indofood Sukses',   'alamat' => 'Jl. Industri No. 1, Jakarta',        'telepon' => '021-1234567', 'email' => 'indofood@supplier.com',   'status' => 'aktif'],
            ['kode_supplier' => 'SUP-002', 'nama_supplier' => 'CV. Sari Roti Utama',   'alamat' => 'Jl. Pabrik No. 22, Tangerang',       'telepon' => '021-2345678', 'email' => 'sariroti@supplier.com',   'status' => 'aktif'],
            ['kode_supplier' => 'SUP-003', 'nama_supplier' => 'PT. Wings Food',         'alamat' => 'Jl. Raya Industri No. 5, Surabaya',  'telepon' => '031-3456789', 'email' => 'wings@supplier.com',      'status' => 'aktif'],
            ['kode_supplier' => 'SUP-004', 'nama_supplier' => 'UD. Bahan Pokok Utama', 'alamat' => 'Jl. Kencana No. 77, Bandung',         'telepon' => '022-4567890', 'email' => 'bahanpokok@supplier.com', 'status' => 'aktif'],
            ['kode_supplier' => 'SUP-005', 'nama_supplier' => 'PT. Mayora Indah',       'alamat' => 'Jl. Tomang Raya No. 21, Jakarta',    'telepon' => '021-5678901', 'email' => 'mayora@supplier.com',     'status' => 'nonaktif'],
        ];

        foreach ($suppliers as $s) {
            DB::table('suppliers')->insert(array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // =====================
        // 4. BARANG
        // =====================
        $barangs = [
            ['kode_barang' => 'BRG-001', 'nama_barang' => 'Indomie Goreng',    'satuan' => 'dus',   'harga_jual' => 120000, 'stok' => 100],
            ['kode_barang' => 'BRG-002', 'nama_barang' => 'Beras Premium 5kg', 'satuan' => 'karung','harga_jual' => 75000,  'stok' => 200],
            ['kode_barang' => 'BRG-003', 'nama_barang' => 'Minyak Goreng 2L',  'satuan' => 'karton','harga_jual' => 145000, 'stok' => 80],
            ['kode_barang' => 'BRG-004', 'nama_barang' => 'Gula Pasir 1kg',    'satuan' => 'karung','harga_jual' => 15000,  'stok' => 300],
            ['kode_barang' => 'BRG-005', 'nama_barang' => 'Teh Botol Sosro',   'satuan' => 'karton','harga_jual' => 48000,  'stok' => 150],
            ['kode_barang' => 'BRG-006', 'nama_barang' => 'Sabun Lifebuoy',    'satuan' => 'lusin', 'harga_jual' => 36000,  'stok' => 120],
        ];

        foreach ($barangs as $b) {
            DB::table('barangs')->insert(array_merge($b, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // =====================
        // 5. PEMESANAN
        // =====================
        $pemesanans = [
            ['id' => 1, 'no_pemesanan' => 'PMS-001', 'id_konsumen' => 1, 'tanggal_pesan' => Carbon::now()->subDays(20), 'tanggal_kirim' => Carbon::now()->subDays(15), 'status_pesan' => 'disetujui',  'total_harga' => 1200000, 'keterangan' => 'Pesanan rutin bulanan'],
            ['id' => 2, 'no_pemesanan' => 'PMS-002', 'id_konsumen' => 2, 'tanggal_pesan' => Carbon::now()->subDays(18), 'tanggal_kirim' => Carbon::now()->subDays(12), 'status_pesan' => 'disetujui',  'total_harga' => 875000,  'keterangan' => null],
            ['id' => 3, 'no_pemesanan' => 'PMS-003', 'id_konsumen' => 3, 'tanggal_pesan' => Carbon::now()->subDays(15), 'tanggal_kirim' => Carbon::now()->subDays(10), 'status_pesan' => 'disetujui',  'total_harga' => 2340000, 'keterangan' => 'Pesanan besar akhir bulan'],
            ['id' => 4, 'no_pemesanan' => 'PMS-004', 'id_konsumen' => 4, 'tanggal_pesan' => Carbon::now()->subDays(10), 'tanggal_kirim' => Carbon::now()->subDays(5),  'status_pesan' => 'disetujui',  'total_harga' => 560000,  'keterangan' => null],
            ['id' => 5, 'no_pemesanan' => 'PMS-005', 'id_konsumen' => 5, 'tanggal_pesan' => Carbon::now()->subDays(3),  'tanggal_kirim' => Carbon::now()->addDays(2),  'status_pesan' => 'menunggu',   'total_harga' => 450000,  'keterangan' => null],
            ['id' => 6, 'no_pemesanan' => 'PMS-006', 'id_konsumen' => 1, 'tanggal_pesan' => Carbon::now()->subDays(2),  'tanggal_kirim' => Carbon::now()->addDays(3),  'status_pesan' => 'menunggu',   'total_harga' => 720000,  'keterangan' => 'Urgent'],
            ['id' => 7, 'no_pemesanan' => 'PMS-007', 'id_konsumen' => 6, 'tanggal_pesan' => Carbon::now()->subDays(25), 'tanggal_kirim' => null,                        'status_pesan' => 'dibatalkan', 'total_harga' => 300000,  'keterangan' => 'Dibatalkan oleh konsumen'],
        ];

        foreach ($pemesanans as $p) {
            DB::table('pemesanans')->insert(array_merge($p, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // =====================
        // 6. DETAIL PEMESANAN
        // =====================
        $details = [
            ['id_pemesanan' => 1, 'id_barang' => 1, 'jumlah' => 5,  'harga_satuan' => 120000, 'subtotal' => 600000],
            ['id_pemesanan' => 1, 'id_barang' => 3, 'jumlah' => 4,  'harga_satuan' => 145000, 'subtotal' => 580000],
            ['id_pemesanan' => 1, 'id_barang' => 4, 'jumlah' => 2,  'harga_satuan' => 15000,  'subtotal' =>  20000],
            ['id_pemesanan' => 2, 'id_barang' => 2, 'jumlah' => 5,  'harga_satuan' => 75000,  'subtotal' => 375000],
            ['id_pemesanan' => 2, 'id_barang' => 5, 'jumlah' => 5,  'harga_satuan' => 48000,  'subtotal' => 240000],
            ['id_pemesanan' => 2, 'id_barang' => 6, 'jumlah' => 5,  'harga_satuan' => 36000,  'subtotal' => 180000],
            ['id_pemesanan' => 3, 'id_barang' => 1, 'jumlah' => 10, 'harga_satuan' => 120000, 'subtotal' => 1200000],
            ['id_pemesanan' => 3, 'id_barang' => 2, 'jumlah' => 10, 'harga_satuan' => 75000,  'subtotal' => 750000],
            ['id_pemesanan' => 3, 'id_barang' => 3, 'jumlah' => 2,  'harga_satuan' => 145000, 'subtotal' => 290000],
            ['id_pemesanan' => 3, 'id_barang' => 4, 'jumlah' => 5,  'harga_satuan' => 15000,  'subtotal' =>  75000],
            ['id_pemesanan' => 3, 'id_barang' => 6, 'jumlah' => 1,  'harga_satuan' => 36000,  'subtotal' =>  25000],
            ['id_pemesanan' => 4, 'id_barang' => 5, 'jumlah' => 5,  'harga_satuan' => 48000,  'subtotal' => 240000],
            ['id_pemesanan' => 4, 'id_barang' => 6, 'jumlah' => 5,  'harga_satuan' => 36000,  'subtotal' => 180000],
            ['id_pemesanan' => 4, 'id_barang' => 4, 'jumlah' => 9,  'harga_satuan' => 15000,  'subtotal' => 135000],
            ['id_pemesanan' => 5, 'id_barang' => 1, 'jumlah' => 3,  'harga_satuan' => 120000, 'subtotal' => 360000],
            ['id_pemesanan' => 5, 'id_barang' => 4, 'jumlah' => 6,  'harga_satuan' => 15000,  'subtotal' =>  90000],
            ['id_pemesanan' => 6, 'id_barang' => 2, 'jumlah' => 6,  'harga_satuan' => 75000,  'subtotal' => 450000],
            ['id_pemesanan' => 6, 'id_barang' => 5, 'jumlah' => 5,  'harga_satuan' => 48000,  'subtotal' => 240000],
            ['id_pemesanan' => 7, 'id_barang' => 3, 'jumlah' => 2,  'harga_satuan' => 145000, 'subtotal' => 290000],
        ];

        foreach ($details as $d) {
            DB::table('detail_pemesanans')->insert(array_merge($d, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // =====================
        // 7. PEMBAYARAN
        // =====================
        $pembayarans = [
            // PMS-001: Cicil 2x → Lunas
            [
                'no_pembayaran' => 'PAY-' . Carbon::now()->subDays(14)->format('Ymd') . '-001',
                'id_pemesanan'  => 1, 'tanggal_bayar' => Carbon::now()->subDays(14),
                'jumlah_bayar'  => 700000,  'total_tagihan' => 1200000, 'sisa_tagihan' => 500000,
                'metode_bayar'  => 'transfer', 'status_bayar' => 'belum_lunas',
                'bukti_bayar'   => null, 'keterangan' => 'Pembayaran pertama',
            ],
            [
                'no_pembayaran' => 'PAY-' . Carbon::now()->subDays(10)->format('Ymd') . '-001',
                'id_pemesanan'  => 1, 'tanggal_bayar' => Carbon::now()->subDays(10),
                'jumlah_bayar'  => 500000,  'total_tagihan' => 1200000, 'sisa_tagihan' => 0,
                'metode_bayar'  => 'tunai',    'status_bayar' => 'lunas',
                'bukti_bayar'   => null, 'keterangan' => 'Pelunasan',
            ],
            // PMS-002: Lunas 1x
            [
                'no_pembayaran' => 'PAY-' . Carbon::now()->subDays(11)->format('Ymd') . '-002',
                'id_pemesanan'  => 2, 'tanggal_bayar' => Carbon::now()->subDays(11),
                'jumlah_bayar'  => 875000,  'total_tagihan' => 875000,  'sisa_tagihan' => 0,
                'metode_bayar'  => 'transfer', 'status_bayar' => 'lunas',
                'bukti_bayar'   => null, 'keterangan' => null,
            ],
            // PMS-003: Baru DP
            [
                'no_pembayaran' => 'PAY-' . Carbon::now()->subDays(8)->format('Ymd') . '-001',
                'id_pemesanan'  => 3, 'tanggal_bayar' => Carbon::now()->subDays(8),
                'jumlah_bayar'  => 1000000, 'total_tagihan' => 2340000, 'sisa_tagihan' => 1340000,
                'metode_bayar'  => 'transfer', 'status_bayar' => 'belum_lunas',
                'bukti_bayar'   => null, 'keterangan' => 'DP pertama',
            ],
            // PMS-004: Bayar sebagian
            [
                'no_pembayaran' => 'PAY-' . Carbon::now()->subDays(4)->format('Ymd') . '-001',
                'id_pemesanan'  => 4, 'tanggal_bayar' => Carbon::now()->subDays(4),
                'jumlah_bayar'  => 300000,  'total_tagihan' => 560000,  'sisa_tagihan' => 260000,
                'metode_bayar'  => 'tunai',    'status_bayar' => 'belum_lunas',
                'bukti_bayar'   => null, 'keterangan' => null,
            ],
        ];

        foreach ($pembayarans as $pb) {
            DB::table('pembayarans')->insert(array_merge($pb, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}