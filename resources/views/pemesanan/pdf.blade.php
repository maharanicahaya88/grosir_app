{{-- resources/views/pemesanan/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
  body { padding: 30px; color: #1a1a1a; }
  .kop { text-align: center; border-bottom: 2px solid #1a1a1a; padding-bottom: 12px; margin-bottom: 20px; }
  .kop h2 { font-size: 18px; font-weight: bold; letter-spacing: 1px; }
  .kop p  { color: #555; margin-top: 4px; }
  .judul  { text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline; margin-bottom: 16px; }
  .info-table { width: 100%; margin-bottom: 20px; }
  .info-table td { padding: 3px 6px; vertical-align: top; }
  .info-table td:first-child { width: 140px; color: #555; }
  .info-table td:nth-child(2) { width: 10px; }
  table.detail { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  table.detail th { background: #f0f0f0; border: 0.5px solid #bbb; padding: 6px 8px; text-align: left; }
  table.detail td { border: 0.5px solid #bbb; padding: 6px 8px; }
  table.detail .text-right { text-align: right; }
  .total-row td { font-weight: bold; background: #f9f9f9; }
  .ttd { margin-top: 40px; display: flex; justify-content: space-between; }
  .ttd-box { width: 200px; text-align: center; }
  .ttd-space { height: 60px; }
</style>
</head>
<body>
  <div class="kop">
    <h2>TOKO GROSIR MAKMUR</h2>
    <p>Jl. Raya Pasar No. 10 — Telp: (022) 123-4567 — Email: grosir@makmur.id</p>
  </div>

  <div class="judul">SURAT PESANAN (PURCHASE ORDER)</div>

  <table class="info-table">
    <tr><td>No. Pesanan</td><td>:</td><td><strong>{{ $pemesanan->no_pemesanan }}</strong></td></tr>
    <tr><td>Tanggal Pesan</td><td>:</td><td>{{ $pemesanan->tanggal_pesan->format('d F Y') }}</td></tr>
    @if($pemesanan->tanggal_kirim)
    <tr><td>Tanggal Kirim</td><td>:</td><td>{{ $pemesanan->tanggal_kirim->format('d F Y') }}</td></tr>
    @endif
    <tr><td>Kepada</td><td>:</td><td>{{ $pemesanan->konsumen->nama_konsumen }}</td></tr>
    <tr><td>Alamat</td><td>:</td><td>{{ $pemesanan->konsumen->alamat ?? '-' }}</td></tr>
    <tr><td>Status</td><td>:</td><td><strong>{{ strtoupper($pemesanan->status_pesan) }}</strong></td></tr>
  </table>

  <table class="detail">
    <thead>
      <tr>
        <th style="width:30px">No</th>
        <th style="width:80px">Kode</th>
        <th>Nama Barang</th>
        <th style="width:60px">Satuan</th>
        <th style="width:50px">Qty</th>
        <th style="width:110px" class="text-right">Harga Satuan</th>
        <th style="width:120px" class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pemesanan->detailPemesanans as $i => $d)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $d->barang->kode_barang }}</td>
        <td>{{ $d->barang->nama_barang }}</td>
        <td>{{ $d->barang->satuan }}</td>
        <td>{{ number_format($d->jumlah_pesan, 0, ',', '.') }}</td>
        <td class="text-right">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
        <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="6" class="text-right">TOTAL</td>
        <td class="text-right">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
      </tr>
    </tfoot>
  </table>

  @if($pemesanan->keterangan)
  <p><strong>Keterangan:</strong> {{ $pemesanan->keterangan }}</p>
  @endif

  <div class="ttd">
    <div class="ttd-box">
      <p>Dipesan oleh,</p>
      <div class="ttd-space"></div>
      <p>( _________________ )</p>
    </div>
    <div class="ttd-box">
      <p>Disetujui oleh,</p>
      <div class="ttd-space"></div>
      <p>( _________________ )</p>
    </div>
  </div>
</body>
</html>
