@extends('layouts.dashboard')

@section('judul', 'Tambah Pengukuran')

@section('content')
<div class="row">
    <div class="col-12">
        <a href="{{ route('pengukuran.index') }}" class="btn btn-primary mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pengukuran.hitung') }}" method="POST" id="pengukuranForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remaja_id">Nama Remaja</label>
                                <select class="form-control" id="remaja_id" name="remaja_id" required>
                                    <option value="">Pilih Nama</option>
                                    @foreach($remajas as $remaja)
                                        <option value="{{ $remaja->id }}">{{ $remaja->user->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin" required disabled>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required disabled>
                            </div>
                            <div class="form-group">
                                <label for="usia">Usia</label>
                                <input type="number" class="form-control" id="usia" name="usia" required disabled>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pengukuran">Tanggal Pengukuran</label>
                                <input type="date" class="form-control" id="tanggal_pengukuran" name="tanggal_pengukuran" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bb">BB</label>
                                <input type="number" class="form-control" id="bb" name="bb" required>
                            </div>
                            <div class="form-group">
                                <label for="tb">TB</label>
                                <input type="number" class="form-control" id="tb" name="tb" required>
                            </div>
                            <div class="form-group">
                                <label for="lila">LILA</label>
                                <input type="number" step="0.1" class="form-control" id="lila" name="lila" required>
                            </div>
                            <div class="form-group">
                                <label for="tensi">Tensi</label>
                                <input type="text" class="form-control" id="tensi" name="tensi" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#remaja_id').change(function() {
            var remajaId = $(this).val();
            if (remajaId) {
                $.ajax({
                    url: '/fetch-remaja-data/' + remajaId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#jenis_kelamin').val(data.jenis_kelamin).prop('disabled', true);
                        $('#tanggal_lahir').val(data.tanggal_lahir).prop('disabled', true);
                        $('#usia').val(data.usia).prop('disabled', true);
                    }
                });
            } else {
                $('#jenis_kelamin, #tanggal_lahir, #usia').val('').prop('disabled', true);
            }
        });
    });
</script>
@endsection