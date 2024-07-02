<?php

namespace App\Http\Controllers;

use App\Models\Remaja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RemajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $remajas = Remaja::with('user')->get();
        return view('admin.remaja.index', compact('remajas'));
    }
    public function profile()
    {
        $user = Auth::user();
        $remaja = Remaja::where('user_id', $user->id)->first(); 
        return view('admin.remaja.profile', compact('remaja'));
    }

    public function profileUpdate(Request $request, $id)
    {
        // Find the remaja by ID
        $remaja = Remaja::findOrFail($id);
        $user = $remaja->user;
    
        // Validate the request data
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
    
        // Update the user information
        $user->nama = $request->nama;
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
    
        // Update the remaja information
        $remaja->jenis_kelamin = $request->jenis_kelamin;
        $remaja->nik = $request->nik;
        $remaja->tempat_lahir = $request->tempat_lahir;
        $remaja->tanggal_lahir = $request->tanggal_lahir;
        $remaja->no_hp = $request->no_hp;
        $remaja->alamat = $request->alamat;
        $remaja->save();
    
        // Redirect back with a success message
        return redirect()->route('profile')->with('success', 'Data berhasil diperbarui.');
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
            'nik' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'username' => 'required|string',
            'username' => 'required|string|unique:users',
        ]);

        try {
            DB::beginTransaction();

            $user = null;
            $user = new User();
            $user->username = $request->nama;
            $user->nama = $request->nama;
            $user->password = bcrypt('password');
            $user->role = 'remaja';
            $user->save();

            $remaja = new Remaja();
            $remaja->user_id = $user ? $user->id : null;
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
            'username' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',

        ]);

        try {
            DB::beginTransaction();

            $remaja = Remaja::findOrFail($id);
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
            $user->role = 'remaja';
            $user->save();

            DB::commit();

            return redirect()->route('remaja.index')->with('success', 'Data remaja berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data remaja: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
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
}
