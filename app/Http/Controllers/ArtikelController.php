<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artikels = Artikel::all();
        return view('admin.artikel.index', compact('artikels'));
    }

    public function create()
    {
        // Return view for create form
    }

    public function store(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|max:2048', 
        ]);

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikel_images', 'public');
            $validatedData['gambar'] = $gambarPath;
        }

        Artikel::create($validatedData);

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
{
    // Validate request
    $validatedData = $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'gambar' => 'nullable|image|max:2048', // example max size
    ]);

    // Find artikel
    $artikel = Artikel::findOrFail($id);

    // Handle file upload
    if ($request->hasFile('gambar')) {
        // Delete existing image if needed
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        // Store new image
        $gambarPath = $request->file('gambar')->store('artikel_images', 'public');
        $validatedData['gambar'] = $gambarPath;
    }

    // Update artikel
    $artikel->update($validatedData);

    return redirect()->route('artikel.index')->with('success', 'Artikel berhasil diperbarui.');
}

    public function destroy2($id)
    {
        $artikel = Artikel::findOrFail($id);

        // Delete associated image
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return response()->json(['success' => true]);
    }
    
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
    
        if ($artikel->delete()) {
            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Artikel gagal dihapus.']);
        }
    }
    
    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.show', compact('artikel'));
    }

}
