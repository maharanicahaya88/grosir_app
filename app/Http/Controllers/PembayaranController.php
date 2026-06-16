<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('pemesanan.konsumen')
            ->orderByDesc('tanggal_bayar');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('no_pembayaran', 'like', "%{$request->search}%")
                  ->orWhereHas('pemesanan', fn($q2) =>
                      $q2->where('no_pemesanan', 'like', "%{$request->search}%")
                  );
            });
        }

        if ($request->status) {
            $query->where('status_bayar', $request->status);
        }

        if ($request->dari) {
            $query->whereDate('tanggal_bayar', '>=', $request->dari);
        }

        if ($request->sampai) {
            $query->whereDate('tanggal_bayar', '<=', $request->sampai);
        }

        $pembayaran = $query->paginate(15)->withQueryString();

        $stats = [
            'total'       => Pembayaran::count(),
            'lunas'       => Pembayaran::where('status_bayar', 'lunas')->count(),
            'belum_lunas' => Pembayaran::where('status_bayar', 'belum_lunas')->count(),
            'total_nilai' => Pembayaran::sum('jumlah_bayar'),
        ];

        return view('pembayaran.index', compact('pembayaran','stats'));
    }

    public function create(Request $request)
    {
        $pemesanan = Pemesanan::with('konsumen', 'pembayarans')
            ->where('status_pesan', 'disetujui')
            ->get()
            ->filter(fn($p) => $p->sisaTagihan() > 0);

        $selectedPemesanan = null;
        if ($request->id_pemesanan) {
            $selectedPemesanan = Pemesanan::with('konsumen', 'pembayarans')
                ->find($request->id_pemesanan);
        }

        return view('pembayaran.create', compact('pemesanan', 'selectedPemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan'  => 'required|exists:pemesanans,id',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar'  => 'required|numeric|min:1',
            'metode_bayar'  => 'required|in:tunai,transfer,cek,giro',
            'bukti_bayar'   => 'nullable|image|max:2048',
            'keterangan'    => 'nullable|string',
        ]);

        $pemesanan    = Pemesanan::with('pembayarans')->findOrFail($request->id_pemesanan);
        $totalDibayar = $pemesanan->totalDibayar() + $request->jumlah_bayar;
        $sisa         = max(0, $pemesanan->total_harga - $totalDibayar);
        $status       = $sisa <= 0 ? 'lunas' : 'belum_lunas';

        $bukti = null;
        if ($request->hasFile('bukti_bayar')) {
            $bukti = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        $nomorUrut = str_pad(
            Pembayaran::whereDate('created_at', today())->count() + 1,
            3, '0', STR_PAD_LEFT
        );

        Pembayaran::create([
            'no_pembayaran' => 'PAY-' . date('Ymd') . '-' . $nomorUrut,
            'id_pemesanan'  => $pemesanan->id,
            'tanggal_bayar' => $request->tanggal_bayar,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'total_tagihan' => $pemesanan->total_harga,
            'sisa_tagihan'  => $sisa,
            'metode_bayar'  => $request->metode_bayar,
            'status_bayar'  => $status,
            'bukti_bayar'   => $bukti,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dicatat!');
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('pemesanan.konsumen', 'pemesanan.detailPemesanans.barang');
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_bayar) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}