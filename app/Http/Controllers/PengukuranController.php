<?php

namespace App\Http\Controllers;

use App\Models\Remaja;
use App\Exports\PengukuranExport;
use App\Models\Pengukuran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengukuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengukuran::query();
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_pengukuran', [$request->start_date, $request->end_date]);

        }
        if(Auth::user()->role == 'remaja'){
            $user = Auth::user();
            $remajaId = Remaja::where('user_id', $user->id)->first()->id;
            $pengukuranRemaja = Pengukuran::where('remaja_id', $remajaId)->get();
            $pengukurans = $query->get();
            return view('admin.pengukuran.index', compact('pengukurans','pengukuranRemaja'));
        }
        
        $pengukurans = $query->get();


          return view('admin.pengukuran.index', compact('pengukurans'));
        
     
    }

    public function cetakPDF(Request $request)
    {
        $query = Pengukuran::with('remaja');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_pengukuran', [$request->start_date, $request->end_date]);
        }

        $pengukurans = $query->get();
        $start_date = Carbon::parse($request->start_date)->format('Ymd');
        $end_date = Carbon::parse($request->end_date)->format('Ymd');

        // Load view
        $view = view('admin.pengukuran.pdf', compact('pengukurans', 'start_date', 'end_date'))->render();

        // Setup DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($view);

        // Render PDF
        $dompdf->render();

        // Generate filename with ymd format
        $filename = 'laporan_pengukuran_' . Carbon::parse($request->start_date)->format('Ymd') . '_to_' . Carbon::parse($request->end_date)->format('Ymd') . '.pdf';

        // Download PDF
        return $dompdf->stream($filename);
    }


    public function exportExcel(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        return Excel::download(new PengukuranExport($start_date, $end_date), 'pengukuran.xlsx');
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

        if (($remaja->jenis_kelamin == 'Laki-laki' && ($usia < 10 || $usia > 18)) ||
            ($remaja->jenis_kelamin == 'Perempuan' && ($usia < 10 || $usia > 18))
        ) {
            return redirect()->back()->with('error', 'Usia remaja harus antara 10-18 tahun untuk laki-laki dan 10-18 tahun untuk perempuan.');
        }

        // Tentukan standar IMT berdasarkan jenis kelamin dan usia remaja
        $standarImt = [];
        if ($remaja->jenis_kelamin == 'Laki-laki') {
            $standarImt = $remaja->getStandarImtLakiLaki()[$usia];
        } elseif ($remaja->jenis_kelamin == 'Perempuan') {
            $standarImt = $remaja->getStandarImtPerempuan()[$usia];
        } else {
            return redirect()->back()->with('error', 'Jenis kelamin remaja tidak valid.');
        }

        // Hitung IMT (Indeks Massa Tubuh)
        $bb = $validatedData['bb']; // Berat badan dalam kg
        $tb = $validatedData['tb'] / 100; // Tinggi badan dalam meter
        $imt = $bb / ($tb * $tb);

        // Tentukan Status Gizi berdasarkan nilai IMT dan standar
        $status_gizi = '';
        foreach ($standarImt as $key => $value) {
            if ($imt < $value['-3']) {
                $status_gizi = 'Gizi Buruk';
                break;
            } elseif ($imt >= $value['-3'] && $imt < $value['-2']) {
                $status_gizi = 'Gizi Kurang';
                break;
            } elseif ($imt >= $value['-2'] && $imt < $value['1']) {
                $status_gizi = 'Gizi Baik';
                break;
            } elseif ($imt >= $value['1'] && $imt < $value['2']) {
                $status_gizi = 'Gizi Lebih';
                break;
            } else {
                $status_gizi = 'Obesitas';
                break;
            }
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

        // Cari remaja berdasarkan ID yang diberikan
        $remaja = Remaja::findOrFail($validatedData['remaja_id']);

        // Hitung usia remaja berdasarkan tanggal lahir dan tanggal pengukuran
        $tanggal_lahir = new Carbon($remaja->tanggal_lahir);
        $usia = $tanggal_lahir->diffInYears($validatedData['tanggal_pengukuran']);

        if (($remaja->jenis_kelamin == 'Laki-laki' && ($usia < 10 || $usia > 18)) ||
            ($remaja->jenis_kelamin == 'Perempuan' && ($usia < 10 || $usia > 18))
        ) {
            return redirect()->back()->with('error', 'Usia remaja harus antara 13-18 tahun untuk laki-laki dan 10-18 tahun untuk perempuan.');
        }

        // Tentukan standar IMT berdasarkan jenis kelamin dan usia remaja
        $standarImt = [];
        if ($remaja->jenis_kelamin == 'Laki-laki') {
            $standarImt = $remaja->getStandarImtLakiLaki()[$usia];
        } elseif ($remaja->jenis_kelamin == 'Perempuan') {
            $standarImt = $remaja->getStandarImtPerempuan()[$usia];
        } else {
            return redirect()->back()->with('error', 'Jenis kelamin remaja tidak valid.');
        }

        // Hitung IMT (Indeks Massa Tubuh)
        $bb = $validatedData['bb']; // Berat badan dalam kg
        $tb = $validatedData['tb'] / 100; // Tinggi badan dalam meter
        $imt = $bb / ($tb * $tb);

        // Tentukan Status Gizi berdasarkan nilai IMT dan standar
        $status_gizi = '';
        foreach ($standarImt as $key => $value) {
            if ($imt < $value['-3']) {
                $status_gizi = 'Gizi Buruk';
                break;
            } elseif ($imt >= $value['-3'] && $imt < $value['-2']) {
                $status_gizi = 'Gizi Kurang';
                break;
            } elseif ($imt >= $value['-2'] && $imt < $value['1']) {
                $status_gizi = 'Gizi Baik';
                break;
            } elseif ($imt >= $value['1'] && $imt < $value['2']) {
                $status_gizi = 'Gizi Lebih';
                break;
            } else {
                $status_gizi = 'Obesitas';
                break;
            }
        }

        // Simpan data pengukuran ke database
        $pengukuran = Pengukuran::findOrFail($id);
        $pengukuran->remaja_id = $validatedData['remaja_id'];
        $pengukuran->tanggal_pengukuran = $validatedData['tanggal_pengukuran'];
        $pengukuran->bb = $validatedData['bb'];
        $pengukuran->tb = $validatedData['tb'];
        $pengukuran->lila = $validatedData['lila'];
        $pengukuran->tensi = $validatedData['tensi'];
        $pengukuran->status_gizi = $status_gizi;
        $pengukuran->save();

        // Redirect atau kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('pengukuran.index')->with('success', 'Pengukuran berhasil diperbarui.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pengukuran = Pengukuran::findOrFail($id);
            $pengukuran->delete();
            return redirect()->route('pengukuran.index')->with('success', 'Data pengukuran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pengukuran.index')->with('error', 'Gagal menghapus data pengukuran: ' . $e->getMessage());
        }
    }

    
}
