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
            $dates = $pengukuranRemaja->pluck('tanggal_pengukuran')->map(function ($date) {
                return Carbon::parse($date)->isoFormat('DD MMMM YYYY');
            })->toArray();
            $bb = $pengukuranRemaja->pluck('bb')->toArray();
            $tb = $pengukuranRemaja->pluck('tb')->toArray();

            $bbChart = (new LarapexChart)->areaChart()
                ->setTitle('Perkembangan Berat Badan')
                ->setXAxis($dates)
                ->addData('Berat Badan', $bb)
                ->setColors(['#ff6384']);

            $tbChart = (new LarapexChart)->areaChart()
                ->setTitle('Perkembangan Tinggi Badan')
                ->setXAxis($dates)
                ->addData('Tinggi Badan', $tb)
                ->setColors(['#0000ff']);

            return view('home', compact('tbChart', 'bbChart', 'pengukuranRemaja'));
        }

        // Group by month and year correctly
        $pengukurans = Pengukuran::selectRaw('DATE_FORMAT(tanggal_pengukuran, "%Y-%m") as month_year, DATE_FORMAT(tanggal_pengukuran, "%d %M %Y") as full_date, status_gizi, COUNT(*) as count')
            ->groupBy('month_year', 'full_date', 'status_gizi')
            ->orderBy('month_year', 'desc') // Urutkan berdasarkan tanggal secara menurun
            ->get()
            ->groupBy('month_year'); // Grouping berdasarkan bulan dan tahun

        $labels = [];
        $giziBurukCounts = [];
        $giziKurangCounts = [];
        $giziBaikCounts = [];
        $giziLebihCounts = [];
        $obesitasCounts = [];

        foreach ($pengukurans as $monthYear => $statusGroup) {
            // Ambil tanggal bulan tahun lengkap
            $labels[] = $statusGroup->first()->full_date;

            $giziBurukCounts[] = $statusGroup->where('status_gizi', 'Gizi Buruk')->first()->count ?? 0;
            $giziKurangCounts[] = $statusGroup->where('status_gizi', 'Gizi Kurang')->first()->count ?? 0;
            $giziBaikCounts[] = $statusGroup->where('status_gizi', 'Gizi Baik')->first()->count ?? 0;
            $giziLebihCounts[] = $statusGroup->where('status_gizi', 'Gizi Lebih')->first()->count ?? 0;
            $obesitasCounts[] = $statusGroup->where('status_gizi', 'Obesitas')->first()->count ?? 0;
        }

        // Reverse the arrays to get the correct order (latest month first)
        $labels = array_reverse($labels);
        $giziBurukCounts = array_reverse($giziBurukCounts);
        $giziKurangCounts = array_reverse($giziKurangCounts);
        $giziBaikCounts = array_reverse($giziBaikCounts);
        $giziLebihCounts = array_reverse($giziLebihCounts);
        $obesitasCounts = array_reverse($obesitasCounts);

        $chart = (new LarapexChart)->areaChart()
            ->setTitle('Status Gizi Per Bulan')
            ->setXAxis($labels)
            ->addData('Gizi Buruk', $giziBurukCounts)
            ->addData('Gizi Kurang', $giziKurangCounts)
            ->addData('Gizi Baik', $giziBaikCounts)
            ->addData('Gizi Lebih', $giziLebihCounts)
            ->addData('Obesitas', $obesitasCounts)
            ->setColors(['#ff6384', '#ffce56', '#28a745','#36a2eb', '#cc65fe']);

        // Mengambil data pengukuran untuk remaja
        $pengukurans = Pengukuran::all();

        return view('home', compact('chart', 'adminCount', 'remajaCount', 'petugasCount', 'pengukurans'));
    }
}
