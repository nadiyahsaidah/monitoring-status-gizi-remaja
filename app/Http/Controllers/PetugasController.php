<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petugas = Petugas::with('user')->get();
        return view('admin.petugas.index', compact('petugas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string|unique:petugas',
            'nip' => 'required|string|unique:petugas',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string',
            'alamat' => 'required|string',
            'username' => 'required|string|unique:users',
        ]);

        try {
            $user = new User();
            $user->username = $request->username;
            $user->nama = $request->nama;
            $user->password = bcrypt('password');            
            $user->role = 'petugas';
            $user->save();

            $petugas = new Petugas();
            $petugas->user_id = $user->id;
            $petugas->jenis_kelamin = $request->jenis_kelamin;
            $petugas->nik = $request->nik;
            $petugas->nip = $request->nip;
            $petugas->tempat_lahir = $request->tempat_lahir;
            $petugas->tanggal_lahir = $request->tanggal_lahir;
            $petugas->jabatan = $request->jabatan;
            $petugas->alamat = $request->alamat;
            $petugas->save();

            return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menambahkan data petugas: ' . $e->getMessage()]);
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string',
            'nip' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string',
            'alamat' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $petugas = Petugas::findOrFail($id);
            $petugas->jenis_kelamin = $request->jenis_kelamin;
            $petugas->nik = $request->nik;
            $petugas->nip = $request->nip;
            $petugas->tempat_lahir = $request->tempat_lahir;
            $petugas->tanggal_lahir = $request->tanggal_lahir;
            $petugas->jabatan = $request->jabatan;
            $petugas->alamat = $request->alamat;
            $petugas->save();

            $user = User::findOrFail($petugas->user_id);
            $user->username = $request->username; 
            $user->nama = $request->nama;
            $user->role = 'petugas';
            $user->save();

            DB::commit();

            return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data petugas: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $petugas = Petugas::findOrFail($id);
            $petugas->delete();

            $user = User::findOrFail($petugas->user_id);
            $user->delete();

            DB::commit();

            return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => 'Gagal menghapus data petugas: ' . $e->getMessage()]);
        }
    }
}
