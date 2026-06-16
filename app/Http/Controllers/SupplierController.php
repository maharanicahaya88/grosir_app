<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::when($request->search, function ($q, $search) {
                $q->where('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('kode_supplier', 'like', "%{$search}%");
            })
            ->orderBy('nama_supplier')
            ->paginate(15)
            ->withQueryString();

        return view('master.supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
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

        return back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $supplier->update($request->only(['nama_supplier', 'alamat', 'telepon', 'email', 'status']));

        return back()->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus.');
    }
}