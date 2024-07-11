@extends('layouts.dashboard')

@section('judul', 'Pengukuran')

@section('content')
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
               <div class="card-header">
                <h3 class="fw-bold mb-3">Riwayat Pengukuran </h3>
               </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
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
@endsection