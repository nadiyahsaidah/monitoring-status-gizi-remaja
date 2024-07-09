@extends('layouts.dashboard')

@section('judul', $artikel->judul)

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h1>{{ $artikel->judul }}</h1>
                <p class="card-text text-muted">Diposting pada {{ $artikel->created_at->format('d M Y') }}</p>
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" class="img-fluid rounded-lg" style="max-width: 100%; max-height: 500px;" alt="{{ $artikel->judul }}">
                    </div>
                    <div class="col-lg-12">
                        <p class="card-text" style="white-space: pre-wrap;">{{ $artikel->deskripsi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
