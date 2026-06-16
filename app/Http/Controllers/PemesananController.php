<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Konsumen;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PemesananController extends Controller
{
    // =====================================================================
    // INDEX — Daftar semua pesanan
    // =====================================================================
    public function index(Request $request)
    {
        $pesanan = Pemesanan::with('konsumen')
            ->filter($request->only(['status', 'konsumen', 'dari', 'sampai', 'search']))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $konsumens = Konsumen::aktif()->get();

        $stats = [
            'total'      => Pemesanan::count(),
            'menunggu'   => Pemesanan::menunggu()->count(),
            'disetujui'  => Pemesanan::disetujui()->count(),
            'dibatalkan' => Pemesanan::where('status_pesan', 'dibatalkan')->count(),
        ];

        return view('pemesanan.index', compact('pesanan', 'konsumens', 'stats'));
    }

    // =====================================================================
    // CREATE — Form buat pesanan baru
    // =====================================================================
    public function create()
    {
        $konsumens     = Konsumen::where('status', 'aktif')->get();
        $barangs       = Barang::where('status', 'aktif')->get();
        $no_pemesanan  = Pemesanan::generateKode();

        return view('pemesanan.create', compact('konsumens', 'barangs', 'no_pemesanan'));
    }

    // =====================================================================
    // STORE — Simpan pesanan baru
    // =====================================================================
    public function store(Request $request)
    {
        $request->validate([
            'id_konsumen'               => 'required|exists:konsumens,id',
            'tanggal_pesan'             => 'required|date',
            'tanggal_kirim'             => 'nullable|date|after_or_equal:tanggal_pesan',
            'keterangan'                => 'nullable|string|max:500',
            'details'                   => 'required|array|min:1',
            'details.*.id_barang'       => 'required|exists:barangs,id',
            'details.*.jumlah_pesan'    => 'required|integer|min:1',
            'details.*.harga_satuan'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $pemesanan = Pemesanan::create([
                'no_pemesanan'  => Pemesanan::generateKode(),
                'tanggal_pesan' => $request->tanggal_pesan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'id_konsumen'   => $request->id_konsumen,
                'status_pesan'  => 'menunggu',
                'keterangan'    => $request->keterangan,
            ]);

            foreach ($request->details as $item) {
                $pemesanan->detailPemesanans()->create([
                    'id_barang'     => $item['id_barang'],
                    'jumlah_pesan'  => $item['jumlah_pesan'],
                    'harga_satuan'  => $item['harga_satuan'],
                    'subtotal'      => $item['jumlah_pesan'] * $item['harga_satuan'],
                ]);
            }

            $pemesanan->hitungTotal();
        });

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    // =====================================================================
    // SHOW — Detail pesanan
    // =====================================================================
    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load('konsumen', 'details.barang');
        return view('pemesanan.show', compact('pemesanan'));
    }

    // =====================================================================
    // EDIT — Form edit pesanan
    // =====================================================================
    public function edit(Pemesanan $pemesanan)
    {
        abort_if($pemesanan->status_pesan !== 'menunggu', 403, 'Pesanan tidak dapat diedit.');

        $konsumens = Konsumen::where('status', 'aktif')->get();
        $barangs   = Barang::where('status', 'aktif')->get();
        $pemesanan->load('details.barang');

        return view('pemesanan.edit', compact('pemesanan', 'konsumens', 'barangs'));
    }

    // =====================================================================
    // UPDATE — Simpan perubahan pesanan
    // =====================================================================
    public function update(Request $request, Pemesanan $pemesanan)
    {
        abort_if($pemesanan->status_pesan !== 'menunggu', 403, 'Pesanan tidak dapat diedit.');

        $request->validate([
            'id_konsumen'               => 'required|exists:konsumens,id',
            'tanggal_pesan'             => 'required|date',
            'tanggal_kirim'             => 'nullable|date|after_or_equal:tanggal_pesan',
            'details'                   => 'required|array|min:1',
            'details.*.id_barang'       => 'required|exists:barangs,id',
            'details.*.jumlah_pesan'    => 'required|integer|min:1',
            'details.*.harga_satuan'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $pemesanan) {
            $pemesanan->update([
                'tanggal_pesan' => $request->tanggal_pesan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'id_konsumen'   => $request->id_konsumen,
                'keterangan'    => $request->keterangan,
            ]);

            $pemesanan->details()->delete();
            foreach ($request->details as $item) {
                $pemesanan->details()->create([
                    'id_barang'    => $item['id_barang'],
                    'jumlah_pesan' => $item['jumlah_pesan'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal'     => $item['jumlah_pesan'] * $item['harga_satuan'],
                ]);
            }
            $pemesanan->hitungTotal();
        });

        return redirect()->route('pemesanan.show', $pemesanan)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    // =====================================================================
    // DESTROY — Hapus / batalkan pesanan
    // =====================================================================
    public function destroy(Pemesanan $pemesanan)
    {
        abort_if(
            in_array($pemesanan->status_pesan, ['disetujui', 'diproses']),
            403,
            'Pesanan yang sudah diproses tidak dapat dihapus.'
        );

        $pemesanan->update(['status_pesan' => 'dibatalkan']);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // =====================================================================
    // APPROVE — Setujui pesanan
    // =====================================================================
    public function approve(Pemesanan $pemesanan)
    {
        abort_if($pemesanan->status_pesan !== 'menunggu', 422, 'Status tidak valid.');

        $pemesanan->update(['status_pesan' => 'disetujui']);

        return back()->with('success', "Pesanan {$pemesanan->no_pemesanan} disetujui.");
    }

    // =====================================================================
    // CETAK PDF — Cetak surat pesanan
    // =====================================================================
    public function cetakPdf(Pemesanan $pemesanan)
    {
        $pemesanan->load('konsumen', 'detailPemesanans.barang');
        $pdf = Pdf::loadView('pemesanan.pdf', compact('pemesanan'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("surat-pesanan-{$pemesanan->no_pemesanan}.pdf");
    }

    // =====================================================================
    // EXPORT EXCEL — Export daftar pesanan
    // =====================================================================
    public function exportExcel(Request $request)
    {
        $pesanan = Pemesanan::with('konsumen')
            ->filter($request->only(['status', 'dari', 'sampai']))
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-pemesanan.csv"',
        ];

        $callback = function () use ($pesanan) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['No. Pesanan','Tanggal','Konsumen','Total','Status']);
            foreach ($pesanan as $p) {
                fputcsv($file, [
                    $p->no_pemesanan,
                    $p->tanggal_pesan->format('d/m/Y'),
                    $p->konsumen->nama_konsumen,
                    $p->total_harga,
                    $p->status_pesan,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // =====================================================================
    // API — Ambil harga barang (untuk form dinamis)
    // =====================================================================
    public function getHargaBarang(Barang $barang)
    {
        return response()->json([
            'id'           => $barang->id,
            'kode_barang'  => $barang->kode_barang,
            'nama_barang'  => $barang->nama_barang,
            'satuan'       => $barang->satuan,
            'harga_jual'   => $barang->harga_jual,
            'stok'         => $barang->stok,
        ]);
    }
}
