<?php
namespace App\Http\Controllers;

use App\Models\KategoriBerita;
use Illuminate\Http\Request;

class KategoriBeritaController extends Controller
{
    /**
     * Tampilkan daftar kategori berita.
     */
    public function index()
    {
        $categories = KategoriBerita::latest()->paginate(5);
        return view('dashboard.kategori_berita.index', compact('categories'));
    }

    /**
     * Tampilkan form untuk menambahkan kategori berita baru.
     */
    public function create()
    {
        return view('dashboard.kategori_berita.create');
    }

    /**
     * Simpan kategori berita baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
        ]);

        KategoriBerita::create($request->all());

        return redirect()->route('kategori_berita.index')
            ->with('success', 'Kategori berita berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit kategori berita.
     */
    public function edit($id)
    {
        $kategori = KategoriBerita::findOrFail($id);
        return view('dashboard.kategori_berita.edit', compact('kategori'));
    }

    /**
     * Perbarui data kategori berita di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'kategori_berita_id' => 'required|exists:kategori_berita,id',
        ]);

        $kategori = KategoriBerita::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori_berita.index')
            ->with('success', 'Kategori berita berhasil diperbarui.');
    }

    /**
     * Hapus kategori berita dari database.
     */
    public function destroy($id)
    {
        $kategori = KategoriBerita::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori_berita.index')
            ->with('success', 'Kategori berita berhasil dihapus.');
    }
}
