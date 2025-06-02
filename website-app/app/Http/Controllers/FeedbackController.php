<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the feedback.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Feedback::with(['pasien', 'medicalRecord']);

        // Filter berdasarkan tipe (semua atau hanya yang terkait rekam medis)
        $filter = $request->query('filter', 'all');
        if ($filter === 'medical') {
            $query->whereNotNull('id_medical_record');
        }

        // Pencarian
        if ($search = $request->query('search')) {
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })
            ->orWhere('comment', 'like', "%{$search}%");
        }

        $feedbacks = $query->latest()->paginate(10);

        return view('dashboard.feedback.index', compact('feedbacks', 'filter'));
    }

    /**
     * Display the specified feedback.
     *
     * @param \App\Models\Feedback $feedback
     * @return \Illuminate\View\View
     */
    public function show(Feedback $feedback)
    {
        $feedback->load(['pasien', 'medicalRecord']);
        return view('dashboard.feedback.show', compact('feedback'));
    }
}
