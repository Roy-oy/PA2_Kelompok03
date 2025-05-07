<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasiens = Pasien::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Pasien',
            'data' => $pasiens
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Pasien',
            'data' => $pasien
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keluhan_sakit' => 'required|string',
        ]);
    }
}