<?php
namespace App\Http\Controllers;

use App\Models\Konsumen;
use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;

// ===================================================================
// KONSUMEN CONTROLLER
// ===================================================================
class KonsumenController extends Controller
{
    public function index(Request $request)
    {
        $konsumens = Konsumen::when($request->search, fn($q, $v) =>
            $q->where('nama_konsumen', 'like', "%{$v}%")
              ->orWhere('kode_konsumen', 'like', "%{$v}%")
        )->latest()->paginate(15)->withQueryString();
        return view('master.konsumen.index', compact('konsumens'));
    }

    public function create()  { return redirect()->route('konsumen.index'); }
    public function show(Konsumen $konsumen) { return redirect()->route('konsumen.index'); }

    public function store(Request $request)
    {
        $request->validate([
            'nama_konsumen' => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
        ]);
        Konsumen::create([
            'kode_konsumen' => Konsumen::generateKode(),
            'nama_konsumen' => $request->nama_konsumen,
            'alamat'        => $request->alamat,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'status'        => 'aktif',
        ]);
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil ditambahkan.');
    }

    public function edit(Konsumen $konsumen) { return redirect()->route('konsumen.index'); }

    public function update(Request $request, Konsumen $konsumen)
    {
        $request->validate([
            'nama_konsumen' => 'required|string|max:100',
            'email'         => 'nullable|email|max:100',
        ]);
        $konsumen->update([
            'nama_konsumen' => $request->nama_konsumen,
            'alamat'        => $request->alamat,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'status'        => $request->status ?? 'aktif',
        ]);
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil diperbarui.');
    }

    public function destroy(Konsumen $konsumen)
    {
        if ($konsumen->pemesanans()->exists()) {
            return back()->with('error', 'Konsumen tidak dapat dihapus karena memiliki data pesanan.');
        }
        $konsumen->delete();
        return redirect()->route('konsumen.index')->with('success', 'Konsumen berhasil dihapus.');
    }
}

// ===================================================================
// SUPPLIER CONTROLLER
// ===================================================================
class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::when($request->search, fn($q, $v) =>
            $q->where('nama_supplier', 'like', "%{$v}%")
              ->orWhere('kode_supplier', 'like', "%{$v}%")
        )->latest()->paginate(15)->withQueryString();
        return view('master.supplier.index', compact('suppliers'));
    }

    public function create()  { return redirect()->route('supplier.index'); }
    public function show(Supplier $supplier) { return redirect()->route('supplier.index'); }
    public function edit(Supplier $supplier) { return redirect()->route('supplier.index'); }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'email'         => 'nullable|email|max:100',
        ]);
        Supplier::create([
            'kode_supplier' => Supplier::generateKode(),
            'nama_supplier' => $request->nama_supplier,
            'alamat'        => $request->alamat,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'status'        => 'aktif',
        ]);
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(['nama_supplier' => 'required|string|max:100']);
        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat'        => $request->alamat,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'status'        => $request->status ?? 'aktif',
        ]);
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }
}

// ===================================================================
// BARANG CONTROLLER
// ===================================================================
class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::when($request->search, fn($q, $v) =>
            $q->where('nama_barang', 'like', "%{$v}%")
              ->orWhere('kode_barang', 'like', "%{$v}%")
        )->latest()->paginate(15)->withQueryString();
        return view('master.barang.index', compact('barangs'));
    }

    public function create()  { return redirect()->route('barang.index'); }
    public function show(Barang $barang) { return redirect()->route('barang.index'); }
    public function edit(Barang $barang) { return redirect()->route('barang.index'); }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'   => 'required|string|max:100',
            'satuan'        => 'required|string|max:20',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'stok_minimum'  => 'required|integer|min:0',
        ]);
        Barang::create([
            'kode_barang'  => Barang::generateKode(),
            'nama_barang'  => $request->nama_barang,
            'satuan'       => $request->satuan,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual,
            'stok'         => $request->stok,
            'stok_minimum' => $request->stok_minimum,
            'keterangan'   => $request->keterangan,
            'status'       => 'aktif',
        ]);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga_jual'  => 'required|numeric|min:0',
        ]);
        $barang->update([
            'nama_barang'  => $request->nama_barang,
            'satuan'       => $request->satuan,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual,
            'stok'         => $request->stok,
            'stok_minimum' => $request->stok_minimum,
            'keterangan'   => $request->keterangan,
            'status'       => $request->status ?? 'aktif',
        ]);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->detailPemesanans()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sudah digunakan dalam pesanan.');
        }
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
