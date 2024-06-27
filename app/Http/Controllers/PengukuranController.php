<?php

namespace App\Http\Controllers;

use App\Models\Remaja;
use Illuminate\Http\Request;
use App\Models\Pengukuran;
use Carbon\Carbon;

class PengukuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengukurans = Pengukuran::with('remaja')->get();
        return view('admin.pengukuran.index', compact('pengukurans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $remajas = Remaja::with('user')->get();
        return view('admin.pengukuran.create', compact('remajas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function hitungStatusGizi(Request $request)
    {
        $validatedData = $request->validate([
            'remaja_id' => 'required|exists:remaja,id',
            'tanggal_pengukuran' => 'required|date',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'lila' => 'required|numeric',
            'tensi' => 'required|string',
        ]);

        // Cari remaja berdasarkan ID yang diberikan
        $remaja = Remaja::findOrFail($validatedData['remaja_id']);
        

        // Hitung usia remaja berdasarkan tanggal lahir dan tanggal pengukuran
        $tanggal_lahir = new Carbon($remaja->tanggal_lahir);
        $usia = $tanggal_lahir->diffInYears($validatedData['tanggal_pengukuran']);

        // Hitung IMT (Indeks Massa Tubuh)
        $bb = $validatedData['bb']; // Berat badan dalam kg
        $tb = $validatedData['tb'] / 100; // Tinggi badan dalam meter
        $imt = $bb / ($tb * $tb); 

        // Tentukan Status Gizi berdasarkan nilai IMT
        if ($imt < 18.5) {
            $status_gizi = 'Kurang';
        } elseif ($imt >= 18.5 && $imt < 25) {
            $status_gizi = 'Normal';
        } elseif ($imt >= 25 && $imt < 30) {
            $status_gizi = 'Berlebih';
        } else {
            $status_gizi = 'Obesitas';
        }

        // Simpan data pengukuran ke database
        $pengukuran = new Pengukuran();
        $pengukuran->remaja_id = $validatedData['remaja_id'];
        $pengukuran->tanggal_pengukuran = $validatedData['tanggal_pengukuran'];
        $pengukuran->bb = $validatedData['bb'];
        $pengukuran->tb = $validatedData['tb'];
        $pengukuran->lila = $validatedData['lila'];
        $pengukuran->tensi = $validatedData['tensi'];
        $pengukuran->status_gizi = $status_gizi;
        $pengukuran->save();

        // Redirect atau kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('pengukuran.index')->with('success', 'Pengukuran berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pengukuran = Pengukuran::findOrFail($id);
        $remajas = Remaja::all();

        return view('admin.pengukuran.edit', compact('pengukuran', 'remajas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'remaja_id' => 'required|exists:remaja,id',
            'tanggal_pengukuran' => 'required|date',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'lila' => 'required|numeric',
            'tensi' => 'required|string',
        ]);
    
        $pengukuran = Pengukuran::findOrFail($id);
        $pengukuran->remaja_id = $validatedData['remaja_id'];
        $pengukuran->tanggal_pengukuran = $validatedData['tanggal_pengukuran'];
        $pengukuran->bb = $validatedData['bb'];
        $pengukuran->tb = $validatedData['tb'];
        $pengukuran->lila = $validatedData['lila'];
        $pengukuran->tensi = $validatedData['tensi'];
        $pengukuran->save();
    
        $statusGizi = $this->standarImt($pengukuran);
    
        // Simpan status gizi ke dalam pengukuran
        $pengukuran->status_gizi = $statusGizi;
        $pengukuran->save();
    
        return redirect()->route('pengukuran.index')->with('success', 'Pengukuran berhasil diperbarui.');
    }

    private function standarImt(Pengukuran $pengukuran)
{
    
    $imt = $pengukuran->bb / (($pengukuran->tb / 100) ** 2);
    if ($imt < 18.5) {
        return 'Kurus';
    } elseif ($imt < 25) {
        return 'Normal';
    } else {
        return 'Gemuk';
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
