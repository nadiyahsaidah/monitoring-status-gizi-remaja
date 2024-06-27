@extends('layouts.dashboard')

@section('judul', 'Edit Pengukuran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('pengukuran.index') }}" class="btn btn-primary mb-3">Kembali</a>
                <form action="{{ route('pengukuran.update', $pengukuran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remaja_id">Nama Remaja</label>
                                <select class="form-control" id="remaja_id" name="remaja_id" required>
                                    @foreach($remajas as $remaja)
                                    <option value="{{ $remaja->id }}" {{ $remaja->id == $pengukuran->remaja_id ? 'selected' : '' }}>
                                        {{ $remaja->user->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pengukuran">Tanggal Pengukuran</label>
                                <input type="date" class="form-control" id="tanggal_pengukuran" name="tanggal_pengukuran" value="{{ $pengukuran->tanggal_pengukuran }}" required>
                            </div>
                            <div class="form-group">
                                <label for="bb">BB</label>
                                <input type="number" class="form-control" id="bb" name="bb" value="{{ $pengukuran->bb }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tb">TB</label>
                                <input type="number" class="form-control" id="tb" name="tb" value="{{ $pengukuran->tb }}" required>
                            </div>
                            <div class="form-group">
                                <label for="lila">LILA</label>
                                <input type="number" step="0.1" class="form-control" id="lila" name="lila" value="{{ $pengukuran->lila }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tensi">Tensi</label>
                                <input type="text" class="form-control" id="tensi" name="tensi" value="{{ $pengukuran->tensi }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
