@extends('layouts.dashboard')

@section('judul', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6>Selamat Datang {{ Auth::user()->nama }}</h6>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Admin</h4>
                    </div>
                    <div class="card-body">
                        {{ $adminCount }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Petugas</h4>
                    </div>
                    <div class="card-body">
                        {{ $petugasCount }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="far fa-file"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Remaja</h4>
                    </div>
                    <div class="card-body">
                        {{ $remajaCount }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ LarapexChart::cdn() }}"></script>
    {{ $chart->script() }}
@else
    <div class="row">
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    {!! $bbChart->container() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    {!! $tbChart->container() !!}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ LarapexChart::cdn() }}"></script>
    {{ $bbChart->script() }}
    {{ $tbChart->script() }}
@endif
@endsection
