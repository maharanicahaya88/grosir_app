<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::when($request->search, function ($q, $search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            })
            ->orderBy('nama_barang')
            ->paginate(15)
            ->withQueryString();

        return view('master.barang.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'  => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        Barang::create([
            'kode_barang'  => Barang::generateKode(),
            'nama_barang'  => $request->nama_barang,
            'satuan'       => $request->satuan,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual,
            'stok'         => $request->stok,
            'stok_minimum' => $request->stok_minimum ?? 5,
            'keterangan'   => $request->keterangan,
            'status'       => 'aktif',
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang'  => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'keterangan'   => 'nullable|string',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $barang->update($request->only([
            'nama_barang', 'satuan', 'harga_beli', 'harga_jual',
            'stok', 'stok_minimum', 'keterangan', 'status',
        ]));

        return back()->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}