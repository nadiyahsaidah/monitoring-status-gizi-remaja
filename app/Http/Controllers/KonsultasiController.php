<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Remaja;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konsultasis = Konsultasi::with('remaja')->get(); 
        $remajas = Remaja::with('user')->get();
        return view('admin.konsultasi.index', compact('konsultasis', 'remajas'));
    }

    public function create()
    {
        $remajas = Remaja::all();
        return view('konsultasi.create', compact('remajas'));
    }

    public function store(Request $request)
    {
        $userId = $request->input('remaja_id');
        $remajaId = Remaja::where('user_id', $userId)->first()->id;
        $validatedData = $request->validate([
            'remaja_id' => 'required|exists:remaja,id',
            'perihal' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $validatedData['remaja_id'] = $remajaId;

        Konsultasi::create($validatedData);

        return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $remajas = Remaja::all(); 

        return view('konsultasi.edit', compact('konsultasi', 'remajas'));
    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'remaja_id' => 'required|exists:remaja,id',
        'balasan' => 'nullable|string',
    ]);

    $konsultasi = Konsultasi::findOrFail($id);

    $konsultasi->update([
        'remaja_id' => $validatedData['remaja_id'],
        'status' => 'Sudah dibalas',
        'balasan' => $validatedData['balasan'],
    ]);

    return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil diperbarui.');
}


    public function destroy($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->delete();

        return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil dihapus.');
    }
}
