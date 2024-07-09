@extends('layouts.dashboard')

@section('judul', 'Data Konsultasi')

@section('content')

<style>
    .chat-container {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        background: #f0f0f0;
        border-radius: 10px;
    }
    .chat-message {
        margin-bottom: 10px;
    }
    .chat-message.left {
        text-align: left;
    }
    .chat-message.right {
        text-align: right;
    }
    .message-content {
        display: inline-block;
        padding: 10px;
        border-radius: 15px;
        max-width: 60%;
    }
    .chat-message.right .message-content {
        border-bottom-right-radius: 0;
    }
    .chat-message.left .message-content {
        border-bottom-left-radius: 0;
    }
</style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (Auth::user()->role == 'remaja')
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahModal">
                            Tambah Konsultasi
                        </button>
                    @endif
                    <div class="table-responsive">
                        <table class="table-striped table" id="datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Remaja</th>
                                    <th>Perihal</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($konsultasis as $konsultasi)
                                    @php
                                        $notification = Auth::user()->unreadNotifications->firstWhere(
                                            'data.konsultasi_id',
                                            $konsultasi->id,
                                        );
                                    @endphp  
                                    <tr class="{{ $notification ? 'table-warning' : '' }}">
                                        <td>{{ $loop->iteration }} <span
                                                class="badge bg-danger fw-bold text-white rounded">{{ $notification ? 'Baru' : '' }}</span>
                                        </td>
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
                                        <td>{{ $konsultasi->created_at }}</td>
                                        <td>
                                            <div class="d-flex gap-3">
                                              @if (Auth::user()->role == 'remaja')
                                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#showModal{{ $konsultasi->id }}"
                                                onclick="markAsRead('{{ isset($notification) ? $notification->id : '' }}')">Show</button>
                                              @else
                                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#showModal{{ $konsultasi->id }}">Show</button>
                                              @endif
                                        
                                                <form action="{{ route('konsultasi.destroy', $konsultasi->id) }}"
                                                    method="POST" id="deleteForm{{ $konsultasi->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation({{ $konsultasi->id }})">Hapus</button>
                                                </form>
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
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <p class="m-0"><strong>Perihal:</strong> {{ $konsultasi->perihal }}</p>
                                                                        <p class="m-0"><strong>Deskripsi:</strong> {{ $konsultasi->deskripsi }}</p>
                                                                        <div class="chat-container">
                                                                            @foreach ($konsultasi->messages as $message)
                                                                                <div class="chat-message {{ $message->user->role == 'remaja' ? 'right' : 'left' }}">
                                                                                    <div class="message-content {{ $message->user->role == 'Remaja' ? 'bg-info text-white' : 'bg-light text-dark' }}">
                                                                                        <strong>{{ $message->user->role }}:</strong>
                                                                                        {{ $message->message }}
                                                                                    </div>
                                                                                    <small>{{ $message->created_at }}</small>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <p><strong>Tanggal:</strong> {{ $konsultasi->created_at }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-12">
                                                                        <form action="{{ route('konsultasi-messages.reply', $konsultasi->id) }}" method="POST">
                                                                            @csrf
                                                                            <div class="form-group">
                                                                                <label for="message">Balasan</label>
                                                                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                                                            </div>
                                                                            <div class="form-group text-end mt-2">
                                                                                <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Reply -->
                                            <div class="modal fade" id="replyModal{{ $konsultasi->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="replyModalLabel{{ $konsultasi->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('konsultasi-messages.reply', $konsultasi->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="replyModalLabel{{ $konsultasi->id }}">Balas
                                                                    Konsultasi
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="message">Balasan</label>
                                                                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary">Kirim
                                                                    Balasan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
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
                            <input type="text" class="form-control" id="remaja_nama"
                                value="{{ Auth::user()->nama }}" readonly>
                            <input type="hidden" class="form-control" id="user_id" name="user_id"
                                value="{{ Auth::user()->id }}" readonly>
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
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        function markAsRead(id) {
            $.ajax({
                url: '{{ route('notifications.markAsRead', '') }}' + '/' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
