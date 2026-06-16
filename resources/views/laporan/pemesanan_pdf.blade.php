{{-- resources/views/laporan/pemesanan_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { font-family: Arial, sans-serif; font-size: 11px; margin:0; padding:0; }
    body { padding: 20px; color: #111; }
    .kop { border-bottom: 2px solid #1a56db; padding-bottom:10px; margin-bottom:16px; }
    .kop h2 { font-size:16px; color:#1a56db; font-weight:bold; }
    .kop p  { color:#555; margin-top:2px; font-size:10px; }
    h3.judul { font-size:13px; font-weight:bold; margin-bottom:4px; }
    .periode { color:#666; font-size:10px; margin-bottom:16px; }
    .stats { display:table; width:100%; margin-bottom:16px; }
    .stat-box { display:table-cell; width:25%; padding:10px; background:#f5f5f5; border:1px solid #ddd; text-align:center; }
    .stat-box .label { font-size:9px; color:#666; }
    .stat-box .val { font-size:16px; font-weight:bold; color:#1a56db; }
    table.data { width:100%; border-collapse:collapse; margin-top:12px; }
    table.data th { background:#1a56db; color:#fff; padding:6px 8px; text-align:left; font-size:10px; }
    table.data td { padding:6px 8px; border-bottom:1px solid #e5e7eb; font-size:10px; }
    table.data tr:nth-child(even) td { background:#f9fafb; }
    .badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:bold; }
    .badge-menunggu  { background:#fef3c7; color:#92400e; }
    .badge-diproses  { background:#dbeafe; color:#1e40af; }
    .badge-disetujui { background:#d1fae5; color:#065f46; }
    .badge-dibatalkan{ background:#fee2e2; color:#991b1b; }
    .text-right { text-align:right; }
    .footer { margin-top:24px; font-size:9px; color:#aaa; text-align:center; border-top:1px solid #e5e7eb; padding-top:8px; }
</style>
</head>
<body>
    <div class="kop">
        <h2>TOKO GROSIR MAKMUR</h2>
        <p>Jl. Raya Pasar No. 10 &bull; Telp: (022) 123-4567 &bull; Email: grosir@makmur.id</p>
    </div>

    <h3 class="judul">LAPORAN PEMESANAN</h3>
    <p class="periode">Periode: {{ $dari }} s/d {{ $sampai }} &bull; Dicetak: {{ now()->format('d M Y H:i') }}</p>

    <div class="stats">
        <div class="stat-box">
            <div class="label">Total Pesanan</div>
            <div class="val">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Nilai</div>
            <div class="val" style="font-size:11px">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Disetujui</div>
            <div class="val" style="color:#065f46">{{ $stats['disetujui'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Rata-rata</div>
            <div class="val" style="font-size:11px">
                Rp {{ $stats['total'] > 0 ? number_format($stats['total_nilai'] / $stats['total'], 0, ',', '.') : 0 }}
            </div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Konsumen</th>
                <th class="text-right">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:bold;color:#1a56db">{{ $p->no_pemesanan }}</td>
                <td>{{ $p->tanggal_pesan->format('d/m/Y') }}</td>
                <td>{{ $p->konsumen->nama_konsumen }}</td>
                <td class="text-right">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                <td><span class="badge badge-{{ $p->status_pesan }}">{{ ucfirst($p->status_pesan) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;padding:20px;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        @if($pesanan->count() > 0)
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold;background:#f5f5f5">TOTAL</td>
                <td class="text-right" style="font-weight:bold;background:#f5f5f5">
                    Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}
                </td>
                <td style="background:#f5f5f5"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem Aplikasi Penjualan Grosir &bull; {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
