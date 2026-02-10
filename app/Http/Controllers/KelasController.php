<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    private function findKelasById($id)
    {
        return Kelas::findOrFail($id);
    }

    public function index(Request $request)
    {
        $data = Kelas::with(['tahunAjaran', 'wali'])->get();

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return view('kelas/index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('kelas/form', [
            'tahunAjaran' => TahunAjaran::all(),
            'guru' => Guru::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $status = Kelas::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => (bool) $status,
                'message' => $status ? 'Berhasil ditambahkan' : 'Gagal ditambahkan',
            ], $status ? 200 : 500);
        }

        if ($status) {
            return redirect('/kelas')->with('success', 'Data kelas berhasil ditambahkan');
        }

        return redirect('/kelas')->with('error', 'Data kelas gagal ditambahkan');
    }

    public function edit($id)
    {
        $data = $this->findKelasById($id);

        return view('kelas/form', [
            'data' => $data,
            'tahunAjaran' => TahunAjaran::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $kelas = $this->findKelasById($id);

        $validated = $request->validate([
            'nama_kelas' => 'required|max:50',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $status = $kelas->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => (bool) $status,
                'message' => $status ? 'Berhasil diupdate' : 'Gagal diupdate',
            ], $status ? 200 : 500);
        }

        if ($status) {
            return redirect('/kelas')->with('success', 'Data kelas berhasil diupdate');
        }

        return redirect('/kelas')->with('error', 'Data kelas gagal diupdate');
    }

    public function destroy(Request $request, $id)
    {
        $kelas = $this->findKelasById($id);
        $status = $kelas->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => (bool) $status,
                'message' => $status ? 'Berhasil dihapus' : 'Gagal dihapus',
            ], $status ? 200 : 500);
        }

        if ($status) {
            return redirect('/kelas')->with('success', 'Data kelas berhasil dihapus');
        }

        return redirect('/kelas')->with('error', 'Data kelas gagal dihapus');
    }
}
