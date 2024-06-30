@extends('layouts.dashboard')

@section('judul', 'Dashboard')

@section('content')
    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
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
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
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
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
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
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    {!! $bbChart->container() !!}
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    {!! $tbChart->container() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h6>Selamat Datang {{ Auth::user()->nama }}</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-6 col-sm-6 col-12">
            <div class="card">
               <div class="card-header">
                <h3 class="fw-bold mb-3">Riwayat Pengukuran</h3>
               </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>1</th>
                                    <th>Tanggal Pegukuran</th>
                                    <th>Status Gizi</th>
                                    <th>BB</th>
                                    <th>TB</th>
                                    <th>LILA</th>
                                    <th>Tensi</th>
                                    <th>Status Gizi</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($pengukuranRemaja as $item)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengukuran)->isoFormat('D MMMM YYYY') }}</td>
                                <td>{{ $item->status_gizi }}</td>
                                <td>{{ $item->bb }}</td>
                                <td>{{ $item->tb }}</td>
                                <td>{{ $item->lila }}</td>
                                <td>{{ $item->tensi }}</td>
                                <td><span class="badge badge-success">{{ $item->status_gizi }}</span></td>
                            </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

    <script src="{{ LarapexChart::cdn() }}"></script>
    {{ $bbChart->script() }}
    {{ $tbChart->script() }}
    @endif
@endsection
