@extends('layouts.dashboard')

@section('judul', 'Data Pengukuran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                <form action="{{ route('pengukuran.index') }}" method="GET" class="mb-3">
                    <div class="form-row align-items-center">
                        <div class="col-md-3">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary mt-4">Filter</button>
                        </div>
                    </div>
                </form>
                
                <div class="d-flex mb-3">
                @if (auth()->user()->role == 'admin')
                    <a class="btn btn-primary mt-4 me-2" href="{{ route('pengukuran.create') }}">Tambah</a>
                @endif
                    <a href="{{ route('cetakPDF', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-danger mt-4 mx-2">Cetak PDF</a>
                    <a href="{{ route('exportExcel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success mt-4">Cetak Excel</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Tanggal Pengukuran</th>
                                <th>Usia</th>
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
                                <td>
                                    @php
                                    $badgeClass = 'badge badge-secondary'; // Default class
                                    switch (strtolower($pengukuran->status_gizi)) {
                                        case 'gizi buruk':
                                            $badgeClass = 'badge badge-danger';
                                            break;
                                        case 'gizi kurang':
                                            $badgeClass = 'badge badge-warning';
                                            break;
                                        case 'gizi baik':
                                            $badgeClass = 'badge badge-success';
                                            break;
                                        case 'gizi lebih':
                                            $badgeClass = 'badge badge-info';
                                            break;
                                        case 'obesitas':
                                            $badgeClass = 'badge badge-primary';
                                            break;
                                    }
                                    @endphp
                                    <span class="{{ $badgeClass }}">{{ $pengukuran->status_gizi }}</span>
                                </td>
                                <td>{{ $pengukuran->lila }}</td>
                                <td>{{ $pengukuran->tensi }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('pengukuran.edit', $pengukuran->id) }}" class="btn btn-warning mx-2">Edit</a>
                                        <form id="deleteForm{{ $pengukuran->id }}" action="{{ route('pengukuran.destroy', $pengukuran->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="deleteConfirmation({{ $pengukuran->id }})">Hapus</button>
                                        </form>
                                    </div>
                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (auth()->user()->role == 'remaja')
            <div class="table-responsive">
                <table class="table table-bordered"  id="datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengukuran</th>
                            <th>BB</th>
                            <th>TB</th>
                            <th>LILA</th>
                            <th>Tensi</th>
                            <th>Status Gizi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengukuranRemaja as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengukuran)->isoFormat('D MMMM YYYY') }}</td>
                            <td>{{ $item->bb }}</td>
                            <td>{{ $item->tb }}</td>
                            <td>{{ $item->lila }}</td>
                            <td>{{ $item->tensi }}</td>
                            <td>
                                @php
                                $badgeClass = 'badge badge-secondary'; // Default class
                                switch (strtolower($item->status_gizi)) {
                                    case 'gizi buruk':
                                        $badgeClass = 'badge badge-danger';
                                        break;
                                    case 'gizi kurang':
                                        $badgeClass = 'badge badge-warning';
                                        break;
                                    case 'gizi baik':
                                        $badgeClass = 'badge badge-success';
                                        break;
                                    case 'gizi lebih':
                                        $badgeClass = 'badge badge-info';
                                        break;
                                    case 'obesitas':
                                        $badgeClass = 'badge badge-primary';
                                        break;
                                }
                                @endphp
                                <span class="{{ $badgeClass }}">{{ $item->status_gizi }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function deleteConfirmation(id) {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>

@endsection
