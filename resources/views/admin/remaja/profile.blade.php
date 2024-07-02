@extends('layouts.dashboard')
@section('judul', 'Data Pengukuran')
@section('content')
   <div class="row">
    <div class="col-md-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <form action="{{ route('profile.update', $remaja->id) }}" method="POST">
                    @csrf
                    @method('PUT')
        
                    <div class="row d-flex">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    value="{{ $remaja->user->nama }}">
                            </div>
        
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    value="{{ $remaja->user->username }}">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
        
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                    <option value="Laki-laki" {{ $remaja->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $remaja->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
        
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text" name="nik" id="nik" class="form-control"
                                    value="{{ $remaja->nik }}">
                            </div>
                        </div>
                        <div class="col-md-6">
        
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                    value="{{ $remaja->tempat_lahir }}">
                            </div>
        
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                                    value="{{ $remaja->tanggal_lahir }}">
                            </div>
        
                            <div class="form-group">
                                <label for="no_hp">Nomor HP</label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control"
                                    value="{{ $remaja->no_hp }}">
                            </div>
        
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control">{{ $remaja->alamat }}</textarea>
                            </div>
                        </div>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
   </div>
@endsection
