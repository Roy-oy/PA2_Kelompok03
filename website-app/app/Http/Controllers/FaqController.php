<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->paginate(10);
        return view('dashboard.faq.index', compact('faqs'));
    }

    public function create()
    {
        $kategoriOptions = ['umum', 'pendaftaran', 'layanan', 'pembayaran'];
        return view('dashboard.faq.create', compact('kategoriOptions'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori' => 'required|in:umum,pendaftaran,layanan,pembayaran',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        Faq::create($validatedData);

        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    public function show()
    {
       
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $kategoriOptions = ['umum', 'pendaftaran', 'layanan', 'pembayaran'];
        return view('dashboard.faq.edit', compact('faq', 'kategoriOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:umum,pendaftaran,layanan,pembayaran',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->only(['kategori', 'question', 'answer']));

        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil dihapus!');
    }
}