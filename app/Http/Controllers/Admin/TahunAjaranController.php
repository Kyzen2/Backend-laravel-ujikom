<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun', 'desc')->get();

        return view('admin.tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|unique:tahun_ajaran,tahun',
            'semester' => 'required|string',
        ]);

        TahunAjaran::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }


    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun' => 'required|string|unique:tahun_ajaran,tahun,' . $tahunAjaran->id,
            'semester' => 'required|string',
            'status' => 'required|string',
        ]);

        $tahunAjaran->update([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'status' => $request->status,
        ]);

        return redirect('/admin/tahun-ajaran')
            ->with('success', 'Tahun ajaran berhasil diupdate');
    }

    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->delete();

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus');
    }

    // public function setActive($id)
    // {
    //     TahunAjaran::query()->update(['is_active' => false]);

    //     TahunAjaran::where('id', $id)->update(['is_active' => true]);

    //     return redirect()
    //         ->route('admin.tahun-ajaran.index')
    //         ->with('success', 'Tahun ajaran aktif berhasil diubah');
    // }
}
