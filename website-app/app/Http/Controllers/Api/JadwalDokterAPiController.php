<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JadwalDokterApiController extends Controller
{
    // Map English days to Indonesian
    private function mapDayToIndonesian($day)
    {
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $dayMap[$day] ?? $day; // Fallback to original if not found
    }

    public function index()
    {
        $doctor_schedules = DoctorSchedule::with(['doctor', 'cluster'])->latest()->get()->map(function ($schedule) {
            // Format time to show in 08.00 - 09.00 format
            $start_time = date('H.i', strtotime($schedule->start_time));
            $end_time = date('H.i', strtotime($schedule->end_time));

            // Generate full URL for foto_profil
            $foto_profil = 'https://via.placeholder.com/150'; // Default fallback
            if ($schedule->doctor && !empty($schedule->doctor->foto_profil)) {
                $foto_profil = asset('storage/' . $schedule->doctor->foto_profil);
                Log::info("Foto profil URL generated: $foto_profil for doctor ID: {$schedule->doctor->id}");
            } else {
                Log::info("No foto_profil or doctor data for schedule ID: {$schedule->id}");
            }

            return [
                'id' => $schedule->id,
                'foto_profil' => $foto_profil,
                'namaDokter' => $schedule->doctor->nama ?? '-',
                'spesialis' => $schedule->doctor->spesialisasi ?? '-', 
                'email' => $schedule->doctor->email ?? '-',
                'schedule_day' => $this->mapDayToIndonesian($schedule->schedule_day),
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

    public function show(DoctorSchedule $schedule)
    {
        // Generate full URL for foto_profil
        $foto_profil = 'https://via.placeholder.com/150'; // Default fallback
        if ($schedule->doctor && !empty($schedule->doctor->foto_profil)) {
            $foto_profil = asset('storage/' . $schedule->doctor->foto_profil);
            Log::info("Foto profil URL generated: $foto_profil for doctor ID: {$schedule->doctor->id}");
        } else {
            Log::info("No foto_profil or doctor data for schedule ID: {$schedule->id}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Jadwal Dokter',
            'data' => [
                'id' => $schedule->id,
                'foto_profil' => $foto_profil,
                'namaDokter' => $schedule->doctor->nama ?? '-',
                'spesialis' => $schedule->doctor->spesialisasi ?? '-',
                'email' => $schedule->doctor->email ?? '-',
                'schedule_day' => $this->mapDayToIndonesian($schedule->schedule_day),
                'jamMulai' => date('H.i', strtotime($schedule->start_time)),
                'jamSelesai' => date('H.i', strtotime($schedule->end_time)),
                'ruangan' => $schedule->cluster->nama ?? '-',
                'status' => $schedule->status,
            ]
        ]);
    }
}