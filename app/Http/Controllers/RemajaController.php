<?php

namespace App\Http\Controllers;

use App\Models\Remaja;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class RemajaController extends Controller
{
    public function index()
    {
        $remajas = Remaja::with('user', 'pengukurans')->get();
        $charts = [];
    
        foreach ($remajas as $remaja) {
            $pengukuranRemaja = $remaja->pengukurans;
            $dates = $pengukuranRemaja->pluck('tanggal_pengukuran')->toArray();
            $bb = $pengukuranRemaja->pluck('bb')->toArray();
            $tb = $pengukuranRemaja->pluck('tb')->toArray();
    
            if (count($bb) > 0 && count($tb) > 0) {
                $formattedDates = collect($dates)->map(function ($date) {
                    return Carbon::parse($date)->formatLocalized('%d %B %Y');
                })->toArray();
    
                $bbChart = (new LarapexChart)->areaChart()
                    ->setTitle('Perkembangan Berat Badan')
                    ->setXAxis($formattedDates)
                    ->addData('Berat Badan', $bb)
                    ->setColors(['#ff6384']);
    
                $tbChart = (new LarapexChart)->areaChart()
                    ->setTitle('Perkembangan Tinggi Badan')
                    ->setXAxis($formattedDates)
                    ->addData('Tinggi Badan', $tb)
                    ->setColors(['#0000ff']);
            } else {
                $bbChart = null;
                $tbChart = null;
            }
    
            $charts[$remaja->id] = [
                'bbChart' => $bbChart,
                'tbChart' => $tbChart,
            ];
        }
    
        return view('admin.remaja.index', compact('remajas', 'charts'));
    }

    public function profile()
    {
        $user = Auth::user();
        $remaja = Remaja::where('user_id', $user->id)->first(); 
        return view('admin.remaja.profile', compact('remaja'));
    }

    public function profileUpdate(Request $request, $id)
    {
        $remaja = Remaja::findOrFail($id);
        $user = $remaja->user;
    
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);
    
        $user->nama = $request->nama;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
    
        $remaja->jenis_kelamin = $request->jenis_kelamin;
        $remaja->nik = $request->nik;
        $remaja->tempat_lahir = $request->tempat_lahir;
        $remaja->tanggal_lahir = $request->tanggal_lahir;
        $remaja->no_hp = $request->no_hp;
        $remaja->alamat = $request->alamat;
        $remaja->save();
    
        return redirect()->route('profile')->with('success', 'Data berhasil diperbarui.');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'username' => 'required|string|unique:users',
        ]);

        try {
            DB::beginTransaction();

            $user = new User();
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->password = bcrypt('password');
            $user->role = 'remaja';
            $user->save();

            $remaja = new Remaja();
            $remaja->user_id = $user->id;
            $remaja->jenis_kelamin = $request->jenis_kelamin;
            $remaja->nik = $request->nik;
            $remaja->tempat_lahir = $request->tempat_lahir;
            $remaja->tanggal_lahir = $request->tanggal_lahir;
            $remaja->no_hp = $request->no_hp;
            $remaja->alamat = $request->alamat;
            $remaja->save();

            DB::commit();

            return redirect()->route('remaja.index')->with('success', 'Data remaja berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->withErrors(['error' => 'Gagal menambahkan data remaja: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
{
    $remaja = Remaja::findOrFail($id);
    $request->validate([
        'nama' => 'required|string',
        'username' => [
            'required',
            'string',
            Rule::unique('users')->ignore($remaja->user_id),
        ],
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'nik' => 'required|string',
        'tempat_lahir' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'no_hp' => 'required|string',
        'alamat' => 'required|string',
    ]);

    $remaja->jenis_kelamin = $request->jenis_kelamin;
    $remaja->nik = $request->nik;
    $remaja->tempat_lahir = $request->tempat_lahir;
    $remaja->tanggal_lahir = $request->tanggal_lahir;
    $remaja->no_hp = $request->no_hp;
    $remaja->alamat = $request->alamat;
    $remaja->save();

    $user = User::findOrFail($remaja->user_id);
    $user->username = $request->username;
    $user->nama = $request->nama;
    $user->save();

    return redirect()->route('remaja.index')->with('success', 'Data remaja berhasil diperbarui');
}

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $remaja = Remaja::findOrFail($id);
            $remaja->delete();

            $user = User::findOrFail($remaja->user_id);
            $user->delete();

            DB::commit();

            return redirect()->route('remaja.index')->with('success', 'Data remaja berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Gagal menghapus data remaja: ' . $e->getMessage()]);
        }
    }

    public function fetchData($id)
    {
        $remaja = Remaja::findOrFail($id);

        return response()->json([
            'jenis_kelamin' => $remaja->jenis_kelamin,
            'tanggal_lahir' => $remaja->tanggal_lahir,
            'usia' => $remaja->hitungUsia(),
        ]);
    }

    public function show($id)
    {
        $remaja = Remaja::with('user', 'pengukurans')->findOrFail($id);
        $pengukuranRemaja = $remaja->pengukurans;
        $dates = $pengukuranRemaja->pluck('tanggal_pengukuran')->map(function ($date) {
            return Carbon::parse($date)->isoFormat('DD MMMM YYYY');
        })->toArray();
        $bb = $pengukuranRemaja->pluck('bb')->toArray();
        $tb = $pengukuranRemaja->pluck('tb')->toArray();

        $bbChart = null;
        $tbChart = null;

        if (count($bb) > 0 && count($tb) > 0) {
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
        }

        return view('admin.remaja.show', compact('remaja', 'pengukuranRemaja', 'bbChart', 'tbChart'));
    }
}
