@extends('layouts.dashboard')

@section('judul', 'Data Artikel')

@section('content')
    @if (Auth::user()->role != 'remaja')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahModal">
                            Tambah Artikel
                        </button>
                        <div class="table-responsive">
                            <table class="table-striped table" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($artikels as $artikel)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $artikel->judul }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($artikel->deskripsi, 150, $end='...') }}</td>
                                            <td><img src="{{ asset('storage/' . $artikel->gambar) }}" width="100"
                                                    alt=""></td>
                                            <td>
                                                <div class="d-flex">
                                                <a href="{{ route('artikel.show', $artikel->id) }}" class="btn btn-primary">Detail</a>
                                                    <button type="button" class="btn btn-warning mx-2 editBtn"
                                                        data-id="{{ $artikel->id }}" data-judul="{{ $artikel->judul }}"
                                                        data-deskripsi="{{ $artikel->deskripsi }}"
                                                        data-gambar="{{ $artikel->gambar }}">Edit</button>
                                                            <form id="deleteForm{{ $artikel->id }}" action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-danger deleteBtn" data-id="{{ $artikel->id }}">Hapus</button>
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

        <!-- Modal Tambah Artikel -->
        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Artikel</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" class="form-control-file" id="gambar" name="gambar">
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

        <!-- Modal Edit Artikel -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('artikel.store') }}"  method="POST" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Artikel</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editId">
                            <div class="form-group">
                                <label for="edit_judul">Judul</label>
                                <input type="text" class="form-control" id="edit_judul" name="judul" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_deskripsi">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_gambar">Gambar</label>
                                <input type="file" class="form-control-file" id="edit_gambar" name="gambar">
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
    @endif

    @if (Auth::user()->role == 'remaja')
        <div class="row">
            @foreach ($artikels as $artikel)
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <img src="{{ asset('storage/' . $artikel->gambar) }}" class="card-img-top" alt="{{ $artikel->judul }}" style="width: 150px; border-radius: 10px;">
                            <div class="mx-4">
                                <h5 class="card-title">{{ $artikel->judul }}</h5>
                                <a href="{{ route('artikel.show', $artikel->id) }}" class="btn btn-primary">Tampilkan Selengkapnya</a>
                                <div class="text-muted">
                                    <small>{{ $artikel->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
@endsection

@section('scripts')
    <script>
       $(document).ready(function() {
    // Edit Artikel Modal
    $('.editBtn').click(function() {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        var deskripsi = $(this).data('deskripsi');
        var gambar = $(this).data('gambar');

        $('#editId').val(id);
        $('#edit_judul').val(judul);
        $('#edit_deskripsi').val(deskripsi);
        // Optional: handle gambar display here if needed

        $('#editModal').modal('show');
    });

    // Delete Artikel with SweetAlert
    $('.deleteBtn').click(function() {
        var id = $(this).data('id');
        var url = "{{ route('artikel.destroy', ':id') }}";
        url = url.replace(':id', id);

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Artikel akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                "Deleted!",
                                "Artikel berhasil dihapus.",
                                "success"
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                "Error!",
                                "Artikel gagal dihapus.",
                                "error"
                            );
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        Swal.fire(
                            "Error!",
                            "Artikel gagal dihapus.",
                            "error"
                        );
                    }
                });
            }
        });
    });
});


    </script>
@endsection
