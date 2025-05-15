<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Cluster;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $doctor_schedules = DoctorSchedule::with(['doctor', 'cluster'])->latest()->paginate(7);

        return view('dashboard.jadwal_dokter.index', compact('doctor_schedules'));
    }

    public function create()
    {
        $doctors = Doctor::all();
        $clusters = Cluster::all();
        return view('dashboard.jadwal_dokter.create', compact('doctors', 'clusters'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'cluster_id' => 'required|exists:clusters,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->route('jadwal_dokter.create')
                ->withErrors($validator)
                ->withInput();
        }

        DoctorSchedule::create($request->all());

        return redirect()->route('jadwal_dokter.index')
            ->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function show()
    {
      
    }

    public function edit($id)
    {
        $schedule = DoctorSchedule::with(['doctor', 'cluster'])->findOrFail($id);
        $doctors = Doctor::all();
        $clusters = Cluster::all();
        return view('dashboard.jadwal_dokter.edit', compact('schedule', 'doctors', 'clusters'));
    }

    public function update(Request $request, $id)
    {
        $schedule = DoctorSchedule::findOrFail($id);

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'cluster_id' => 'required|exists:clusters,id',
            'status' => 'required|in:active,inactive',
        ]);

        $schedule->update($request->all());

        return redirect()->route('jadwal_dokter.index')
            ->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $doctor_schedule = DoctorSchedule::findOrFail($id);
        $doctor_schedule->delete();

        return redirect()->route('jadwal_dokter.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}