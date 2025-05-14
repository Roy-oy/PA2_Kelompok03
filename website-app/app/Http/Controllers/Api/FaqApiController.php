<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    public function index()
    {
        $faq = Faq::latest()->get()->map(function ($faq) {
            return [
                'id' => $faq->id,
                'question' => $faq->question ?? '-',
                'answer' => $faq->answer ?? '-',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar FAQ',
            'data' => $faq
        ]);
    }

    public function show(Faq $faq)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail FAQ',
            'data' => $faq
        ]);
    }
}