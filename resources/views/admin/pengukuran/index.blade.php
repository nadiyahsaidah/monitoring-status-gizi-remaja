@extends('layouts.dashboard')

@section('judul', 'Data Pengukuran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary mb-3" href="{{ route('pengukuran.create') }}">Tambah</a>
                <div class="table-responsive">
                    <table class="table-striped table" id="datatable">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    No
                                </th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Tanggal Pengukuran</th>
                                <th>Usia </th>
                                <th>BB</th>
                                <th>TB</th>
                                <th>Status Gizi</th>
                                <th>LILA</th>
                                <th>Tensi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengukurans as $key => $pengukuran)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $pengukuran->remaja->user->username }}</td>
                                <td>{{ $pengukuran->remaja->user->nama }}</td>
                                <td>{{ $pengukuran->remaja->jenis_kelamin }}</td>
                                <td>{{ $pengukuran->remaja->tanggal_lahir }}</td>
                                <td>{{ $pengukuran->tanggal_pengukuran }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengukuran->remaja->tanggal_lahir)->age }}</td>
                                <td>{{ $pengukuran->bb }}</td>
                                <td>{{ $pengukuran->tb }}</td>
                                <td><span class="badge badge-success">{{ $pengukuran->status_gizi }}</span></td>
                                <td>{{ $pengukuran->lila }}</td>
                                <td>{{ $pengukuran->tensi }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('pengukuran.edit', $pengukuran->id) }}" class="btn btn-warning mx-2">Edit</a>
                                        <form action="{{ route('pengukuran.destroy', $pengukuran->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
