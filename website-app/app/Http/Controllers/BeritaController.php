<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::with('kategoriBerita')->latest()->paginate(10);
        return view('dashboard.berita.index', compact('berita'));
    }

    public function create()
    {
        $kategoriBerita = KategoriBerita::all();
        return view('dashboard.berita.create', compact('kategoriBerita'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'isi_berita' => 'required',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_upload' => 'required|date'
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('berita-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['total_visitors'] = 0;
        Berita::create($validated);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambahkan');
    }

    public function show()
    {
        
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        $kategoriBerita = KategoriBerita::all();
        return view('dashboard.berita.edit', compact('berita', 'kategoriBerita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'isi_berita' => 'required',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_upload' => 'required|date'
        ]);

        if ($request->hasFile('photo')) {
            if ($berita->photo) {
                Storage::disk('public')->delete($berita->photo);
            }
            $photoPath = $request->file('photo')->store('berita-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $berita->update($validated);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Delete the photo from storage if it exists
        // and the photo is not the default image   
    {
        if ($berita->photo) {
            Storage::disk('public')->delete($berita->photo);
        }
        
        $berita->delete();

        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus');
    }
}
}