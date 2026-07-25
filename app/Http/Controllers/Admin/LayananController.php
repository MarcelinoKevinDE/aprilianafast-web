<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layananList = Layanan::orderBy('nama_layanan')->paginate(10);

        return view('admin.layanan', compact('layananList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:100'],
            'deskripsi'    => ['nullable', 'string', 'max:500'],
            'harga'        => ['required', 'integer', 'min:0'],
        ]);

        Layanan::create($validated + ['aktif' => true]);

        return back()->with('status', 'Layanan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:100'],
            'deskripsi'    => ['nullable', 'string', 'max:500'],
            'harga'        => ['required', 'integer', 'min:0'],
            'aktif'        => ['nullable', 'boolean'],
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $layanan->update($validated);

        return back()->with('status', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return back()->with('status', 'Layanan berhasil dihapus.');
    }
}

