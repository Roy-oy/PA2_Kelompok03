<?php

// app/Http/Controllers/FaqController.php
namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::paginate(10); 
        return view('dashboard.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('dashboard.faq.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq = Faq::create($validatedData);
        
        // Jika request AJAX, kembalikan JSON
        if ($request->expectsJson()) {
            return response()->json($faq, 201);
        }
        
        // Jika request normal, redirect ke index dengan pesan sukses
        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    public function show($id)
    {
        $faq = Faq::findOrFail($id);
        
        // Jika request AJAX, kembalikan JSON
        if (request()->expectsJson()) {
            return response()->json($faq);
        }
        
        // Jika request normal, tampilkan view
        return view('dashboard.faq.show', compact('faq'));
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('dashboard.faq.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        // Jika request AJAX, kembalikan JSON
        if (request()->expectsJson()) {
            return response()->json(['message' => 'FAQ deleted']);
        }
        
        // Jika request normal, redirect ke index dengan pesan sukses
        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil dihapus!');
    }
}