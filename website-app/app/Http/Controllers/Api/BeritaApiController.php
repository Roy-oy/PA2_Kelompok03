<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;

class BeritaApiController extends Controller
{
    public function index()
    {
        $berita = Berita::with('kategoriBerita')
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'status' => 'success',
            'data' => $berita
        ]);
    }
    
    public function show($id)
    {
        $berita = Berita::with('kategoriBerita')->findOrFail($id);
        
        // Increment visitor count
        $berita->incrementVisitor();
        
        return response()->json([
            'status' => 'success',
            'data' => $berita
        ]);
    }
    
    public function getByKategori($kategoriId)
    {
        $berita = Berita::with('kategoriBerita')
            ->where('kategori_berita_id', $kategoriId)
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'status' => 'success',
            'data' => $berita
        ]);
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $berita = Berita::with('kategoriBerita')
            ->where('judul', 'like', "%{$query}%")
            ->orWhere('isi_berita', 'like', "%{$query}%")
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'status' => 'success',
            'data' => $berita
        ]);
    }
    
    public function getKategori()
    {
        $kategori = KategoriBerita::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $kategori
        ]);
    }
}