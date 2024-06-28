@extends('layouts.dashboard')

@section('judul', 'Data Remaja')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Button trigger modal for Add Remaja -->
               @if (auth()->user()->role == 'admin')
               <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAddRemaja">
                Tambah
            </button>
               @endif
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Username</th>
                                <th>Jenis Kelamin</th>
                                <th>NIK</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>No Hp</th>
                                <th>Alamat</th>
                                @if (auth()->user()->role == 'admin')
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($remajas as $remaja)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $remaja->user->username }}</td>
                                <td>{{ $remaja->jenis_kelamin }}</td>
                                <td>{{ $remaja->nik }}</td>
                                <td>{{ $remaja->tempat_lahir }}</td>
                                <td>{{ $remaja->tanggal_lahir }}</td>
                                <td>{{ $remaja->no_hp }}</td>
                                <td>{{ $remaja->alamat }}</td>
                               @if (auth()->user()->role == 'admin')
                               <td>
                                <div class="d-flex">
                                    <!-- Button trigger modal for Edit Remaja -->
                                    <button type="button" class="btn btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#modalEditRemaja{{ $remaja->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('remaja.destroy', $remaja->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    
                                </div>
                            </td>
                               @endif
                            </tr>

                            <!-- Modal for Edit Remaja -->
                            <div class="modal fade" id="modalEditRemaja{{ $remaja->id }}" tabindex="-1" aria-labelledby="modalEditRemajaLabel{{ $remaja->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditRemajaLabel{{ $remaja->id }}">Edit Remaja</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('remaja.update', $remaja->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nama" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $remaja->user->nama }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="{{ $remaja->user->username }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                                        <option value="Laki-laki" {{ $remaja->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                        <option value="Perempuan" {{ $remaja->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nik" class="form-label">NIK</label>
                                                    <input type="text" class="form-control" id="nik" name="nik" value="{{ $remaja->nik }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ $remaja->tempat_lahir }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $remaja->tanggal_lahir }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="no_hp" class="form-label">No Hp</label>
                                                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $remaja->no_hp }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="alamat" class="form-label">Alamat</label>
                                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $remaja->alamat }}</textarea>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add Remaja -->
<div class="modal fade" id="modalAddRemaja" tabindex="-1" aria-labelledby="modalAddRemajaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddRemajaLabel">Tambah Remaja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('remaja.store') }}" method="POST">
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
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Hp</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
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
        $(document).on('click', 'form.delete-form button[type="submit"]', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data remaja ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
