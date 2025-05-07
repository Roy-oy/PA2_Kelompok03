<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class JadwalDokterApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $doctor_schedules = DoctorSchedule::with(['doctor', 'cluster'])->latest()->get()->map(function ($schedule) {
        return [
            'id' => $schedule->id,
            'foto_profil' => $schedule->doctor->foto_profil ?? '-',
            'namaDokter' => $schedule->doctor->nama ?? '-',
            'spesialis' => $schedule->doctor->spesialisasi ?? '-', 
            'email' => $schedule->doctor->email ?? '-',
            'hari' => $schedule->day_name,
            'jamMulai' => $schedule->start_time,
            'jamSelesai' => $schedule->end_time,
            'ruangan' => $schedule->cluster->nama ?? '-',
            'status' => $schedule->status,
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Daftar Jadwal Dokter',
        'data' => $doctor_schedules
    ]);
}

    
    /**
     * Display the specified resource.
     */
    public function show(DoctorSchedule $schedule)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Jadwal Dokter',
            'data' => $schedule
        ]);
    }
}
