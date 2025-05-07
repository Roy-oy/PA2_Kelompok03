<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;    
use App\Models\Pengumuman;
use Illuminate\Http\Request;    

class PengumumanApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumuman = Pengumuman::latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'isi' => $item->isi_pengumuman,
                'tanggal' => $item->tanggal_upload,
                'file' => $item->file_surat ? url('storage/' . $item->file_surat) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pengumuman',
            'data' => $pengumuman
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Pengumuman',
            'data' => $pengumuman
        ]);
    }
}