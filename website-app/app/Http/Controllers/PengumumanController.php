<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('tanggal_upload', 'desc')->get();
        return view('dashboard.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('dashboard.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_pengumuman' => 'required',
            'tanggal_upload' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $data = $request->only(['judul', 'isi_pengumuman', 'tanggal_upload']);

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store directly to the public disk
            $path = Storage::disk('public')->putFileAs('surat', $file, $filename);
            
            // Save the correct path for database
            $data['file_surat'] = $path;
        }

        Pengumuman::create($data);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function show()
    {
        //
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('dashboard.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_pengumuman' => 'required',
            'tanggal_upload' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $data = $request->only(['judul', 'isi_pengumuman', 'tanggal_upload']);

        if ($request->hasFile('file_surat')) {
            // Delete old file if exists
            if ($pengumuman->file_surat) {
                Storage::disk('public')->delete($pengumuman->file_surat);
            }

            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store directly to the public disk
            $path = Storage::disk('public')->putFileAs('surat', $file, $filename);
            
            // Save the correct path for database
            $data['file_surat'] = $path;
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file_surat) {
            Storage::delete('public/' . $pengumuman->file_surat);
        }

        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
