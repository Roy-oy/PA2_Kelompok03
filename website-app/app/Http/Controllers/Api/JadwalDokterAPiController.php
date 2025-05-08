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
            // Format time to show in 08.00 - 09.00 format
            $start_time = date('H.i', strtotime($schedule->start_time));
            $end_time = date('H.i', strtotime($schedule->end_time));

            return [
                'id' => $schedule->id,
                'foto_profil' => $schedule->doctor->foto_profil ?? '-',
                'namaDokter' => $schedule->doctor->nama ?? '-',
                'spesialis' => $schedule->doctor->spesialisasi ?? '-', 
                'email' => $schedule->doctor->email ?? '-',
                'schedule_date' => $schedule->schedule_date,
                'jamMulai' => $start_time,
                'jamSelesai' => $end_time,
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
