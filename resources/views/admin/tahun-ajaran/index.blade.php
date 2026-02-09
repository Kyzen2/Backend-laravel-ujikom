@extends('admin.layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tahun Ajaran</h3>
                <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalTambah">
                    Tambah
                </button>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Semester</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tahunAjaran as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $row->tahun }}</td>
                            <td>{{ $row->semester }}</td>
                            <td>{{ $row->is_active ? 'Ya' : 'Tidak' }}</td>
                            <td>
                                <form action="{{ route('admin.tahun-ajaran.destroy',$row->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection

<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="text" name="tahun" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1">
                        <label>Aktif</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>