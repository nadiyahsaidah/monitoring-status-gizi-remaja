<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengukuran;
use App\Models\Remaja;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user = Auth::user();
        $adminCount = User::where('role', 'admin')->count();
        $remajaCount = User::where('role', 'remaja')->count();
        $petugasCount = User::where('role', 'petugas')->count();

        if ($user->role == 'remaja') {
            $remajaId = Remaja::where('user_id', $user->id)->first()->id;
            $pengukuranRemaja = Pengukuran::where('remaja_id', $remajaId)->get();
    
            // Ambil data tanggal, bb, dan tb
            $dates = $pengukuranRemaja->pluck('tanggal_pengukuran')->toArray();
            $bb = $pengukuranRemaja->pluck('bb')->toArray();
            $tb = $pengukuranRemaja->pluck('tb')->toArray();
    
            $bbChart = (new LarapexChart)->lineChart()
            ->setTitle('Perkembangan Berat Badan')
            ->setXAxis($dates)
            ->addData('Berat Badan', $bb)
            ->setColors(['#ff6384']);

            $tbChart = (new LarapexChart)->lineChart()
            ->setTitle('Perkembangan Tinggi Badan')
            ->setXAxis($dates)
            ->addData('Berat Badan', $tb)
            ->setColors(['#0000ff']);
    
            return view('home', compact('tbChart','bbChart', 'pengukuranRemaja'));
        }

        $pengukurans = Pengukuran::selectRaw('DATE(tanggal_pengukuran) as date, status_gizi, COUNT(*) as count')
        ->groupBy('date', 'status_gizi')
        ->orderBy('date', 'desc')
        ->get()
        ->groupBy('date');

    $dates = [];
    $giziBurukCounts = [];
    $giziKurangCounts = [];
    $giziBaikCounts = [];
    $giziLebihCounts = [];
    $obesitasCounts = [];

    foreach ($pengukurans as $date => $statusGroup) {
        $dates[] = $date;

        $giziBurukCounts[] = $statusGroup->where('status_gizi', 'Gizi Buruk')->first()->count ?? 0;
        $giziKurangCounts[] = $statusGroup->where('status_gizi', 'Gizi Kurang')->first()->count ?? 0;
        $giziBaikCounts[] = $statusGroup->where('status_gizi', 'Gizi Baik')->first()->count ?? 0;
        $giziLebihCounts[] = $statusGroup->where('status_gizi', 'Gizi Lebih')->first()->count ?? 0;
        $obesitasCounts[] = $statusGroup->where('status_gizi', 'Obesitas')->first()->count ?? 0;
    }

    $chart = (new LarapexChart)->barChart()
        ->setTitle('Status Gizi Per Tanggal')
        ->setXAxis($dates)
        ->addData('Gizi Buruk', $giziBurukCounts)
        ->addData('Gizi Kurang', $giziKurangCounts)
        ->addData('Gizi Baik', $giziBaikCounts)
        ->addData('Gizi Lebih', $giziLebihCounts)
        ->addData('Obesitas', $obesitasCounts)
        ->setColors(['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#ff6384', '#36a2eb']);

    return view('home', compact('chart', 'adminCount', 'remajaCount', 'petugasCount'));
}
}