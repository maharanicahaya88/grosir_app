<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Konsumen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function pemesanan(Request $request)
    {
        $filters = $request->only(['status', 'konsumen', 'dari', 'sampai']);

        $pesanan = Pemesanan::with('konsumen')
            ->filter($filters)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $query = Pemesanan::filter($filters);

        $stats = [
            'total'       => (clone $query)->count(),
            'total_nilai' => (clone $query)->sum('total_harga'),
            'menunggu'    => (clone $query)->where('status_pesan', 'menunggu')->count(),
            'diproses'    => (clone $query)->where('status_pesan', 'diproses')->count(),
            'disetujui'   => (clone $query)->where('status_pesan', 'disetujui')->count(),
            'dibatalkan'  => (clone $query)->where('status_pesan', 'dibatalkan')->count(),
        ];

        // Top 10 konsumen berdasarkan nilai pesanan
        $rekapKonsumen = Pemesanan::filter($request->only(['status', 'dari', 'sampai']))
            ->join('konsumens', 'pemesanans.id_konsumen', '=', 'konsumens.id')
            ->groupBy('konsumens.id', 'konsumens.nama_konsumen')
            ->select(
                'konsumens.nama_konsumen',
                DB::raw('COUNT(*) as jumlah_pesanan'),
                DB::raw('SUM(total_harga) as total_nilai')
            )
            ->orderByDesc('total_nilai')
            ->limit(10)
            ->get();

        // Tren pesanan per bulan (12 bulan terakhir)
        $trenBulanan = Pemesanan::filter($filters)
            ->selectRaw("strftime('%Y-%m', tanggal_pesan) as bulan, COUNT(*) as jumlah, SUM(total_harga) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->limit(12)
            ->get();

        $konsumens = Konsumen::where('status', 'aktif')->orderBy('nama_konsumen')->get();

        return view('laporan.pemesanan', compact(
            'pesanan', 'stats', 'rekapKonsumen', 'trenBulanan', 'konsumens'
        ));
    }

    public function pemesananPdf(Request $request)
    {
        $pesanan = Pemesanan::with('konsumen')
            ->filter($request->only(['status', 'konsumen', 'dari', 'sampai']))
            ->latest()
            ->get();

        $stats = [
            'total'       => $pesanan->count(),
            'total_nilai' => $pesanan->sum('total_harga'),
            'disetujui'   => $pesanan->where('status_pesan', 'disetujui')->count(),
        ];

        $dari   = $request->dari   ?? '-';
        $sampai = $request->sampai ?? '-';

        $pdf = Pdf::loadView('laporan.pemesanan_pdf', compact('pesanan', 'stats', 'dari', 'sampai'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('laporan-pemesanan.pdf');
    }
}