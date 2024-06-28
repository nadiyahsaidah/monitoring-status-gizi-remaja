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
            return view('home', compact('pengukuranRemaja'));
        }

        $pengukurans = Pengukuran::selectRaw('DATE(tanggal_pengukuran) as date, status_gizi, COUNT(*) as count')
        ->groupBy('date', 'status_gizi')
        ->orderBy('date', 'desc')
        ->get()
        ->groupBy('date');

    $dates = [];
    $gemukCounts = [];
    $kurusCounts = [];
    $normalCounts = [];
    $lainnyaCounts = [];

    foreach ($pengukurans as $date => $statusGroup) {
        $dates[] = $date;

        $gemukCounts[] = $statusGroup->where('status_gizi', 'Obesitas')->first()->count ?? 0;
        $kurusCounts[] = $statusGroup->where('status_gizi', 'Kurang')->first()->count ?? 0;
        $normalCounts[] = $statusGroup->where('status_gizi', 'Normal')->first()->count ?? 0;
        $lainnyaCounts[] = $statusGroup->whereNotIn('status_gizi', ['Obesitas', 'Kurang', 'Normal'])->sum('count');
    }

    $chart = (new LarapexChart)->barChart()
        ->setTitle('Status Gizi Per Tanggal')
        ->setXAxis($dates)
        ->addData('Gemuk', $gemukCounts)
        ->addData('Kurus', $kurusCounts)
        ->addData('Normal', $normalCounts)
        ->addData('Lainnya', $lainnyaCounts)
        ->setColors(['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']);

    return view('home', compact('chart', 'adminCount', 'remajaCount', 'petugasCount'));
    }
    
}
