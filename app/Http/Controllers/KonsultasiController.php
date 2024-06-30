<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Remaja;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\KonsultasiAdded;
use App\Notifications\KonsultasiReply;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       if(Auth::user()->role == 'remaja'){
        $user = Auth::user();
        $remajaId = $user->remaja->first()->id;
        $remajas = Remaja::where('id', $remajaId)->with('user')->get();
        $konsultasis = Konsultasi::where('remaja_id', $remajaId)->get();
        return view('admin.konsultasi.index', compact('konsultasis', 'remajas'));
       }

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
        try {
            $userId = $request->input('user_id');
            $remaja = Remaja::where('user_id', $userId)->first();

            if (!$remaja) {
                return redirect()->back()->with('error', 'Remaja tidak ditemukan.');
            }

            $validatedData = $request->validate([
                'user_id' => 'required|exists:remaja,user_id',
                'perihal' => 'required|string|max:255',
                'deskripsi' => 'required|string',
            ]);

            $validatedData['remaja_id'] = $remaja->id;
            unset($validatedData['user_id']);

            $konsultasi = Konsultasi::create($validatedData);

            $adminsAndPetugas = User::whereIn('role', ['admin', 'petugas'])->get();

            foreach ($adminsAndPetugas as $user) {
                $user->notify(new KonsultasiAdded($konsultasi));
            }

            return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan konsultasi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $remajas = Remaja::all();

        return view('konsultasi.edit', compact('konsultasi', 'remajas'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'balasan' => 'required|string',
            ]);

            $konsultasi = Konsultasi::findOrFail($id);
            $konsultasi->balasan = $validatedData['balasan'];
            $konsultasi->status = 'Sudah dibalas';
            $konsultasi->save();

            // Kirim notifikasi ke remaja
            $remaja = $konsultasi->remaja;
            if ($remaja) {
                $remaja->user->notify(new KonsultasiReply($konsultasi));
            }

            $user = auth()->user();
            $notification = $user->unreadNotifications->firstWhere('data.konsultasi_id', $konsultasi->id);
            if ($notification) {
                $notification->markAsRead();
            }

            return redirect()->route('konsultasi.index')->with('success', 'Balasan konsultasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan balasan: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->delete();

        return redirect()->route('konsultasi.index')->with('success', 'Konsultasi berhasil dihapus.');
    }
}
