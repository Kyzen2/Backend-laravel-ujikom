<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun', 'desc')->get();

        return view('admin.tahun-ajaran.index', compact('tahunAjaran'));
    }
}
