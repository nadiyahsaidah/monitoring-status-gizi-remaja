@extends('layouts.dashboard')

@section('judul', 'Data Petugas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Button trigger modal for Add Petugas -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalAddPetugas">
                        Tambah
                    </button>
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>NIK</th>
                                    <th>NIP</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jabatan</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($petugas as $petugas)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $petugas->user->username }}</td>
                                        <td>{{ $petugas->user->nama }}</td>
                                        <td>{{ $petugas->jenis_kelamin }}</td>
                                        <td>{{ $petugas->nik }}</td>
                                        <td>{{ $petugas->nip }}</td>
                                        <td>{{ $petugas->tempat_lahir }}</td>
                                        <td>{{ $petugas->tanggal_lahir }}</td>
                                        <td>{{ $petugas->jabatan }}</td>
                                        <td>{{ $petugas->alamat }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <!-- Button trigger modal for Edit Petugas -->
                                                <button type="button" class="btn btn-warning mx-2" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPetugas{{ $petugas->id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('petugas.destroy', $petugas->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger" onclick="deletePetugas({{ $petugas->id }})">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal for Edit Petugas -->
                                    <div class="modal fade" id="modalEditPetugas{{ $petugas->id }}" tabindex="-1"
                                        aria-labelledby="modalEditPetugasLabel{{ $petugas->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditPetugasLabel{{ $petugas->id }}">
                                                        Edit Petugas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('petugas.update', $petugas->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="nama" class="form-label">Nama</label>
                                                            <input type="text" class="form-control" id="nama"
                                                                name="nama" value="{{ $petugas->user->nama }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="username"
                                                                name="username" value="{{ $petugas->user->username }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenis_kelamin" class="form-label">Jenis
                                                                Kelamin</label>
                                                            <select class="form-control" id="jenis_kelamin"
                                                                name="jenis_kelamin" required>
                                                                <option value="Laki-laki"
                                                                    {{ $petugas->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>
                                                                    Laki-laki</option>
                                                                <option value="Perempuan"
                                                                    {{ $petugas->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>
                                                                    Perempuan</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nik" class="form-label">NIK</label>
                                                            <input type="text" class="form-control" id="nik"
                                                                name="nik" value="{{ $petugas->nik }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nip" class="form-label">NIP</label>
                                                            <input type="text" class="form-control" id="nip"
                                                                name="nip" value="{{ $petugas->nip }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tempat_lahir" class="form-label">Tempat
                                                                Lahir</label>
                                                            <input type="text" class="form-control" id="tempat_lahir"
                                                                name="tempat_lahir" value="{{ $petugas->tempat_lahir }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_lahir" class="form-label">Tanggal
                                                                Lahir</label>
                                                            <input type="date" class="form-control" id="tanggal_lahir"
                                                                name="tanggal_lahir" value="{{ $petugas->tanggal_lahir }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jabatan" class="form-label">Jabatan</label>
                                                            <input type="text" class="form-control" id="jabatan"
                                                                name="jabatan" value="{{ $petugas->jabatan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="alamat" class="form-label">Alamat</label>
                                                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $petugas->alamat }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add Petugas -->
    <div class="modal fade" id="modalAddPetugas" tabindex="-1" aria-labelledby="modalAddPetugasLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddPetugasLabel">Tambah Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('petugas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function deletePetugas(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
