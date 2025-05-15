<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->get()->map(function ($faq) {
            $allowedCategories = ['umum', 'pendaftaran', 'layanan', 'pembayaran'];
            return [
                'id' => $faq->id,
                'kategori' => in_array(strtolower($faq->kategori), $allowedCategories) ? $faq->kategori : 'umum',
                'question' => $faq->question ?? '-',
                'answer' => $faq->answer ?? '-',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar FAQ',
            'data' => $faqs
        ]);
    }

    public function show(Faq $faq)
    {
        $allowedCategories = ['umum', 'pendaftaran', 'layanan', 'pembayaran'];
        $faqData = [
            'id' => $faq->id,
            'kategori' => in_array(strtolower($faq->kategori), $allowedCategories) ? $faq->kategori : 'umum',
            'question' => $faq->question ?? '-',
            'answer' => $faq->answer ?? '-',
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail FAQ',
            'data' => $faqData
        ]);
    }
}