<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use Illuminate\Http\Request;

class KonsumenController extends Controller
{
    public function index(Request $request)
    {
        $konsumens = Konsumen::when($request->search, function ($q, $search) {
                $q->where('nama_konsumen', 'like', "%{$search}%")
                  ->orWhere('kode_konsumen', 'like', "%{$search}%");
            })
            ->orderBy('nama_konsumen')
            ->paginate(15)
            ->withQueryString();

        return view('master.konsumen.index', compact('konsumens'));
    }

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

        return back()->with('success', 'Konsumen berhasil ditambahkan.');
    }

    public function update(Request $request, Konsumen $konsumen)
    {
        $request->validate([
            'nama_konsumen' => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $konsumen->update($request->only(['nama_konsumen', 'alamat', 'telepon', 'email', 'status']));

        return back()->with('success', 'Konsumen berhasil diperbarui.');
    }

    public function destroy(Konsumen $konsumen)
    {
        $konsumen->delete();
        return back()->with('success', 'Konsumen berhasil dihapus.');
    }
}