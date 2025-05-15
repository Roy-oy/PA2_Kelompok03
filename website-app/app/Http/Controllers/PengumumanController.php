<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi_pengumuman' => 'required',
            'tanggal_upload' => 'required|date|after_or_equal:today',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['judul', 'isi_pengumuman', 'tanggal_upload']);
        
        // Set status based on tanggal_upload
        $inputDate = Carbon::parse($request->tanggal_upload);
        $data['status'] = $inputDate->isSameDay(Carbon::today()) ? 'publish' : 'pending';

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('surat', $file, $filename);
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
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi_pengumuman' => 'required',
            'tanggal_upload' => 'required|date|after_or_equal:today',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['judul', 'isi_pengumuman', 'tanggal_upload']);
        
        // Set status based on tanggal_upload
        $inputDate = Carbon::parse($request->tanggal_upload);
        $data['status'] = $inputDate->isSameDay(Carbon::today()) ? 'publish' : 'pending';

        if ($request->hasFile('file_surat')) {
            // Delete old file if exists
            if ($pengumuman->file_surat) {
                Storage::disk('public')->delete($pengumuman->file_surat);
            }

            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('surat', $file, $filename);
            $data['file_surat'] = $path;
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file_surat) {
            Storage::disk('public')->delete($pengumuman->file_surat);
        }

        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}