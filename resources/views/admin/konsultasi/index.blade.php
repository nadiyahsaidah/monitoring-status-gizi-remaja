@extends('layouts.dashboard')

@section('judul', 'Data Konsultasi')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                   @if(Auth::user()->role == 'remaja')
                   <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahModal">
                        Tambah Konsultasi
                    </button>
                    @endif
                    <div class="table-responsive">
                        <table class="table-striped table" id="datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        No
                                    </th>
                                    <th>Nama Remaja</th>
                                    <th>Perihal</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Balasan</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($konsultasis as $konsultasi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $konsultasi->remaja->user->nama }}</td>
                                        <td>{{ $konsultasi->perihal }}</td>
                                        <td>{{ $konsultasi->deskripsi }}</td>
                                        <td>
                                            @if ($konsultasi->status == 'Belum dibalas')
                                                <span class="badge bg-warning text-white">{{ $konsultasi->status }}</span>
                                            @else
                                                <span class="badge bg-success text-white">{{ $konsultasi->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $konsultasi->balasan ? $konsultasi->balasan : '-' }} </td>
                                        <td>{{ $konsultasi->created_at }}</td>
                                        
                                        <td>
                                            <div class="d-flex gap-3">
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#showModal{{ $konsultasi->id }}">
                                                    Show
                                            </button>
                                        @if (Auth::user()->role != 'remaja')
                                                @if ($konsultasi->balasan == null)
                                                    <button type="button" class="btn btn-primary " data-toggle="modal"
                                                        data-target="#editModal{{ $konsultasi->id }}">
                                                        Balas
                                                    </button>
                                                @endif
                                                
                                                <form action="{{ route('konsultasi.destroy', $konsultasi->id) }}"
                                                    method="POST" id="deleteForm{{ $konsultasi->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation({{ $konsultasi->id }})">Hapus</button>
                                                </form>
                                            </div>
                                            <div class="modal fade" id="editModal{{ $konsultasi->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $konsultasi->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('konsultasi.update', $konsultasi->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel{{ $konsultasi->id }}">Edit Konsultasi</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="remaja_id">Nama Remaja</label>
                                                                    <select class="form-control" id="remaja_id" name="remaja_id" required readonly>
                                                                        @foreach ($remajas as $remaja)
                                                                            <option value="{{ $remaja->id }}" @if ($remaja->id == $konsultasi->remaja_id) selected @endif>
                                                                                {{ $remaja->user->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="perihal">Perihal</label>
                                                                    <input type="text" class="form-control" id="perihal" name="perihal" value="{{ $konsultasi->perihal }}" required readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="deskripsi">Deskripsi</label>
                                                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" readonly required>{{ $konsultasi->deskripsi }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="balasan">Balasan</label>
                                                                    <textarea class="form-control" id="balasan" name="balasan" rows="3">{{ $konsultasi->balasan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="showModal{{ $konsultasi->id }}" tabindex="-1" role="dialog" aria-labelledby="showModalLabel{{ $konsultasi->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="showModalLabel{{ $konsultasi->id }}">Detail Konsultasi</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Nama Remaja:</strong> {{ $konsultasi->remaja->user->nama }}</p>
                                                            <p><strong>Perihal:</strong> {{ $konsultasi->perihal }}</p>
                                                            <p><strong>Deskripsi:</strong></p>
                                                            <p>{{ $konsultasi->deskripsi }}</p>
                                                            <p><strong>Status:</strong> 
                                                                @if ($konsultasi->status == 'Belum dibalas')
                                                                    <span class="badge bg-warning text-white">{{ $konsultasi->status }}</span>
                                                                @else
                                                                    <span class="badge bg-success text-white">{{ $konsultasi->status }}</span>
                                                                @endif
                                                            </p>
                                                            @if ($konsultasi->balasan)
                                                                <p><strong>Balasan:</strong></p>
                                                                <p>{{ $konsultasi->balasan }}</p>
                                                            @endif
                                                            <p><strong>Tanggal:</strong> {{ $konsultasi->created_at }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                        </td>

                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Konsultasi -->
    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('konsultasi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Konsultasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="remaja_id">Nama Remaja</label>
                        <input type="text" class="form-control" id="remaja_nama" value="{{ Auth::user()->nama }}" readonly>
                        <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ Auth::user()->id }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="perihal">Perihal</label>
                        <input type="text" class="form-control" id="perihal" name="perihal" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
                    // Submit form untuk menghapus
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>
@endsection
