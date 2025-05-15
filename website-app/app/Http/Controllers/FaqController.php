<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        // Group FAQ by categories
        $categorizedFaqs = FaqCategory::with('faqs')->get();
        $uncategorizedFaqs = Faq::whereNull('category_id')->paginate(10);
        $allFaqs = Faq::all(); // Menambahkan ini untuk digunakan di view

        return view('dashboard.faq.index', compact('categorizedFaqs', 'uncategorizedFaqs', 'allFaqs'));
    }

    public function create()
    {
        $categories = FaqCategory::all();
        return view('dashboard.faq.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'nullable|exists:faq_categories,id',
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq = Faq::create($validatedData);

        if ($request->expectsJson()) {
            return response()->json($faq, 201);
        }

        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    public function show($id)
    {
        $faq = Faq::with('category')->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($faq);
        }

        return view('dashboard.faq.show', compact('faq'));
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $categories = FaqCategory::all();
        return view('dashboard.faq.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'nullable|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->category_id = $request->category_id;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'FAQ deleted']);
        }

        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil dihapus!');
    }

    // API untuk mobile
    public function getAllFaqs(Request $request)
{
    $kategori = $request->query('kategori');

    if ($kategori) {
        // Cari kategori berdasarkan nama
        $category = FaqCategory::where('name', $kategori)->first();

        if (!$category) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan.'
            ], 404);
        }

        // Ambil FAQ hanya untuk kategori tersebut
        $faqs = Faq::where('category_id', $category->id)->get();

        return response()->json([
            'category' => $category->name,
            'faqs' => $faqs
        ]);
    }

    // Default: ambil semua jika tidak ada query
    $categorizedFaqs = FaqCategory::with('faqs')->get();
    $uncategorizedFaqs = Faq::whereNull('category_id')->get();

    return response()->json([
        'categorized' => $categorizedFaqs,
        'uncategorized' => $uncategorizedFaqs
    ]);
}
}
